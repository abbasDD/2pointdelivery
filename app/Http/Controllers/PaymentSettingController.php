<?php

namespace App\Http\Controllers;

use App\Models\PaymentSetting;
use App\Http\Requests\StorePaymentSettingRequest;
use App\Http\Requests\UpdatePaymentSettingRequest;

class PaymentSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StorePaymentSettingRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(PaymentSetting $paymentSetting)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaymentSetting $paymentSetting)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePaymentSettingRequest $request, PaymentSetting $paymentSetting)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentSetting $paymentSetting)
    {
        //
    }
}
