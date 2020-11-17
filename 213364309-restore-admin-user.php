<?php

/**
  * Plesk admin user injector
  *
  * Restores 'admin'@'localhost' user to the local MySQL/MariaDB server
  * Refer to https://support.plesk.com/hc/en-us/articles/213364309
  *
  * Tested with: MySQL 5.1+ (up to 8.0), MariaDB 5.5+ (up to 10.4)
  * Requires: PHP 5.4+, PHP 7.0+
  * Maintainer: Aleksandr Bashurov
  *
  * Copyright 1999-2019. Plesk International GmbH. All rights reserved.
  */

/**
 * Class that interacts with the init service
 */
class Service
{
    /**
     * @var string $init  Either 'legacy' or 'systemd'
     */
    private $init;

    /**
     * @var string $serviceName
     */
    private $serviceName;
    const KNOWN_SERVICES = ['mysqld', 'mysql', 'mariadb'];

    /**
     * @var string|null $serviceWrapper
     */
    private $serviceWrapper;
    const KNOWN_SERVICE_WRAPPERS = ['/usr/sbin/service', '/sbin/service'];

    public function __construct()
    {
        if (file_exists('/etc/os-release')) {
            /* This file is required by systemd 43+, so it must be present in any supported OS */
            $this->init = 'systemd';
        } else {
            $this->init = 'legacy';
        }
        $this->serviceName = $this->extractServiceName();
        Logger::info('Found init ' . $this->init);
    }

    /**
     * Restarts MySQL server
     * 
     * @return int exit code of the init management utility
     */
    public function restartServer()
    {
        $result = [];
        $exitCode = 0;
        Logger::write('Restarting MySQL server');
        switch ($this->init) {
            case 'systemd':
                exec('/bin/systemctl restart ' . $this->serviceName, $result, $exitCode);
                break;
            case 'legacy':
                exec($this->getServiceWrapperName() . ' ' . $this->serviceName . ' restart', $result, $exitCode);
                break;
        }
        Logger::info('Restart completed with exit code ' . $exitCode);
        return $exitCode;
    }

    /**
     * Searches for the service with the provided name in the init
     * 
     * @throws \Exception if there was no match
     * 
     * @return string
     */
    private function extractServiceName()
    {
        $result = [];
        switch ($this->init) {
            case 'systemd':
                exec('/bin/systemctl list-units --type service', $result);
                foreach (self::KNOWN_SERVICES as $name) {
                    foreach ($result as $service) {
                        $nameLength = strlen($name);
                        if (substr(trim($service), 0, 8 + $nameLength) === $name . '.service') {
                            Logger::info('Determined service name: ' . $name);
                            return $name;
                        }
                    }
                }
                break;
            case 'legacy':
                exec($this->getServiceWrapperName() . ' --status-all', $result);
                foreach (self::KNOWN_SERVICES as $name) {
                    foreach ($result as $service) {
                        $nameLength = strlen($name);
                        /* This is due to the 'mysql'/'mysqld' */
                        if (substr(ltrim($service), 0, $nameLength + 1) === $name . ' ') {
                            Logger::info('Determined service name: ' . $name);
                            return $name;
                        }
                    }
                }
                break;
        }
        throw new \Exception('Cannot determine MySQL service name');
    }

    /**
     * Checks SysV/upstart wrapper location
     * 
     * @throws \Exception is none of the default locations is correct
     * 
     * @return string
     */
    private function getServiceWrapperName()
    {
        if ($this->serviceWrapper === null) {
            foreach (self::KNOWN_SERVICE_WRAPPERS as $wrapper) {
                Logger::debug('Trying legacy wrapper in ' . $wrapper);
                if (file_exists($wrapper)) {
                    Logger::info('Found legacy wrapper in ' . $wrapper);
                    $this->serviceWrapper = $wrapper;
                    return $wrapper;
                }
            }
            throw new \Exception('Could not determine service management wrapper name');
        }
        return $this->serviceWrapper;
    }
}

class Connection
{
    /**
     * @var mysqli $instance
     */
    private $instance;

