<?php

namespace App\Http\Middleware;

use App\Models\Client;
use App\Models\Helper;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckIfHelperCreated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // dd(Auth::user()->id);
        // Redirect to login if not logged in
        if (Auth::user()->id == null) {
            return redirect()->route('helper.login');
        }

        // Check if helper is enabled in user table
        $user = User::where('id', Auth::user()->id)->first();
        if (!$user->helper_enabled) {
            return redirect()->back()->with('error', 'You need to be a Helper in order to access this page');
        }


        return $next($request);
    }
}
