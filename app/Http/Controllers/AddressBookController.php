<?php

namespace App\Http\Controllers;

use App\Models\AddressBook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressBookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get kyc details of logged in user
        $addressBooks = AddressBook::where('user_id', Auth::user()->id)->get();
        // dd($kycDetails->front_image);
        return view('client.addressBooks.index', compact('addressBooks'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {

        $addressBook = AddressBook::where('user_id', Auth::user()->id)->where('id', $request->id)->first();
        if (!$addressBook) {
            return redirect()->back()->with('error', 'AddressBook not found');
        }

        return view('client.addressBooks.edit', compact('addressBook'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {

        $addressBook = AddressBook::where('user_id', Auth::user()->id)->where('id', $request->id)->first();
        if (!$addressBook) {
            return redirect()->back()->with('error', 'AddressBook not found');
        }

        $request->validate([
            'pickup_address' => 'required',
            'dropoff_address' => 'required',
            'pickup_latitude' => 'required',
            'pickup_longitude' => 'required',
            'dropoff_latitude' => 'required',
            'dropoff_longitude' => 'required',
            'receiver_name' => 'required',
            'receiver_phone' => 'required',
            'receiver_email' => 'nullable|email',
        ]);

        $addressBook->pickup_address = $request->pickup_address;
        $addressBook->dropoff_address = $request->dropoff_address;
        $addressBook->pickup_latitude = $request->pickup_latitude;
        $addressBook->pickup_longitude = $request->pickup_longitude;
        $addressBook->dropoff_latitude = $request->dropoff_latitude;
        $addressBook->dropoff_longitude = $request->dropoff_longitude;
        $addressBook->receiver_name = $request->receiver_name;
        $addressBook->receiver_phone = $request->receiver_phone;
        $addressBook->receiver_email = $request->receiver_email;
        $addressBook->save();

        // Redirect to success page
        return redirect('client/address-books')->with('success', 'AddressBook updated successfully');
    }
}
