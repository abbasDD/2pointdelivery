<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Hash;

class GoogleLoginController extends Controller
{

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $user = Socialite::driver('google')->user();

        // Check if the user exists in the database
        $existingUser = User::where('email', $user->email)->first();

        if ($existingUser) {
            // If the user exists, log them in
            Auth::login($existingUser);
        } else {
            // If the user doesn't exist, create a new user
            $newUser = new User();
            $newUser->name = $user->name;
            $newUser->email = $user->email;
            $newUser->save();

            // Log in the new user
            Auth::login($newUser);
        }

        // Redirect the user to the home page or their intended destination
        return redirect()->route('home');
    }
}
