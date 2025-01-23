<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class AuthController extends Controller
{
    // Login function
    public function login(Request $request)
    {
        // Validate incoming data
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Check if the user exists
        $user = User::where('email', $request->email)->first();

        // If user is found and the password matches
        if ($user && Hash::check($request->password, $user->password)) {
            // Store user information in the session
            Session::put('user_id', $user->id);
            Session::put('user_email', $user->email);
            return redirect()->route('home');
        }

        // If authentication fails
        return back()->with('error', 'Invalid email or password.');
    }

    // Logout function
    public function logout()
    {
        // Remove user information from the session
        Session::flush();
        return redirect()->route('login');
    }
}
