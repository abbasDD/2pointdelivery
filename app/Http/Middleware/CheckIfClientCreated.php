<?php

namespace App\Http\Middleware;

use App\Models\Client;
use App\Models\Helper;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIfClientCreated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // dd(auth()->user()->id);
        // Redirect to login if not logged in
        if (auth()->user()->id == null) {
            return redirect()->route('client.login');
        }

        // Check if client is enabled in user table
        $user = User::where('id', auth()->user()->id)->first();
        if (!$user->client_enabled) {
            return redirect()->back()->with('error', 'No client profile found');
        }

        return $next($request);
    }
}
