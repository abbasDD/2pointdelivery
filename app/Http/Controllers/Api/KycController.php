<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\KycDetailResource;
use App\Models\KycDetail;
use App\Models\KycType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class KycController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        // If token is not valid return error
        if (!Auth::user()) {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'Unauthorized.',
                'errors' => 'Unauthorized',
            ], 401);
        }

        // Get kyc details of logged in user
        $kycDetails = KycDetail::with('kycType')->where('user_id', Auth::user()->id)->get();

        // Return success
        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'KYC Details fetched successfully',
            'data' => KycDetailResource::collection($kycDetails),
        ], 200);
    }

    // kycTypes
    public function kycTypes(): JsonResponse
    {

        // If token is not valid return error
        if (!Auth::user()) {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'Unauthorized.',
                'errors' => 'Unauthorized',
            ], 401);
        }

        // Get kyc types // Get already added Kyc Types
        $existedKycTypes = KycDetail::where('user_id', Auth::user()->id)->pluck('kyc_type_id')->toArray();

        // Get KYC Types not present in the existedKycTypes array
        $kycTypes = KycType::whereNotIn('id', $existedKycTypes)->get();

        // Return success
        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'KYC Types fetched successfully',
            'data' => $kycTypes,
        ], 200);
    }

    // Add Kyc
    public function store(Request $request): JsonResponse
    {

        // If token is not valid return error
        if (!Auth::user()) {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'Unauthorized.',
                'errors' => 'Unauthorized',
            ], 401);
        }

        // Validate the request

        $validator = Validator::make($request->all(), [
            'kyc_type_id' => 'required|string|max:255',
            'id_number' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'issue_date' => 'required|string|max:255',
            'expiry_date' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if user exist
        $user = User::where('id', Auth::user()->id)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'Unauthorized.',
                'errors' => 'Unauthorized',
            ], 401);
        }

        // Check if kyc exist or not
        $kycDetail = KycDetail::where('user_id', Auth::user()->id)->where('kyc_type_id', $request->kyc_type_id)->first();

        if ($kycDetail) {
            // return redirect()->back()->with('error', 'You have already added this KYC');
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'You have already added this KYC',
                'errors' => 'You have already added this KYC',
            ], 422);
        }

        // Create new kyc
        $kycDetail = KycDetail::create([
            'user_id' => Auth::user()->id,
            'kyc_type_id' => $request->kyc_type_id,
        ]);

        // Check if api route has helper in url
        if (strpos($request->url(), 'helper') !== false) {
            $kycDetail->type = 'helper';
        }

        // Check if front_image exist or not
        if ($request->hasFile('front_image')) {
            $request->validate([
                'front_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,heic,heif',
            ]);
            // upload image
            $file = $request->file('front_image');
            $updatedFilename = time() . '_front' . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('images/kyc/');
            $file->move($destinationPath, $updatedFilename);

            // Set the profile image attribute to the new file name
            $front_image = $updatedFilename;
            $kycDetail->front_image = $updatedFilename;
        }

        // Check if back_image exist or not
        if ($request->hasFile('back_image')) {
            $request->validate([
                'back_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,heic,heif',
            ]);
            // upload image
            $back_image = $request->file('back_image');
            $updatedFilename = time() . '_back' . '.' . $back_image->getClientOriginalExtension();
            $destinationPath = public_path('images/kyc/');
            $back_image->move($destinationPath, $updatedFilename);

            // Set the profile image attribute to the new file name
            $back_image = $updatedFilename;
            $kycDetail->back_image = $updatedFilename;
        }

        // Update kyc
        $kycDetail->id_number = $request->id_number;
        $kycDetail->country = $request->country;
        $kycDetail->state = $request->state;
        $kycDetail->city = $request->city;
        $kycDetail->issue_date = date('Y-m-d', strtotime($request->issue_date));
        $kycDetail->expiry_date = date('Y-m-d', strtotime($request->expiry_date));

        $kycDetail->save();

        // Call notificaion helper to send notification
        app('notificationHelper')->sendNotification(Auth::user()->id, 1, 'admin', 'kyc_detail', $kycDetail->id, 'KYC details added', 'KYC is submitted by client for ' . $kycDetail->kycType->name . ' KYC');

        // Response
        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'KYC added successfully',
            'data' => [],
        ], 200);
    }

    // Show KYC
    public function show(Request $request): JsonResponse
    {
        // If token is not valid return error
        if (!Auth::user()) {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'Unauthorized.',
                'errors' => 'Unauthorized',
            ], 401);
        }

        // Get kyc details from id only if it is not yet accepted
        $kycDetail = KycDetail::with('kycType')->where('user_id', Auth::user()->id)->where('is_verified', '!=', 1)->where('id', $request->id)->first();

        if (!$kycDetail) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Unable to edit this KYC.',
                'errors' => 'Unable to edit this KYC',
            ], 422);
        }

        $kycDetailID = [
            'id' => $kycDetail->id,
            'kyc_type_id' => $kycDetail->kycType->id,
            'kyc_type_name' => $kycDetail->kycType->name,
            'id_number' => $kycDetail->id_number,
            'country' => $kycDetail->country,
            'state' => $kycDetail->state,
            'city' => $kycDetail->city,
            'issue_date' => $kycDetail->issue_date,
            'expiry_date' => $kycDetail->expiry_date,
            'front_image' => $kycDetail->front_image ? asset('/images/kyc/' . $kycDetail->front_image) : 'assets/images/default.png',
            'back_image' => $kycDetail->back_image ? asset('/images/kyc/' . $kycDetail->back_image) : 'assets/images/default.png',
            'is_verified' => $kycDetail->is_verified,
        ];

        // Response
        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'KYC details',
            'data' => $kycDetailID,
        ], 200);
    }

    // Add Kyc
    public function update(Request $request): JsonResponse
    {

        // If token is not valid return error
        if (!Auth::user()) {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'Unauthorized.',
                'errors' => 'Unauthorized',
            ], 401);
        }

        // Validate the request

        $validator = Validator::make($request->all(), [
            'id' => 'required|string|max:255',
            'id_number' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'issue_date' => 'required|string|max:255',
            'expiry_date' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if user exist
        $user = User::where('id', Auth::user()->id)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'Unauthorized.',
                'errors' => 'Unauthorized',
            ], 401);
        }

        // Check if kyc is already submitted to admin
        $kycDetail = KycDetail::where('id', $request->id)->where('user_id', Auth::user()->id)->first();

        if (!$kycDetail) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Unable to find KYC',
                'errors' => 'Unable to find KYC',
            ], 422);
        }

        if ($kycDetail->is_verified == 1) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'KYC is already approved',
                'errors' => 'KYC is already approved',
            ], 422);
        }

        // Check if front_image exist or not
        if ($request->hasFile('front_image')) {
            $request->validate([
                'front_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,heic,heif',
            ]);
            // upload image
            $file = $request->file('front_image');
            $updatedFilename = time() . '_front' . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('images/kyc/');
            $file->move($destinationPath, $updatedFilename);

            // Set the profile image attribute to the new file name
            $front_image = $updatedFilename;
            $kycDetail->front_image = $updatedFilename;
        }

        // Check if back_image exist or not
        if ($request->hasFile('back_image')) {
            $request->validate([
                'back_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,heic,heif',
            ]);
            // upload image
            $back_image = $request->file('back_image');
            $updatedFilename = time() . '_back' . '.' . $back_image->getClientOriginalExtension();
            $destinationPath = public_path('images/kyc/');
            $back_image->move($destinationPath, $updatedFilename);

            // Set the profile image attribute to the new file name
            $back_image = $updatedFilename;
            $kycDetail->back_image = $updatedFilename;
        }

        // Update kyc details
        $kycDetail->id_number = $request->id_number;
        $kycDetail->country = $request->country;
        $kycDetail->state = $request->state;
        $kycDetail->city = $request->city;
        $kycDetail->issue_date = date('Y-m-d', strtotime($request->issue_date));
        $kycDetail->expiry_date = date('Y-m-d', strtotime($request->expiry_date));
        $kycDetail->is_verified = 0;

        $kycDetail->save();

        // Response
        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'KYC data updated successfully',
            'data' => [],
        ], 200);
    }
}
