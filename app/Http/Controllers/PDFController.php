<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingDelivery;
use App\Models\BookingMoving;
use App\Models\Client;
use App\Models\SystemSetting;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
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

        // Check if invoice file is null

        if (is_null($booking->invoice_file)) {

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
                return redirect()->back()->with('error', 'No data found');
            }

            // Get company logo
            $website_logo = SystemSetting::where('key', 'website_logo')->first();
            $company_logo = $website_logo ? asset('images/logo/' . $website_logo->value) : asset('images/logo/default.png');

            // Get client details
            $client_user = User::where('id', $booking->client_user_id)->first();
            $client = Client::where('user_id', $booking->client_user_id)->first();

            if (!$client_user || !$client) {
                return redirect()->back()->with('error', 'No data found');
            }

            // Data for the PDF
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

            // Generate PDF
            $pdf = FacadePdf::loadView('pdfs/booking-invoice', $data);

            // Define the path to save the PDF
            $path = public_path('pdfs/invoices');

            // Ensure the directory exists
            if (!File::exists($path)) {
                File::makeDirectory($path, 0755, true);
            }

            // Save the PDF to the specified path
            $fileName = $booking->uuid . '.pdf';
            $filePath = 'pdfs/invoices/' . $fileName;
            $pdf->save(public_path($filePath));

            // Update the booking with the file path
            $booking->update(['invoice_file' => $filePath]);

        } else {
            // If invoice_file is already set, use the existing file
            $filePath = $booking->invoice_file;
        }

        // Download the PDF
        return response()->download(public_path($filePath));

    }

    public function downloadLabel(Request $request)
    {
        $booking_id = $request->id;

        // Check if booking exists
        $booking = Booking::where('id', $booking_id)->first();

        if (!$booking) {
            return redirect()->back()->with('error', 'Booking not found');
        }

        // Check if label_file already exists
        if (!$booking->label_file) {
            // Get the required data for generating the PDF
            $users = User::get();  // Example data, modify based on your needs

            $data = [
                'title' => 'Shipping Label - ' . $booking->uuid,
                'date' => date('m/d/Y'),
                'booking' => $booking,
                'users' => $users  // Example data
            ];

            // Load the view for the PDF
            $pdf = FacadePdf::loadView('pdfs/shipping-label', $data)
                ->setPaper('a4', 'landscape');

            // Define the path to save the PDF
            $path = public_path('pdfs/labels');

            // Ensure the directory exists
            // if (!File::exists($path)) {
            //     File::makeDirectory($path, 0755, true);
            // }

            // Save the PDF to the specified path with the booking UUID as the filename
            $filePath = $path . '/' . $booking->uuid . '-label.pdf';
            $pdf->save($filePath);

            // Update the label_file field in the booking record
            $booking->update(['label_file' => 'pdfs/labels/' . $booking->uuid . '-label.pdf']);
        }

        // Return success response
        return response()->download(public_path($booking->label_file));
    }

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
    // public function downloadLabel(Request $request)
    // {
    //     // dd($request->all());
    //     $booking_id = $request->id;

    //     // Check if booking exists

    //     $booking = Booking::where('id', $booking_id)->first();

    //     if (!$booking) {
    //         return redirect()->back()->with('error', 'No data found');
    //     }

    //     // Check if label_file already exists
    //     if (!$booking->label_file) {
    //         // Get the required data for generating the PDF
    //         $users = User::get();  // Example data, modify based on your needs

    //         $data = [
    //             'title' => 'Shipping Label - ' . $booking->uuid,
    //             'date' => date('m/d/Y'),
    //             'booking' => $booking,
    //             'users' => $users  // Example data
    //         ];

    //         // Load the view for the PDF
    //         $pdf = FacadePdf::loadView('pdfs/shipping-label', $data);

    //         // Define the path to save the PDF
    //         $path = public_path('pdfs/labels');

    //         // Ensure the directory exists
    //         if (!File::exists($path)) {
    //             File::makeDirectory($path, 0755, true);
    //         }

    //         // Save the PDF to the specified path with the booking UUID as the filename
    //         $filePath = $path . '/' . $booking->uuid . '-label.pdf';
    //         $pdf->save($filePath);

    //         // Update the label_file field in the booking record
    //         $booking->update(['label_file' => ]);
    //     }

    //     // Return success response
    //     return response()->download($filePath);
    // }
}