    /**
     * @var ServerConfigurator $configurator
     */
    private $configurator;

    /**
     * @param ServerConfigurator $configurator  Instance of the configurator
     * 
     * @throws \Exception if connection could not be established for some reason
     */
    public function __construct($configurator)
    {
        $this->configurator = $configurator;

        $password = $this->configurator->getPassword();
        $mysql = mysqli_init();
        if ($mysql->options(MYSQLI_OPT_CONNECT_TIMEOUT, 10) === false) {
            throw new \Exception('Failed to configure connection timeout!');
        }
        /* @ here is for Plesk with upgraded MySQL/MariaDB instances */
        if (false === @$mysql->real_connect(
            Configuration::getHostname(),
            Configuration::getUsername(),
            $password,
            'mysql',
            3306,
            $this->configurator->getSocket()
        )) {
            throw new \Exception('Connection to Plesk database failed: ' . mysqli_connect_error());
        }
        if (!$mysql->set_charset('UTF8')) {
            throw new \Exception('Failed to set charset to UTF8: ' . $mysql->error);
        }
        $this->instance = $mysql;
    }

    /**
     * Checks if the current user has the 'GRANT ALL' privilege on all tables
     * 
     * @return bool
     */
    public function hasAllPrivileges()
    {
        $grantQuery = $this->instance->query('SHOW GRANTS');
        if (! $grantQuery instanceof \mysqli_result) {
            return false;
        }
        $grants = $grantQuery->fetch_all()[0][0];
        $grantQuery->free();

        return substr($grants, 0, 27) === 'GRANT ALL PRIVILEGES ON *.*';
    }

    /**
     * Returns internal mysqli instance
     * 
     * @return \mysqli
     */
    public function getConnection()
    {
        return $this->instance;
    }
}

class Server
{
    /**
     * @var ServerConfigurator $configurator
     */
    private $configurator;

    /**
     * @var Service $service
     */
    private $service;

    /**
     * @var int[]|null $version  For example, [5, 5]
     */
    private $version;

    /**
     * @var string|null $flavor  Either 'MariaDB', or 'MySQL'
     */
    private $flavor;

    /**
     * @param ServerConfigurator $configurator
     * @param Service            $service
     */
    public function __construct($configurator, $service)
    {
        $this->configurator = $configurator;
        $this->service = $service;
    }

    /**
     * Creates the 'admin'@'localhost' user in the database
     * 
     * @return void
     */
    public function injectAdmin()
    {
        Logger::write('Disabling ACL tables in configuration');
        $this->configurator->setSkipGrants(true);
        $this->service->restartServer();

        try {
            $connection = new Connection($this->configurator);
            $this->guessVersionAndFlavor($connection);

            Logger::debug(
                'Determined server version: '
                    . $this->version[0] . '.'
                    . $this->version[1]
            );
            Logger::debug('Determined server flavor: ' . $this->flavor);

            $this->runQueries($this->generateSqlQueries(), $connection);
        } catch (\Exception $e) {
            Logger::error('Could not inject admin user: ' . $e->getMessage());
        } finally {
            Logger::write('Restoring previous configuration');
            $this->configurator->setSkipGrants(false);
            $this->service->restartServer();
        }
    }

    /**
     * Tries to guess what MySQL version and flavor we have
     * 
     * @param Connection $handle  MySQL connection wrapper instance
     * 
     * @return void
     */
    private function guessVersionAndFlavor($handle)
    {
        $connection = $handle->getConnection();

        $versionQuery = $connection->query('SHOW VARIABLES LIKE "version"');
        $versionValue = $versionQuery->fetch_all()[0][1];
        $versionQuery->free();

        $versionArray = explode('.', $versionValue);
        $this->version = [
            intval($versionArray[0]),
            intval($versionArray[1]),
        ];

        if (strpos(strtolower($versionValue), 'mariadb') !== false) {
            $this->flavor = 'MariaDB';
            return;
        }

        /* Continue checking whether it's MariaDB by looking for AriaDB */
        $flavorQuery = $connection->query('SHOW VARIABLES LIKE "aria%"');
        if ($flavorQuery->num_rows > 0) {
            $this->flavor = 'MariaDB';
            $flavorQuery->free();
            return;
        }

        $this->flavor = 'MySQL';
    }

