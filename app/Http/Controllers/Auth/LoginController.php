<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Client;
use App\Models\Helper;
use App\Models\SocialLoginSetting;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


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
        Session::put('intended_url', url()->previous());

        // Get Social Login Settings
        $socialLoginSetting = SocialLoginSetting::all();

        // Format it as a key-value associative array
        $socialLoginSettingPair = $socialLoginSetting->pluck('value', 'key')->all();

        // dd($socialLoginSettingPair);

        return view('client.auth.login', compact('socialLoginSettingPair'));
    }

    public function postClientLoginForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        $user = User::where('email', $request->email)->first();

        if (!$user || $user->user_type != 'user' || !$user->client_enabled) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }

        // If user is inactive
        if (!$user->is_active) {
            return back()->withErrors([
                'email' => 'Your account is inactive. Please contact the administrator.',
            ]);
        }

        // Check if password is null
        if ($user->password == null) {
            return back()->withErrors([
                'email' => 'Your account is linked to Third Party. Try using Third Party Login.',
            ]);
        }

        if (Auth::attempt($credentials, $request->filled('remember'))) {

            // Retrieve the intended URL after successful login
            $intendedUrl = Session::pull('intended_url');

            // Store login_type to Session
            session(['login_type' => 'client']);

            // Get client first name and last name
            $clientInfo = Client::where('user_id', $user->id)->first();
            if ($clientInfo) {
                session(['full_name' => $clientInfo->first_name . ' ' . $clientInfo->last_name]);
                // set profile_image
                if ($clientInfo->thumbnail) {
                    session(['thumbnail' => asset('images/users/thumbnail/' . $clientInfo->thumbnail)]);
                }
            }
            // dd(route('newBooking'));
            if ($intendedUrl == route('newBooking')) {
                // Redirect the user back to the intended URL
                return redirect()->to($intendedUrl)->with('success', 'Login Successful');
            } else {
                // Redirect the user back to the intended URL
                return redirect()->route('client.index')->with('success', 'Login Successful to Dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    //Routes for the Helpers

    public function showLoginFormHelper()
    {
        // Get Social Login Settings
        $socialLoginSetting = SocialLoginSetting::all();

        // Format it as a key-value associative array
        $socialLoginSettingPair = $socialLoginSetting->pluck('value', 'key')->all();

        // dd($socialLoginSettingPair);

        return view('helper.auth.login', compact('socialLoginSettingPair'));
    }


    public function postHelperLoginForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        $user = User::where('email', $request->email)->first();

        if (!$user || $user->user_type != 'user' || !$user->helper_enabled) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
        }

        // If user is inactive
        if (!$user->is_active) {
            return back()->withErrors([
                'email' => 'Your account is inactive. Please contact the administrator.',
            ]);
        }

        // Check if password is null
        if ($user->password == null) {
            return back()->withErrors([
                'email' => 'Your account is linked to Third Party. Try using Third Party Login.',
            ]);
        }

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            // Retrieve the intended URL after successful login
            $intendedUrl = Session::pull('intended_url');

            // Store login_type to Session
            session(['login_type' => 'helper']);

            // Get helper first name and last name
            $helperInfo = Helper::where('user_id', $user->id)->first();
            if ($helperInfo) {
                session(['full_name' => $helperInfo->first_name . ' ' . $helperInfo->last_name]);
                // set profile_image
                if ($helperInfo->thumbnail) {
                    session(['thumbnail' => asset('images/users/thumbnail/' . $helperInfo->thumbnail)]);
                }
            }

            // dd(route('newBooking'));
            if ($intendedUrl == route('newBooking')) {
                // Redirect the user back to the intended URL
                return redirect()->to($intendedUrl)->with('success', 'Login Successful');
            } else {
                // Redirect the user back to the intended URL
                return redirect()->route('helper.index')->with('success', 'Login Successful to Dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
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

            // Store login_type to Session
            session(['login_type' => 'admin']);

            // Get admin first name and last name
            $adminInfo = Admin::where('user_id', $user->id)->first();
            if ($adminInfo) {
                session(['full_name' => $adminInfo->first_name . ' ' . $adminInfo->last_name]);
                // set profile_image
                if ($adminInfo->thumbnail) {
                    session(['thumbnail' => asset('images/users/thumbnail/' . $adminInfo->thumbnail)]);
                }
            }

            return redirect()->intended('admin');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
}
