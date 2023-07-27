<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    function index()
    {
        return view('usuarios.index');
    }
}