    /**
     * Runs an array of provided queries
     * 
     * @param string[] $queries   SQL queries generated by generateSqlQueries
     * @param Connection $handle  MySQL connection wrapper instance
     * 
     * @return void
     */
    private function runQueries($queries, $handle)
    {
        $connection = $handle->getConnection();
        
        Logger::write('Reloading ACL tables');
        $connection->query('FLUSH PRIVILEGES');

        foreach ($queries as $query) {
            Logger::write('Executing query: `' . $query . '`');
            $connection->query($query);
        }
    }

    /**
     * Builds an SQL query to add the user to the list
     *
     * @return string[]  Array with commands that should be executed
     */
    private function generateSqlQueries()
    {
        $user = Configuration::getUsername();
        $host = Configuration::getHostname();
        $password = $this->configurator->getPassword();

        /* MySQL after 5.7 enforces plugin authentication */
        if ($this->flavor === 'MySQL'
            && $this->version[0] === 5
            && $this->version[1] >= 7
        ) {
            return [
                $this->getDeleteQuery($user, $host),
                $this->getModernMysqlCreateUser($user, $host, $password),
                $this->getGrantQuery($user, $host),
            ];
        }

        /* Other versions do not, though */
        return [
            $this->getDeleteQuery($user, $host),
            $this->getCreateUser($user, $host, $password),
            $this->getGrantQuery($user, $host),
        ];
    }

    /**
     * Creates a generic DELETE query
     *
     * @param string $name   MySQL username
     * @param string $host   MySQL user's hostname
     *
     * @return string
     */
    private function getDeleteQuery($name, $host)
    {
        return "DROP USER \"{$name}\"@\"{$host}\";";
    }

    /**
     * Creates a generic GRANT ALL query
     * 
     * @param string $user  MySQL username
     * @param string $host  MySQL user's hostname
     * 
     * @return string
     */
    private function getGrantQuery($user, $host)
    {
        return "GRANT ALL ON *.* TO \"{$user}\"@\"{$host}\" WITH GRANT OPTION";
    }

    /**
     * Creates a legacy CREATE USER query (MariaDB 5.5 to 10.4, MySQL 5.1 to 5.6)
     * 
     * @param string $user      MySQL username
     * @param string $host      MySQL user's hostname
     * @param string $password  Target user password
     * 
     * @return string
     */
    private function getCreateUser($user, $host, $password)
    {
        return "CREATE USER \"{$user}\"@\"{$host}\" IDENTIFIED BY \"{$password}\"";
    }

    /**
     * Creates a modern (plugin-aware) CREATE USER query compatible with MySQL 5.7 and above
     * 
     * @param string $user      MySQL username
     * @param string $host      MySQL user's hostname
     * @param string $password  Target user password
     * 
     * @return string
     */
    private function getModernMysqlCreateUser($user, $host, $password)
    {
        return "CREATE USER \"{$user}\"@\"{$host}\" IDENTIFIED WITH \"mysql_native_password\" BY \"{$password}\"";
    }
}

class ServerConfigurator
{
    /**
     * @var string $socket  Socket file location
     */
    private $socket;

    /**
     * @var string $configFile  Main config file location
     */
    private $configFile;
    const DEFAULT_LOCATIONS = ['/etc/my.cnf', '/etc/mysql/my.cnf'];

    /**
     * @var string[] $configContent  Config file contents
     */
    private $configContent;

    /**
     * @var string[] $configBackup  Original config backup
     */
    private $configBackup;

    /**
     * @var string $password  Database admin password
     */
    private $password;

    public function __construct()
    {
        $this->socket = $this->findSocket();
        Logger::debug('Found socket: ' . $this->socket);

        $this->configFile = $this->findConfig();
        Logger::debug('Found configuration file: ' . $this->configFile);

        $this->configContent = $this->configBackup = $this->loadConfig();
        $this->password = $this->loadPassword();
    }

