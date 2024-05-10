<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Illuminate\View\View;
use Symfony\Component\Process\Process;

class AuthController extends Controller
{
    public function index(): View
    {
        /*$process = new Process(['ls', '/var/www/html']);
        
        $process->run();
        dd($process->getOutput());*/
        //dd(env('K8S_BEARER_TOKEN'));
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            return redirect()->intended('/Dashboard');
        } else {
            return back()->withErrors(['email' => 'Invalid credentials']);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
