<?php

namespace App\Http\Controllers;

use App\Models\AddressBook;
use App\Http\Requests\StoreAddressBookRequest;
use App\Http\Requests\UpdateAddressBookRequest;

class AddressBookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get kyc details of logged in user
        $addressBooks = AddressBook::where('user_id', auth()->user()->id)->get();
        // dd($kycDetails->front_image);
        return view('client.addressBooks.index', compact('addressBooks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAddressBookRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(AddressBook $addressBook)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AddressBook $addressBook)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAddressBookRequest $request, AddressBook $addressBook)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AddressBook $addressBook)
    {
        //
    }
}