    /**
     * Modifies configuration to remove authentication from the server
     * 
     * @param bool $enable
     * 
     * @return void
     */
    public function setSkipGrants($enable)
    {
        if ($enable) {
            $this->injectOverride();
        } else {
            $this->revertConfig();
        }
    }

    /**
     * Extracts Plesk 'admin'@'localhost' password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Extracts MySQL socket location
     *
     * @return string
     */
    public function getSocket()
    {
        return $this->socket;
    }

    /**
     * Checks MySQL socket location in Plesk configuration file
     * 
     * @throws \Exception if the value could not be found
     * 
     * @return string
     */
    private function findSocket()
    {
        $psaConfig = explode(PHP_EOL, file_get_contents('/etc/psa/psa.conf'));
        $socketList = array_filter($psaConfig, function ($line) {
            return substr(trim($line), 0, 12) === 'MYSQL_SOCKET';
        });
        if (count($socketList) === 0) {
            throw new \Exception('Configuration file `/etc/psa/psa.conf` does not have a socket file specified');
        }
        $socketEntry = array_values($socketList)[0];
        return trim(explode(' ', $socketEntry)[1]);
    }

    /**
     * Searches for the main MySQL configuration file
     * 
     * @throws \Exception if none of the default locations matched
     * 
     * @return string
     */
    private function findConfig()
    {
        foreach(self::DEFAULT_LOCATIONS as $location) {
            if (file_exists($location)) {
                return $location;
            }
        }
        throw new \Exception('Could not find main configuration file for the database server!');
    }

    /**
     * Loads admin password from the filesystem
     * 
     * @throws \Exception if the file with password does not exist
     * 
     * @return string
     */
    private function loadPassword()
    {
        if (!file_exists('/etc/psa/.psa.shadow')) {
            throw new \Exception('Could not locate /etc/psa/.psa.shadow file');
        }
        return file_get_contents('/etc/psa/.psa.shadow');
    }

    /**
     * Reads the configuration file
     * 
     * @return string[]
     */
    private function loadConfig()
    {
        return explode(PHP_EOL, file_get_contents($this->configFile));
    }

    /**
     * Injects skip-grant-tables and skip-networking to the config
     * 
     * @return void
     */
    private function injectOverride()
    {
        $sectionPosition = array_search('[mysqld]', $this->configContent);
        if ($sectionPosition === false) {
            $this->configContent = array_merge(
                $this->configContent,
                [
                    '',
                    '[mysqld]',
                    'skip-grant-tables',
                    'skip-networking',
                ]
            );
        } else {
            array_splice(
                $this->configContent,
                $sectionPosition + 1,
                0,
                [
                    'skip-grant-tables',
                    'skip-networking',
                ]
            );
        }
        $this->storeConfig($this->configContent);
    }

    /**
     * Saves configuration back to the file
     * 
     * @param string[] $config  Target configuration
     * 
     * @return void
     */
    private function storeConfig($config)
    {
        file_put_contents($this->configFile, implode(PHP_EOL, $config));
    }

    /**
     * Reverts configuration to the one used before script was executed
     * 
     * @return void
     */
    private function revertConfig()
    {
        $this->storeConfig($this->configBackup);
    }
}

final class Configuration
{
    /**
     * @var int $verbosity
     */
    private static $verbosity = 0;

    /**
     * @var string $username
     */
    private static $username = 'admin';

    /**
     * @var string $hostname
     */
    private static $hostname = 'localhost';

    /**
     * Changes verbosity for the Logger
     *
     * @param int $newLevel
     *
     * @return void
     */
    public static function setVerbosity($newLevel)
    {
        if (!is_int($newLevel)) {
            throw new \Exception("Type Error: expected integer, received " . gettype($newLevel));
        }
        static::$verbosity = $newLevel;
    }

    /**
     * Changes target username
     *
     * @param string $username
     *
     * @return void
     */
    public static function setUsername($username)
    {
        static::$username = $username;
    }

    /**
     * Changes target hostname
     *
     * @param string $hostname
     *
     * @return void
     */
    public static function setHostname($hostname)
    {
        static::$hostname = $hostname;
    }

