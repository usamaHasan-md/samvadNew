<?php

namespace App\Http\Controllers;

use App\Models\CampaignModel;
use App\Models\FieldAgentModel;
use App\Models\VendorModel;
use App\Models\Asset;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ImageUploadCampaignModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CampaignController extends Controller
{
    public function fieldagent_dashboard()
    {
        return view('fieldagent.field-agent-dashboard');
    }

    public function page(Request $request)
    {
        // $admin = AdminManagementModel::where('email', $request->email)->first();

        $fieldagentlists = FieldAgentModel::all();
        return view('fieldagent.campaign-list', compact('fieldagentlists'));
    }

    public function campaignpage(Request $request)
    {
        // $admin = AdminManagementModel::where('email', $request->email)->first();

        $campaignpages = FieldAgentModel::all();
        return view('fieldagent.campaign', compact('campaignpages'));
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'agent_name' => 'required|string|max:255',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Multiple image validation
                'pdf' => 'nullable|mimes:pdf|max:2048', // PDF validation
                'description' => 'nullable|string|max:500', // Description should be a string
            ]);

            // $compaigns=CompaignModel::create($validatedData);

            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $imageName = time() . '_' . $image->getClientOriginalName();
                    $imagePath = 'uploads/images/' . $imageName;
                    $image->move(public_path('uploads/images'), $imageName);
                    $imagePaths[] = $imagePath; // Save paths in an array
                }
            }

            $pdfPath = null;
            if ($request->hasFile('pdf')) {
                $pdfFile = $request->file('pdf');
                $pdfName = time() . '_' . $pdfFile->getClientOriginalName();
                $pdfPath = 'uploads/pdf/' . $pdfName;
                $pdfFile->move(public_path('uploads/pdf'), $pdfName);
            }
            // Insert Data into Database
            CampaignModel::create([
                'agent_name' => $validatedData['agent_name'],
                'images' => json_encode($imagePaths), // Store multiple images as JSON
                'pdf' => $pdfPath, // Store PDF path
                'description' => $validatedData['description'],
            ]);

            Log::info('Admin Registered Successfully', [
                'agent_name' => $validatedData['agent_name'],
                'images' => $validatedData['images'],
                'pdf' => $validatedData['pdf'],
                'description' => $validatedData['description'],
            ]);
            return redirect()->back()->with('success', 'Campaign Registered Successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation Error:', $e->errors());
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (Exception $e) {
            Log::error('Database Insert Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to register campaign! Please try again.');
        }
    }

    public function campaignlistToFieldagent()
    {
        $user = auth()->guard('fieldagent')->user()->id;

        $campaignlistToFieldagents = CampaignModel::whereHas('fieldAgents', function ($query) use ($user) {
            $query->where('fieldagent_id', $user);
        })->with(['fieldAgents' => function ($query) use ($user) {
            $query->where('fieldagent_id', $user);
        }])->get();

        return view('fieldagent.campaign-list', compact('campaignlistToFieldagents'));
    }



    public function editpage($id)
    {
        try {
            $campaign = CampaignModel::find($id);
            if (!$campaign) {
                return redirect()->route('compaign.list')->with('error', 'compaign not foound');
            }
            // JSON ko array me convert karna Multiple images ko fetch karke delete karne ke liye json me data ko decode karna padega 
            $campaign->images = json_decode($campaign->images, true);
            Log::info('Compaign Edit Page Open to update field agent list', ['id' => $campaign->id, 'agent_name' => $campaign->agent_name, 'images' => $campaign->images, 'pdf' => $campaign->pdf, 'description' => $campaign->description]);
            return view('vendor.compaign-edit', compact('compaign'));
        } catch (\Exception $e) {
            Log::error("error fetching admin for edit", ['error' => $e->getMessage(), 'id' => $id]);
            return redirect()->route('compaign.list')->with('error', 'User not Found');
        }
    }


    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'agent_name' => 'required|string|max:255',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Multiple image validation
                'pdf' => 'nullable|mimes:pdf|max:2048', // PDF validation
                'description' => 'nullable|string|max:500', // Description should be a string
            ]);

            // Fetch the existing campaign
            $campaign = CampaignModel::findOrFail($id);

            // **Delete old images if new images are uploaded**
            if ($request->hasFile('images')) {
                if (!empty($campaign->images)) {
                    foreach (json_decode($campaign->images) as $oldImage) {
                        $oldImagePath = public_path('uploads/images/' . $oldImage);
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath); // Delete old image file
                        }
                    }
                }

                // **Upload new images**
                $imagePaths = [];
                foreach ($request->file('images') as $image) {
                    $imageName = time() . '_' . $image->getClientOriginalName();
                    $image->move(public_path('uploads/images'), $imageName);
                    $imagePaths[] = $imageName; // Save only image names
                }
                $campaign->images = json_encode($imagePaths);
            }

            // **Delete old PDF if new PDF is uploaded**
            if ($request->hasFile('pdf')) {
                if (!empty($campaign->pdf)) {
                    $oldPdfPath = public_path('uploads/pdf/' . $campaign->pdf);
                    if (file_exists($oldPdfPath)) {
                        unlink($oldPdfPath); // Delete old PDF file
                    }
                }

                // **Upload new PDF**
                $pdfFile = $request->file('pdf');
                $pdfName = time() . '_' . $pdfFile->getClientOriginalName();
                $pdfFile->storeAs('public/uploads/pdf', $pdfName);
                $campaign->pdf = $pdfName;
            }

            // **Update other fields**
            $campaign->agent_name = $validatedData['agent_name'];
            $campaign->description = $validatedData['description'];
            $campaign->save();

            Log::info('Campaign Updated Successfully', [
                'agent_name' => $validatedData['agent_name'],
                'images' => $campaign->images,
                'pdf' => $campaign->pdf,
                'description' => $validatedData['description'],
            ]);

            return redirect()->back()->with('success', 'Campaign Updated Successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation Error:', $e->errors());
            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Database Update Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update campaign! Please try again.');
        }
    }

    public function delete($id)
    {
        $campaignDelete = CampaignModel::find($id);
        if (!$campaignDelete) {
            return redirect()->back()->with('error', 'Compaign not found');
        }
        $campaignDelete->delete();
        return redirect()->back()->with('sucess', 'Compaign Deleted Successfully.');
    }


    public function uploadImage(Request $request)
    {
        try {
            $fieldagent_id = Auth::guard('fieldagent')->id();

            $folderPath = public_path('uploads/');

            if (!file_exists($folderPath)) {
                mkdir($folderPath, 0777, true);
            }

            $image = $request->image;

            if (!$image) {
                throw new \Exception("No image data received.");
            }

            $imageParts = explode(";base64,", $image);
            if (count($imageParts) < 2) {
                throw new \Exception("Invalid image format.");
            }

            $imageBase64 = base64_decode($imageParts[1]);

            if (!$imageBase64) {
                throw new \Exception("Base64 decoding failed.");
            }

            $fileName = uniqid() . '.png';
            $file = $folderPath . $fileName;
            file_put_contents($file, $imageBase64);

            // Save to database
            $photo = new ImageUploadCampaignModel();
            $photo->campaign_id = $request->campaign_id;
            $photo->fieldagent_id = $fieldagent_id;
            $photo->hoarding_id = $request->hoarding_id; // ✅ hoarding_id added
            $photo->image = 'uploads/' . $fileName;
            $photo->longtitude = $request->longtitude;
            $photo->latitude = $request->latitude;
            $photo->date = Carbon::now('Asia/Kolkata')->format('Y-m-d H:i:s'); // ✅ full datetime

            $photo->save();

            return response()->json([
                'success' => 'Image saved successfully.',
                'image' => $photo->image,
                'longtitude' => $photo->longtitude,
                'latitude' => $photo->latitude,
                'date' => $photo->date,
                'fieldagent_id' => $photo->fieldagent_id,
                'campaign_id' => $photo->campaign_id,
                'hoarding_id' => $photo->hoarding_id
            ]);
        } catch (\Exception $e) {
            Log::error("Image Upload Error: " . $e->getMessage());

            return response()->json([
                'error' => 'Image upload failed.',
                'message' => $e->getMessage()
            ], 500);
        }
    }



    public function addCampaignVendor()
    {
        $addCampaignVendors = FieldAgentModel::all();
        return view('fieldagent.campaign', compact('addCampaignVendors'));
    }

    public function showFilterForm(Request $request)
    {
        $campaigns = CampaignModel::all();
        $assetsQuery = Asset::query();

        // Apply filters
        if ($request->has('search')) {
            $filters = $request->input('filter');
            foreach ($filters as $key => $value) {
                if (!empty($value)) {
                    if ($key == 'hoarding_id' && is_array($value)) {
                        $assetsQuery->whereIn($key, $value);
                    } else {
                        $assetsQuery->where($key, 'like', "%$value%");
                    }
                }
            }
        }
        $assets = $assetsQuery->with(['categoryData', 'subCategoryData'])->get();

        // Get distinct category and sub_category IDs from assets
        $categoryIds = Asset::distinct()->pluck('category');
        $subCategoryIds = Asset::distinct()->pluck('sub_category');

        // Fetch category and sub_category names using the IDs
        $categories = Category::whereIn('id', $categoryIds)->get();
        $subCategories = SubCategory::whereIn('id', $subCategoryIds)->get();
        $hoardingIds = Asset::distinct()->pluck('hoarding_id');

        return view('admin.campaignassignfilter', compact(
            'campaigns',
            'assets',
            'hoardingIds',
            'categories',
            'subCategories',
        ));
    }


