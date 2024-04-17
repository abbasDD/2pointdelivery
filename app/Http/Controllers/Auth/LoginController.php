<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/index';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    //Routes for the Clients

    public function showLoginFormClient()
    {
        return view('client.auth.login');
    }

    //Routes for the Helpers

    public function showLoginFormHelper()
    {
        return view('helper.auth.login');
    }

    //Routes for the Admin



    public function showAdminLoginForm()
    {
        return view('admin.auth.login');
    }

    public function postAdminLoginForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        $user = User::where('email', $request->email)->first();

        if (!$user || $user->user_type != 'admin') {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            // dd($credentials);
            return redirect()->intended('admin');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
}