    /**
     * Extracts current verbosity level
     *
     * @return int
     */
    public static function getVerbosity()
    {
        return static::$verbosity;
    }

    /**
     * Fetches target username for the restoration
     * 
     * @return string
     */
    public static function getUsername()
    {
        return static::$username;
    }

    /**
     * Fetches target hostname for the restoration
     * 
     * @return string
     */
    public static function getHostname()
    {
        return static::$hostname;
    }
}

final class Logger
{
    private function __construct() {}

    /**
     * Logs if the global verbosity level is debug
     *
     * @param string $value
     * 
     * @return void
     */
    public static function debug($value)
    {
        if (Configuration::getVerbosity() >= 2) {
            fwrite(STDERR, 'DEBUG: ' . $value . PHP_EOL);
        }
    }

    /**
     * Logs if the global verbosity level is verbose
     *
     * @param string $value
     * 
     * @return void
     */
    public static function info($value)
    {
        if (Configuration::getVerbosity() >= 1) {
            fwrite(STDOUT, 'INFO: ' . $value . PHP_EOL);
        }
    }

    /**
     * Logs to the console stderr
     *
     * @param string $value
     * 
     * @return void
     */
    public static function error($value)
    {
        fwrite(STDERR, '!ERROR: ' . $value . PHP_EOL);
    }

    /**
     * Logs to the console stdout
     *
     * @param string $value
     * 
     * @return void
     */
    public static function write($value)
    {
        fwrite(STDOUT, $value . PHP_EOL);
    }
}

/**
 * @return void
 */
function helpCommand()
{
    $script_name = basename(__FILE__);
    Logger::write(<<<HELP
$script_name: Inject Plesk administrator user into the MySQL server
Version: 1.0

Usage:
   $script_name -v -d -y

      Options:
   -v     Enables verbose output

   -d     Enables debug output

   -y     Disables user pre-check

   -h     Shows this help message
HELP
   );
}

/**
 * Parses args and returns an associative array
 *
 * @return (bool|int)[]
 */
function saneOpts()
{
    $options = getopt('vdh');
    if ($options === false) {
        return [];
    }
    return [
        'verbosityLevel' => (
            array_key_exists('d', $options) ? 2 : (
            array_key_exists('v', $options) ? 1 : (
            0
        ))),
        'helpMessage' => array_key_exists('h', $options),
        'ignorePrecheck' => array_key_exists('y', $options),
    ];
}

/**
 * Checks that basic requirements are met
 * 
 * @throws \Exception if user does not have root access
 * @throws \Exception if script is executed on Windows
 * 
 * @return void
 */
function checkRequirements()
{
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        /* On Windows,  */
        throw new \Exception('This script must not be run on Windows');
    }
    if (posix_geteuid() !== 0) {
        throw new \Exception('This script must be executed with root privileges');
    }
}

/**
 * Checks that user does not actually exist or has improper rights
 * 
 * @param ServerConfigurator $config
 * 
 * @return bool  should the script be executed
 */
function checkUser($config)
{
    try {
        $connection = new Connection($config);
        return !$connection->hasAllPrivileges();
    } catch (\Exception $e) {
        return true;
    }
}

/**
 * Main function of the script
 *
 * @return void
 */
function main()
{
    $script_name = basename(__FILE__);
    $options = saneOpts();
    if ($options['helpMessage']) {
        helpCommand();
        exit(0);
    }
    checkRequirements();

    Configuration::setVerbosity($options['verbosityLevel']);

    $dbConf = new ServerConfigurator();
    $service = new Service();
    Logger::write('Checking if administrator exists');
    if (checkUser($dbConf) === false && $options['ignorePrecheck'] === false) {
        Logger::error(
            'User \'admin\'@\'localhost\' already exists on the system and has required privileges.'
            . 'Run the script with \'-y\' option to skip this check'
        );
        exit (1);
    }

    $server = new Server($dbConf, $service);
    $server->injectAdmin();

    Logger::write('Execution has completed');
}

main();