public function assignCampaignFinal(Request $request)
{
    $request->validate([
        'campaign_id' => 'required|exists:campaign,id',
        'hoarding_ids' => 'required|array',
        'hoarding_ids.*' => 'exists:assets,hoarding_id',
    ]);

    $campaignId = $request->campaign_id;
    $hoardingIds = $request->hoarding_ids;
    $assignedRecords = [];
    $alreadyAssigned = [];

    // Get current campaign details
    $currentCampaign = DB::table('campaign')->where('id', $campaignId)->first();

    foreach ($hoardingIds as $hoardingId) {
        // Check conflict in campaign_vendor
        $vendorConflict = DB::table('campaign_vendor')
            ->join('campaign', 'campaign.id', '=', 'campaign_vendor.campaign_id')
            ->where('campaign_vendor.hoarding_id', $hoardingId)
            ->where('campaign_vendor.campaign_id', '!=', $campaignId)
            ->whereDate('campaign.end_date', '>=', now())
            ->exists();

        // Check conflict in campaign_fieldagent
        $fieldAgentConflict = DB::table('campaign_fieldagent')
            ->join('campaign', 'campaign.id', '=', 'campaign_fieldagent.campaign_id')
            ->where('campaign_fieldagent.hoarding_id', $hoardingId)
            ->where('campaign_fieldagent.campaign_id', '!=', $campaignId)
            ->whereDate('campaign.end_date', '>=', now())
            ->exists();

        if ($vendorConflict || $fieldAgentConflict) {
            $alreadyAssigned[] = "Hoarding ID {$hoardingId} is already assigned in another active campaign.";
            continue;
        }

        // Assign to vendor and their field agents
        $vendors = VendorModel::whereJsonContains('assigned_hoarding_id', $hoardingId)->get();

        foreach ($vendors as $vendor) {
            $exists = DB::table('campaign_vendor')
                ->where('campaign_id', $campaignId)
                ->where('vendor_id', $vendor->id)
                ->where('hoarding_id', $hoardingId)
                ->exists();

            if ($exists) {
                $alreadyAssigned[] = "Vendor ID {$vendor->id} already has this hoarding for this campaign.";
                continue;
            }

            $assignedRecords[] = [
                'campaign_id' => $campaignId,
                'vendor_id' => $vendor->id,
                'hoarding_id' => $hoardingId,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Field Agent Assignment
            $fieldAgents = FieldAgentModel::where('added_by', $vendor->id)->get();

            foreach ($fieldAgents as $agent) {
                $fieldExists = DB::table('campaign_fieldagent')
                    ->where('campaign_id', $campaignId)
                    ->where('fieldagent_id', $agent->id)
                    ->where('hoarding_id', $hoardingId)
                    ->exists();

                if (!$fieldExists) {
                    DB::table('campaign_fieldagent')->insert([
                        'campaign_id' => $campaignId,
                        'fieldagent_id' => $agent->id,
                        'hoarding_id' => $hoardingId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

    if (!empty($assignedRecords)) {
        DB::table('campaign_vendor')->insert($assignedRecords);
    }

    if (!empty($alreadyAssigned)) {
        return redirect()->back()->with('dangerMessage', implode($alreadyAssigned));
    }

    return redirect()->back()->with('successMessage', 'Campaign assigned successfully.');
}



    public function ajaxFilter(Request $request)
    {
        $query = Asset::query();

        if ($request->campaign_id) {
            // optional use of campaign_id
        }

        if ($hoardingIds = $request->input('filter.hoarding_id')) {
            $query->whereIn('hoarding_id', $hoardingIds);
        }

        if ($request->input('filter.category')) {
            $query->where('category', $request->input('filter.category'));
        }

        if ($request->input('filter.sub_category')) {
            $query->where('sub_category', $request->input('filter.sub_category'));
        }

        if ($request->input('filter.state')) {
            $query->where('state', $request->input('filter.state'));
        }

        if ($request->input('filter.district')) {
            $query->where('district', $request->input('filter.district'));
        }

        if ($request->input('filter.district_area')) {
            $query->where('district_area', 'like', '%' . $request->input('filter.district_area') . '%');
        }

        if ($request->input('filter.location_address')) {
            $query->where('location_address', 'like', '%' . $request->input('filter.location_address') . '%');
        }

        $assets = $query->with(['categoryData', 'subCategoryData'])->get();

        return view('partials.hoarding-table', compact('assets'))->render();
    }
}
