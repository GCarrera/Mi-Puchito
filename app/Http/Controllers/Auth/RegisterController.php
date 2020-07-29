<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\User;
use App\People;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }


    protected function redirectTo()
    {
        if (auth()->user()->type == 'admin') {
            return '/admin';
        }

        return '/home';
    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'dni' => ['required', 'digits_between:7,9', 'unique:people,dni'],
            'name' => ['required', 'string', 'max:255'],
            // 'lastname' => ['required', 'string', 'max:255'],
            // 'phone_number' => ['required', 'digits:11'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return DB::transaction(function () use ($data) {

            $people = new People();
            
            $people->dni = $data['dni'];
            $people->name = $data['name'];
            // $people->lastname = $data['lastname'];
            // $people->phone_number = $data['phone_number'];

            $people->save();

            $peopleid = $people->lastid();

            return User::create([
                'email' => $data['email'],
                'people_id' => $peopleid,
                'password' => Hash::make($data['password']),
            ]);
        });
    }
}
