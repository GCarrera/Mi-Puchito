<!DOCTYPE html>
<head>
  <title>Pusher Test</title>
  <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script>

    // Enable pusher logging - don't include this in production
    //Pusher.logToConsole = true;

    var pusher = new Pusher('112843611a21d52b8514', {
      cluster: 'us2'
    });

    var channel = pusher.subscribe('my-channel');
    channel.bind('App\\Events\\MyEvent', function(data) {
      console.log(data)
      alert(JSON.stringify(data));
    });
  </script>
</head>
<body>
  
  <div class="container" id="app">
    
  </div>
</body>