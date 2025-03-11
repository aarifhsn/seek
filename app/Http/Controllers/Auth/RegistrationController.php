<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Registered;

class RegistrationController extends Controller
{
    public function index()
    {
        return view('register');
    }

    public function store(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'remember_token' => Str::random(10),
        ]);

        Log::info('Dispatching Registered event for: ' . $user->email);
        event(new Registered($user));

        // Temporarily log in the user for few routes
        auth()->login($user);

        return redirect()->route('verification.notice')->with('message', 'Registration successful. Please check your email for verification.');
    }
}
