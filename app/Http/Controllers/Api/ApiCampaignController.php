<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\CampaignModel;
use App\Models\ImageUploadCampaignModel;
use Carbon\Carbon;


class ApiCampaignController extends Controller
{

    public function campaignListForFieldAgent($id)
    {
        try {
            $campaigns = CampaignModel::whereHas('fieldAgents', function ($query) use ($id) {
                $query->where('fieldagent_id', $id);
            })
            ->with(['fieldAgents' => function ($query) use ($id) {
                $query->where('fieldagent_id', $id)
                      ->select('fieldagent_id')
                      ->withPivot('hoarding_id'); // fetch pivot hoarding_id
            }])
            ->orderBy('id','desc')
            ->get();
    
            return response()->json([
                'status' => true,
                'message' => 'Campaigns fetched successfully',
                'data' => $campaigns
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Failed to fetch campaigns',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    
    public function uploadImageFromApp(Request $request)
    {
        try {
            // Validate the incoming request
            $request->validate([
                'campaign_id' => 'required|integer',
                'fieldagent_id' => 'required|integer',
                'hoarding_id' => 'required',
                'image' => 'required|string',  // The base64 encoded image
                'latitude' => 'required|numeric',
                'longtitude' => 'required|numeric',  // Corrected spelling to match the column name
            ]);
    
            // Get the authenticated field agent's ID
            // $fieldagent_id = Auth::guard('fieldagent')->id();
    
            // Set the folder path for storing uploaded images
            $folderPath = public_path('uploads/');
            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0777, true);
            }
    
            // Handle the base64 encoded image
            $imageParts = explode(";base64,", $request->image);
            if (count($imageParts) !== 2) {
                return response()->json([
                    'status' => false,
                    'error' => 'Invalid image format.'
                ], 400);
            }
    
            // Decode the image and save it to the server
            $imageBase64 = base64_decode($imageParts[1]);
            $fileName = uniqid() . '.png';
            $file = $folderPath . $fileName;
            file_put_contents($file, $imageBase64);
    
            // Create a new record in the database
            $photo = new ImageUploadCampaignModel();
            $photo->campaign_id = $request->campaign_id;
            $photo->hoarding_id = $request->hoarding_id;
            $photo->fieldagent_id = $request->fieldagent_id;
            $photo->image = 'uploads/' . $fileName;
            $photo->latitude = $request->latitude;
            $photo->longtitude = $request->longtitude;  // Make sure column name matches 'longtitude'
            $photo->date = Carbon::now('Asia/Kolkata');
            $photo->save();
    
            // Return a successful response
            return response()->json([
                'status' => true,
                'message' => 'Image uploaded successfully.',
                'data' => [
                    'image_url' => asset($photo->image),
                    'date' => $photo->date,
                    'latitude' => $photo->latitude,
                    'longtitude' => $photo->longtitude,  // Added longtitude to response
                    'campaign_id' => $photo->campaign_id,
                    'fieldagent_id' => $photo->fieldagent_id,
                    'hoarding_id' => $photo->hoarding_id,
                ]
            ]);
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('API Image Upload Error: ' . $e->getMessage());
    
            return response()->json([
                'status' => false,
                'error' => 'Something went wrong while uploading the image.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    

    public function apiFieldAgentCampaignView(Request $request, $campaign_id)
    {
        try {
            // Step 1: Fetch campaign details
            $campaign = DB::table('campaign')
                ->where('id', $campaign_id)
                ->first();
    
            if (!$campaign) {
                return response()->json([
                    'status' => false,
                    'message' => 'Campaign not found.'
                ], 404);
            }
    
            // Step 2: Get all field agents assigned to this campaign
            $fieldAgents = DB::table('campaign_fieldagent')
                ->join('fieldagent', 'campaign_fieldagent.fieldagent_id', '=', 'fieldagent.id')
                ->where('campaign_fieldagent.campaign_id', $campaign_id)
                ->select('fieldagent.id', 'fieldagent.name', 'fieldagent.number')
                ->get();
    
            $fieldAgentIds = $fieldAgents->pluck('id')->toArray();
    
            // Step 3: Get all image uploads by these field agents for this campaign
            if (empty($fieldAgentIds)) {
                $imageUploads = collect(); // Empty collection
            } else {
                $imageUploads = DB::table('imageuploadcamp')
                    ->leftJoin('fieldagent', 'imageuploadcamp.fieldagent_id', '=', 'fieldagent.id')
                    ->where('imageuploadcamp.campaign_id', $campaign_id)
                    ->whereIn('imageuploadcamp.fieldagent_id', $fieldAgentIds)
                    ->select(
                        'imageuploadcamp.id',
                        'imageuploadcamp.image',
                        'imageuploadcamp.latitude', // Make sure column name is correct
                        'imageuploadcamp.longtitude', // Make sure column name is correct
                        'imageuploadcamp.date',
                        'fieldagent.name as agent_name'
                    )
                    ->get();
    
            }
    
            // Step 4: Return JSON response
            return response()->json([
                'status' => true,
                'message' => 'Campaign data fetched successfully.',
                'data' => [
                    'campaign' => $campaign,
                    'image_uploads' => $imageUploads
                ]
            ]);
    
        } catch (\Exception $e) {
            Log::error('API Error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong. Please try again.'
            ], 500);
        }
    }
    
    public function apiVendorViewImages(Request $request)
    {
        try {
            Log::info('apiVendorViewImages: Fetching rejected images with remarks');
    
            $images = DB::table('imageuploadcamp')
                ->leftJoin('fieldagent', 'imageuploadcamp.fieldagent_id', '=', 'fieldagent.id')
                ->leftJoin('vendor', 'imageuploadcamp.verified_by', '=', 'vendor.id')
                ->where('imageuploadcamp.is_verified', 0)
                ->whereNotNull('imageuploadcamp.vendor_remarks')
                ->select(
                    'imageuploadcamp.id',
                    'imageuploadcamp.image',
                    'imageuploadcamp.is_verified',
                    'imageuploadcamp.vendor_remarks',
                    'vendor.name AS verified_by',
                    'imageuploadcamp.verified_at',
                    DB::raw("'Rejected' AS verification_status")
                )
                ->get();
    
            Log::info('apiVendorViewImages: Data fetched successfully', ['count' => $images->count()]);
    
            return response()->json([
                'status' => true,
                'message' => 'Rejected images with remarks fetched successfully.',
                'data' => $images
            ]);
    
        } catch (\Exception $e) {
            Log::error('Image Fetch API Error: ' . $e->getMessage());
    
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong. Please try again.'
            ], 500);
        }
    }


}

