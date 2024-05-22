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

        // Get Client data from DB
        $client = Client::where('user_id', auth()->user()->id)->first();

        // If client not found
        if (!$client) {
            // Check if Helper is created with same id
            $helper = Helper::where('user_id', auth()->user()->id)->first();

            // If helper is found then duplicate data to client
            if ($helper) {
                // Check if helper first name and last name is not null
                if ($helper->first_name == null || $helper->last_name == null) {
                    return redirect()->route('helper.profile')->with('error', 'Please fill your helper detail first');
                }

                $client = Client::create([
                    'user_id' => auth()->user()->id,
                    'company_enabled' => $helper->company_enabled ?? 0,
                    'first_name' => $helper->first_name ?? '',
                    'middle_name' => $helper->middle_name ?? '',
                    'last_name' => $helper->last_name ?? '',
                    'gender' => $helper->gender ?? '',
                    'date_of_birth' => $helper->date_of_birth ?? '',
                    'tax_id' => $helper->tax_id ?? '',
                    'phone_no' => $helper->phone_no ?? '',
                    'suite' => $helper->suite ?? '',
                    'street' => $helper->street ?? '',
                    'city' => $helper->city     ?? '',
                    'state' => $helper->state ?? '',
                    'country' => $helper->country ?? '',
                    'zip_code' => $helper->zip_code ?? '',
                ]);
            }
            // If not then create a simple client
            else {
                $client = Client::create([
                    'user_id' => auth()->user()->id,
                ]);
            }
        }

        // dd($client);

        // update client info in user table
        User::where('id', auth()->user()->id)->update(['client_enabled' => 1]);

        return $next($request);
    }
}
