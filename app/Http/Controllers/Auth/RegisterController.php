<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
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
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role_as'  => ['required'],
            'status'   => ['required'],
        ],
        [
            'name.required'     => 'Campo de ser preenchido.',
            'name.string'       => 'Digite apenas letras.',
            'name.max'          => 'Excedeu o limite de digitos.',
            'email.required'    => 'Campo de ser preenchido.',
            'email.email'       => 'E-mail inválido.',
            'email.max'         => 'Excedeu o limite de digitos.',
            'email.unique'      => 'E-mail já cadastrado.',
            'password.required' => 'Campo de ser preenchido.',
            'password.min'      => 'Senha deve possuir no mínimo 8 caracteres.',
            'password.confirmed'=> 'Senhas não coincidem.',
            'role_as.required'  => 'Campo de ser preenchido.',
            'status.required'   => 'Campo de ser preenchido.',
            
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role_as'  => $data['role_as'],
            'status'   => $data['status'],
        ]);
    }
}
