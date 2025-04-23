<?php

namespace App\Http\Controllers;

use App\Mail\CampaignAssignedFieldAgentMail;
use App\Models\CampaignModel;
use App\Models\FieldAgentModel;
use App\Models\ImageUploadCampaignModel;
use App\Models\VendorModel;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class VendorController extends Controller
{
    public function vendor_dashboard()
    {
        return view('vendor.vendor-dashboard');
    }
    public function page()
    {
        return view('vendor.field-agent');
    }

    public function store(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:fieldagent,email',
            'contact' => 'required|string|max:15',
            'role' => 'required',
            'password' => 'nullable|min:6|confirmed',
        ]);
        try {

            $data = [
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'contact' => $validatedData['contact'],
                'role' => $validatedData['role'],
                'password' => Hash::make($validatedData['password']),
            ];

            // Insert into database
            FieldAgentModel::create($data);
            return redirect()->route('fieldagent.list')->with('successMessage', 'Field Agent Registered Successfully');
        } catch (Exception $e) {
            // Logging database or unexpected errors
            Log::error('Database Insert Error: ' . $e->getMessage());
            return redirect()->back()->with('dangerMessage', 'Failed to register field agent! Please try again.');
        }
    }

    public function fielagentlist()
    {
        $fieldagentlists = FieldAgentModel::all();
        return view('vendor.field-agent-list', compact('fieldagentlists'));
    }


    public function addcampaignvendor()
    {
        $addcampaign = FieldAgentModel::all();
        return view('vendor.add-campaign', compact('addcampaign'));
    }

    public function storecampaignvendor(Request $request)
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

    public function campaignlist()
    {
    $user = auth()->guard('vendor')->user()->id;
    
    $compaigns = CampaignModel::whereHas('vendors', function ($query) use ($user) {
        $query->where('vendor_id', $user);
    })->get();

    $fieldagents = FieldAgentModel::all();

    return view('vendor.campaign-list', compact('compaigns', 'fieldagents'));
    }

    
    public function campaignAssignVendor(Request $request, $id)
    {
        $request->validate([
            'fieldagent_name' => 'required|array',
            'fieldagent_name.*' => 'exists:fieldagent,id',
        ]);
        try {
            $campaign = CampaignModel::findOrFail($id);

            $campaignFieldagent = [];
            foreach ($request->fieldagent_name as $fieldagentId) {
                $campaignFieldagent[] = [
                    'campaign_id' => $campaign->id,
                    'fieldagent_id' => $fieldagentId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            DB::table('campaign_fieldagent')->insert($campaignFieldagent);
            $fieldagents = FieldAgentModel::whereIn('id', $request->fieldagent_name)->get();
            $emails = $fieldagents->pluck('email')->toArray();
            Log::info('Sending campaign mail to:', ['emails' => $emails]);

            foreach ($fieldagents as $agent) {
                if (!empty($agent->email)) {
                    Mail::to($agent->email)->send(new CampaignAssignedFieldAgentMail($agent, $campaign));
                }
            }
            return redirect()->back()->with('successMessage', 'Campaign Assign successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating admin', [
                'error' => $e->getMessage(),
                'admin_id' => $id,
                'time' => now(),
            ]);
            return redirect()->back()->with('dangerMessage', 'Something went wrong! Unable to Assign campaign.');
        }
    }


    public function uploadImage(Request $request)
    {
        try {
            // Debugging: Log Authenticated User ID
            Log::info('Authenticated Field Agent ID: ' . Auth::guard('fieldagent')->id());

            $request->validate([
                // 'fieldagent_id' => 'required|integer|exists:fieldagent,id',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'latitude' => 'required|numeric',
                'longtitude' => 'required|numeric',
            ]);

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('uploads/images'), $imageName);
                $imagePath = 'uploads/images/' . $imageName;
            } else {
                throw new \Exception("Image file not found.");
            }

            $upload = ImageUploadCampaignModel::create([
                'fieldagent_id' => Auth::guard('fieldagent')->user()->id,
                'image_path' => $imagePath,
                'latitude' => $request->latitude,
                'longtitude' => $request->longtitude,
            ]);

            return redirect()->route('fieldagent.list');
        } catch (\Exception $e) {
            Log::error("Image upload failed: " . $e->getMessage());

            return response()->json([
                'message' => 'Something went wrong!',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    // ----------------Add Campaign bY Admin----------------------


    // ______________________ ToggleStatus to field Agent  _________________

    public function fielagentToggle($id)
    {
        try {
            $vendors = FieldAgentModel::findOrFail($id);
            $vendors->status = $vendors->status == 1 ? 0 : 1; // Toggle Status
            $vendors->save();

            return redirect()->back()->with('success', 'Status updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Something went wrong.');
        }
    }

    public function fieldagentDestroy($id)
    {
        try {
            $fieldagentDestroy = FieldAgentModel::findOrFail($id);
            $fieldagentDestroy->delete();
            return redirect()->back()->with('successMessage', 'Field Agent Deleted Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('dangerMessage', 'Something went wrong! Unable to delete user');
        }
    }


    public function fieldagentEdit($id)
    {
        $fieldagentEdit = FieldAgentModel::findOrFail($id);
        return view('vendor.field-agent-edit', compact('fieldagentEdit'));
    }
    public function fieldagentUpdate(Request $request, $id)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:fieldagent,email,' . $id,
            'contact' => 'required|string|max:15',
        ]);

        try {
            $fieldagentUpdate = FieldAgentModel::findOrFail($id);
            $oldData = $fieldagentUpdate->toArray(); // Pura purana data save karenge

            $updatedBy = auth('vendor')->check() ? auth('vendor')->user()->name : 'system';

            $fieldagentUpdate->update([
                'name' => $request->name,
                'email' => $request->email,
                'contact' => $request->contact,
                'updated_by' => $updatedBy,

            ]);

            return redirect()->route('fieldagent.list')->with('successMessage', 'Field Agent Successfully Updated');
        } catch (\Exception $e) {
            Log::error('Error updating Vendor', [
                'error' => $e->getMessage(),
                'id' => $id,
                'time' => now(),
            ]);
            return redirect()->back()->with('dangerMessage', 'Something went wrong! Unable to update Field Agent.');
        }
    }
}
