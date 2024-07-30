<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingDelivery;
use App\Models\BookingMoving;
use App\Models\Client;
use App\Models\SystemSetting;
use App\Models\User;
use Barryvdh\DomPDF\Facade as PDF;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PDFController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function generatePDF()
    {
        $users = User::get();

        $data = [
            'title' => 'Welcome to Elabd Technologies',
            'date' => date('m/d/Y'),
            'users' => $users
        ];

        $pdf = FacadePdf::loadView('myPDF', $data);

        // Define the path to save the PDF
        $path = public_path('pdfs/invoices');

        // Ensure the directory exists
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        // Save the PDF to the specified path
        $pdf->save($path . '/booking.pdf');

        // return back with success message
        return redirect()->back()->with('success', 'Test PDF generated successfully');
    }


    // Create booking Invoice
    public function bookingInvoicePDF(Request $request)
    {
        $booking_id = $request->id;


        // Check if booking exist
        $booking = Booking::where('id', $booking_id)
            ->with('prioritySetting')
            ->with('serviceType')
            ->with('serviceCategory')
            ->first();

        if (!$booking) {
            return 0;
        }

        $bookingPayment = [];

        // Check if booking->booking_type is delivery
        if ($booking->booking_type == 'delivery') {
            // Get booking delivery data
            $bookingDelivery = BookingDelivery::where('booking_id', $booking->id)->first();
            if (!$bookingDelivery) {
                return 0;
            }
            // Store to $bookingData
            $bookingPayment = $bookingDelivery;
        }

        // Check if booking->booking_type is moving
        if ($booking->booking_type == 'moving') {
            // Get booking moving data
            $bookingMoving = BookingMoving::where('booking_id', $booking->id)->first();
            if (!$bookingMoving) {
                return 0;
            }
            // Store to $bookingData
            $bookingPayment = $bookingMoving;
        }

        if (!$bookingPayment) {
            return 0;
        }

        // Get company logo
        $website_logo = SystemSetting::where('key', 'website_logo')->first();

        if ($website_logo) {
            $company_logo = asset('images/logo/' . $website_logo->value);
        } else {
            $company_logo = asset('images/logo/default.png');
        }

        // Get client details
        $client_user = User::where('id', $booking->client_user_id)->first();
        if (!$client_user) {
            return 0;
        }

        // Get Client
        $client = Client::where('user_id', $booking->client_user_id)->first();
        if (!$client) {
            return 0;
        }
        // dd($company_logo);

        $data = [
            'title' => 'Booking Invoice - ' . $booking->uuid,
            'date' => date('m/d/Y'),
            'booking' => $booking,
            'bookingPayment' => $bookingPayment,
            'company_logo' => $company_logo,
            'client_user' => $client_user,
            'client' => $client,
            'index' => 1,
        ];

        $pdf = FacadePdf::loadView('pdfs/booking-invoice', $data);

        // Define the path to save the PDF
        $path = public_path('pdfs/invoices');

        // Ensure the directory exists
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        // Save the PDF to the specified path
        $pdf->save($path . '/' . $booking->uuid . '.pdf');

        // return back with success message
        return response()->json(['success' => 'Booking Invoice generated successfully']);
    }

    // Create booking Invoice
    static function bookingInvoiceForBooking($id)
    {
        $booking_id = $id;

        // Check if booking exist
        $booking = Booking::where('id', $booking_id)->first();
        if (!$booking) {
            return 0;
        }

        $bookingPayment = [];

        // Check if booking->booking_type is delivery
        if ($booking->booking_type == 'delivery') {
            // Get booking delivery data
            $bookingDelivery = BookingDelivery::where('booking_id', $booking->id)->first();
            if (!$bookingDelivery) {
                return 0;
            }
            // Store to $bookingData
            $bookingPayment = $bookingDelivery;
        }

        // Check if booking->booking_type is moving
        if ($booking->booking_type == 'moving') {
            // Get booking moving data
            $bookingMoving = BookingMoving::where('booking_id', $booking->id)->first();
            if (!$bookingMoving) {
                return 0;
            }
            // Store to $bookingData
            $bookingPayment = $bookingMoving;
        }

        if (!$bookingPayment) {
            return 0;
        }

        $data = [
            'title' => 'Booking Invoice - ' . $booking->uuid,
            'date' => date('m/d/Y'),
            'booking' => $booking,
            'bookingPayment' => $bookingPayment
        ];

        $pdf = FacadePdf::loadView('pdfs/booking-invoice', $data);

        // Define the path to save the PDF
        $path = public_path('pdfs/invoices');

        // Ensure the directory exists
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        // Save the PDF to the specified path
        $pdf->save($path . '/' . $booking->uuid . '.pdf');

        // return back with success message
        // return redirect()->back()->with('success', 'PDF generated successfully');
        return $booking->uuid . '.pdf';
    }


    // shippingLabelPDF
    public function shippingLabelPDF()
    {
        $users = User::get();

        $data = [
            'title' => 'SHipping Label - 123',
            'date' => date('m/d/Y'),
            'users' => $users
        ];

        $pdf = FacadePdf::loadView('pdfs/shipping-label', $data);

        // Define the path to save the PDF
        $path = public_path('pdfs/shipping-labels');

        // Ensure the directory exists
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        // Save the PDF to the specified path
        $pdf->save($path . '/booking.pdf');

        // return back with success message
        // return redirect()->back()->with('success', 'PDF generated successfully');
        return response()->json(['success' => 'Shipping Label generated successfully']);
    }
}
