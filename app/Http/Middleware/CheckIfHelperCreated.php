<?php

namespace App\Http\Middleware;

use App\Models\Client;
use App\Models\Helper;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIfHelperCreated
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
            return redirect()->route('helper.login');
        }

        // Get Helper data from DB
        $helper = Helper::where('user_id', auth()->user()->id)->first();

        // If helper not found
        if (!$helper) {
            // Check if Client is created with same id
            $client = Client::where('user_id', auth()->user()->id)->first();

            // If client is found then duplicate data to helper
            if ($client) {

                // Check if client first name and last name is not null
                if ($client->first_name == null || $client->last_name == null || $client->city == null) {
                    return redirect()->route('client.profile')->with('error', 'Please fill your client detail first');
                }

                $helper = Helper::create([
                    'user_id' => auth()->user()->id,
                    'company_enabled' => $client->company_enabled ?? 0,
                    'first_name' => $client->first_name ?? '',
                    'middle_name' => $client->middle_name ?? '',
                    'last_name' => $client->last_name ?? '',
                    'gender' => $client->gender ?? '',
                    'date_of_birth' => $client->date_of_birth ?? '',
                    'tax_id' => $client->tax_id ?? '',
                    'phone_no' => $client->phone_no ?? '',
                    'suite' => $client->suite ?? '',
                    'street' => $client->street ?? '',
                    'city' => $client->city     ?? '',
                    'state' => $client->state ?? '',
                    'country' => $client->country ?? '',
                    'zip_code' => $client->zip_code ?? '',
                ]);
            }
            // If not then create a simple helper
            else {
                $helper = Helper::create([
                    'user_id' => auth()->user()->id,
                ]);
            }
        }

        // dd($helper);


        // update client info in user table
        User::where('id', auth()->user()->id)->update(['helper_enabled' => 1]);

        return $next($request);
    }
}
