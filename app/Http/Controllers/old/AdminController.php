<?php

namespace App\Http\Controllers;

use App\Mail\CampaignAssignedVendorMail;
use App\Models\AdminModel;
use App\Models\CampaignModel;
use App\Models\FieldAgentModel;
use App\Models\ImageUploadCampaignModel;
use App\Models\VendorModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Exception;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

use function Laravel\Prompts\error;

class AdminController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function logedin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        Log::info('Login attempt:', ['email' => $request->email, 'ip' => $request->ip()]);

        // Check Admin Login
        if (auth('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
            Log::info('Admin logged in successfully:', ['email' => $request->email, 'ip' => $request->ip()]);
            return redirect()->route('admin.dashboard')->with('successMessage', 'Admin Login Successful');
        }

        // Check Vendor Login
        $vendor = VendorModel::where('email', $request->email)->first();
        if ($vendor && $vendor->status == 1 && auth('vendor')->attempt(['email' => $request->email, 'password' => $request->password])) {
            Log::info('Vendor logged in successfully:', ['email' => $request->email, 'ip' => $request->ip()]);
            return redirect()->route('vendor.dashboard')->with('successMessage', 'Vendor Login Successful');
        } elseif ($vendor && $vendor->status == 0) {
            Log::warning('Inactive Vendor attempted login', ['email' => $request->email, 'ip' => $request->ip()]);
            return redirect()->back()->with('dangerMessage', 'Your account is inactive. Please contact support.');
        }

        // Check Field Agent Login
        $fieldAgent = FieldAgentModel::where('email', $request->email)->first();
        if ($fieldAgent && $fieldAgent->status == 1 && auth('fieldagent')->attempt(['email' => $request->email, 'password' => $request->password])) {
            Log::info('Field Agent logged in successfully:', ['email' => $request->email, 'ip' => $request->ip()]);
            return redirect()->route('fieldagent.dashboard')->with('successMessage', 'Field Agent Login Successful');
        } elseif ($fieldAgent && $fieldAgent->status == 0) {
            Log::warning('Inactive Field Agent attempted login', ['email' => $request->email, 'ip' => $request->ip()]);
            return redirect()->back()->with('dangerMessage', 'Your account is inactive. Please contact support.');
        }

        // If credentials are incorrect
        Log::warning('Failed login attempt:', ['email' => $request->email, 'ip' => $request->ip()]);
        return redirect()->back()->with('dangerMessage', 'Invalid credentials');
    }


    public function admin_dashboard()
    {
        return view('admin.dashboard');
    }
    
    // ---------------------Add Vendor--------------------------
    
    public function add_vendor()
    {
        return view('admin.add-vendor');
    }
    
    public function store(Request $request)
    {
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:admin,email',
        'contact' => 'required|string|max:15',
        'role' => 'required',
        'password' => 'required|min:6|confirmed', // Ensure password matches confirmation
    ]);

    try {
        // Prepare Data for Insert
        $data = [
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'contact' => $validatedData['contact'],
            'role' => $validatedData['role'],
            'password' => Hash::make($validatedData['password']),
        ];

        // Insert into database
        VendorModel::create($data);

        return redirect()->route('list.vendor')->with('success', 'Vendor Registered Successfully');
    }
    catch (Exception $e) {
        // Logging database or unexpected errors
        Log::error('Database Insert Error: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Failed to register vendor! Please try again.');
    }
    }

    public function list()
    {
        $vendors = VendorModel::all();
        return view('admin.vendor-list', compact('vendors'));
    }


    public function toggleStatus($id)
    {
        try {
            $vendors = VendorModel::findOrFail($id);
            $vendors->status = $vendors->status == 1 ? 0 : 1; // Toggle Status
            $vendors->save();

            if ($vendors->status == 1) {
                return redirect()->back()->with('successMessage', 'Status Activated Successfully.');
            } else {
                return redirect()->back()->with('dangerMessage', 'Status Deactivated Successfully.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('dangerMessage', 'Something went wrong.');
        }
    }


    public function destroy($id)
    {
        try {
            $vendors = VendorModel::findOrFail($id);
            $vendors->delete();

            return redirect()->back()->with('successMessage', 'Vendor Deleted Successfully.');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->back()->with('dangerMessage', 'Vendor not found.');
        } catch (\Exception $e) {
            return redirect()->back()->with('dangerMessage', 'Something went wrong! Unable to delete vendor.');
        }
    }


    public function edit($id)
    {
        try {
            $vendor = VendorModel::findOrFail($id);
            return view('admin.vendor-edit', compact('vendor'));
        } catch (\Exception $e) {
            Log::error('Error fetching vendor for edit', [
                'error' => $e->getMessage(),
                'id' => $id
            ]);

            return redirect()->route('vendor.list')->with('error', 'Vendor not found.');
        }
    }


    public function update(Request $request, $id)
    {
        $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:vendor,email,' . $id,  // Table ka sahi name
                'contact' => 'required|string|max:15',
            ]);
        try {
            $vendors = VendorModel::findOrFail($id);
            $oldData = $vendors->toArray(); // Pura purana data save karenge
            $vendors->update([
                'name' => $request->name,
                'email' => $request->email,
                'contact' => $request->contact,
            ]);
            return redirect()->route('list.vendor')->with('successMessage', 'User updated successfully.');
        } catch (\Exception $e) {
            Log::error('Error updating admin', [
                'error' => $e->getMessage(),
                'admin_id' => $id,
                'time' => now(),
            ]);
            return redirect()->back()->with('dangerMessage', 'Something went wrong! Unable to update user.');
        }
    }


    public function logout(Request $request)
    {
        foreach (['admin', 'vendor', 'fieldagent'] as $guard) {
            if (Auth($guard)->check()) {
                Auth($guard)->logout();
                Log::info(ucfirst($guard) . ' logged out successfully:', [
                    'ip' => $request->ip(),
                ]);
                break;
            }
        }
        $request->session()->invalidate();           // Session destroy
        $request->session()->flush();                // All session data clear
        $request->session()->regenerateToken();      // CSRF token regenerate

        return redirect()->route('login.page')->with('successMessage', 'You have Been Successfully Logged Out');
    }


    // ----------------------------add campain pages---------------------------------
    public function addcampaign()
    {
        $addcampaign = FieldAgentModel::all();
        return view('admin.add-campaignadmin', compact('addcampaign'));
    }
    // ----------------------store data to campaign by admin-------------------------
    public function storecamapgin(Request $request)
    {
        $validatedData = $request->validate([
                'campaign_name' => 'required|string|max:255',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Multiple image validation
                'pdf' => 'nullable|mimes:pdf|max:5120', // PDF validation
                'description' => 'nullable|string|max:500', // Description should be a string
                'start_date' => 'required',
                'end_date' => 'required',
            ]);

        try {
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
                'campaign_name' => $validatedData['campaign_name'],
                'images' => json_encode($imagePaths), // Store multiple images as JSON
                'pdf' => $pdfPath, // Store PDF path
                'description' => $validatedData['description'],
                'start_date' => $validatedData['start_date'],
                'end_date' => $validatedData['end_date'],
            ]);
            return redirect()->route('list.campaignadmin')->with('successMessage', 'Campaign Created Successfully');
        } 
        catch (Exception $e) {
            Log::error('Database Insert Error: ' . $e->getMessage());
            return redirect()->back()->with('dangerMessage', 'Failed to creating campaign! Please try again.');
        }
    }

    // ---------------------------list campaign------------------------------

    public function listcampaign()
    {
        $listcampaigns = CampaignModel::all();
        $vendors = VendorModel::all();
        return view('admin.campaign-list', compact('listcampaigns', 'vendors'));
    }

    // ---------------------------Campaign Edit By Admin------------------------------
    public function campaignEditByAdmin($id)
    {
        $EditByAdmins = CampaignModel::findOrFail($id);
        return view('admin.campaign-edit', compact('EditByAdmins'));
    }


    // -------------------Campain Assign by admin To vendor---------------------------
    public function campaignAssignByAdmin(Request $request, $id)
{
    $request->validate([
        'vendor_name' => 'required|array',
        'vendor_name.*' => 'exists:vendor,id',
    ]);

    try {
        $campaign = CampaignModel::findOrFail($id);
        $campaignVendors = [];
        $alreadyAssignedVendors = [];

        foreach ($request->vendor_name as $vendorId) {
            // Check if the campaign is already assigned to the vendor
            $exists = DB::table('campaign_vendor')
                ->where('campaign_id', $campaign->id)
                ->where('vendor_id', $vendorId)
                ->exists();

            if ($exists) {
                // Fetch vendor name for message display
                $vendor = VendorModel::find($vendorId);
                if ($vendor) {
                    $alreadyAssignedVendors[] = $vendor->name;
                }
            } else {
                $campaignVendors[] = [
                    'campaign_id' => $campaign->id,
                    'vendor_id' => $vendorId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Insert only new vendor assignments
        if (!empty($campaignVendors)) {
            DB::table('campaign_vendor')->insert($campaignVendors);
        }

        // Prepare response messages
        if (!empty($alreadyAssignedVendors)) {
            return redirect()->back()->with('dangerMessage', 'In this ' . implode(', ', $alreadyAssignedVendors) . ' already assigned to this campaign.');
        }

        return redirect()->back()->with('successMessage', 'Campaign assigned successfully.');
    } catch (\Exception $e) {
        Log::error('Error assigning campaign to vendors', [
            'error' => $e->getMessage(),
            'campaign_id' => $id,
            'time' => now(),
        ]);
        return redirect()->back()->with('dangerMessage', 'Something went wrong! Unable to assign campaign.');
    }
}


    // ---------------Assign vendor Campaign details------------------
    // --------------------------vIew campaign----------------------------------
   public function campaignDetails()
   {
    try {
        // Fetch campaigns with assigned vendors
        $campaignVendors = DB::table('campaign_vendor')
            ->join('vendor', 'campaign_vendor.vendor_id', '=', 'vendor.id')
            ->join('campaign', 'campaign_vendor.campaign_id', '=', 'campaign.id')
            ->select(
                'campaign.id as campaign_id',
                'campaign.campaign_name as campaign_name',
                DB::raw('GROUP_CONCAT(vendor.name ORDER BY vendor.name SEPARATOR ", ") as vendor_names')
            )
            ->groupBy('campaign.id', 'campaign.campaign_name')
            ->get();

        // Return the view with data
        return view('admin.campaign-details', [
            'campaignVendors' => $campaignVendors,
            'message' => $campaignVendors->isEmpty() ? 'No campaigns assigned yet.' : null
        ]);
    } catch (\Exception $e) {
        // Log the error
        Log::error('Error fetching campaign details', [
            'error' => $e->getMessage(),
            'time' => now(),
        ]);

        return view('admin.campaign-details', [
            'campaignVendors' => [],
            'error' => 'Something went wrong. Please try again.'
        ]);
    }
    }


    public function VendorCampaignView($campaign_id)
    {
    try {
        // Step 1: Fetch campaign details
        $campaign = DB::table('campaign')
            ->where('id', $campaign_id)
            ->first();

        if (!$campaign) {
            return redirect()->back()->with('dangerMessage', 'Campaign not found.');
        }

        // Step 2: Fetch all vendors assigned to this campaign
        $vendors = DB::table('campaign_vendor')
            ->join('vendor', 'campaign_vendor.vendor_id', '=', 'vendor.id')
            ->where('campaign_vendor.campaign_id', $campaign_id)
            ->select('vendor.id', 'vendor.name', 'vendor.email')
            ->get();

        // Step 3: Fetch all field agents assigned under vendors for this campaign
        $fieldAgents = DB::table('campaign_fieldagent')
            ->join('fieldagent', 'campaign_fieldagent.fieldagent_id', '=', 'fieldagent.id')
            ->where('campaign_fieldagent.campaign_id', $campaign_id)
            ->select('fieldagent.id', 'fieldagent.name', 'fieldagent.email')
            ->get();

        // Step 4: Fetch images uploaded by field agents for this campaign
        $imageUploads = DB::table('imageuploadcamp')
            ->where('campaign_id', $campaign_id)
            ->get();
        return view('admin.view-vendor-campaign-details', compact('campaign', 'vendors', 'fieldAgents', 'imageUploads'));
    } catch (\Exception $e) {
        Log::error('Error fetching campaign details', ['dangerMessage' => $e->getMessage(), 'time' => now()]);

        return redirect()->back()->with('dangerMessage', 'Something went wrong. Please try again.');
    }
    }





    // --------------------campaign Update By ssAdmin------------------

    public function campaignUpdateByAdmin(Request $request, $id)
    {
        $validatedData = $request->validate([
                'campaign_name' => 'required|string|max:255',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'pdf' => 'nullable|mimes:pdf|max:2048',
                'description' => 'nullable|string|max:500',
                'start_date' => 'required|date', // or 'before:tomorrow' etc.
                'end_date' => 'required|date', // or 'before:tomorrow' etc.

            ]);
        try {

            $campaign = CampaignModel::findOrFail($id);

            // Delete old images if new ones uploaded
            if ($request->hasFile('images')) {
                $oldImages = json_decode($campaign->images, true) ?? [];
                foreach ($oldImages as $oldImage) {
                    $oldImagePath = public_path($oldImage);
                    if (file_exists($oldImagePath)) @unlink($oldImagePath);
                }

                $imagePaths = [];
                foreach ($request->file('images') as $image) {
                    $imageName = time() . '_' . $image->getClientOriginalName();
                    $image->move(public_path('uploads/images'), $imageName);
                    $imagePaths[] = 'uploads/images/' . $imageName;
                }
                $campaign->images = json_encode($imagePaths);
            }

            // Delete old PDF if new uploaded
            if ($request->hasFile('pdf')) {
                if (!empty($campaign->pdf)) {
                    $oldPdfPath = public_path($campaign->pdf);
                    if (file_exists($oldPdfPath)) @unlink($oldPdfPath);
                }

                $pdfFile = $request->file('pdf');
                $pdfName = time() . '_' . $pdfFile->getClientOriginalName();
                $pdfFile->move(public_path('uploads/pdf'), $pdfName);
                $campaign->pdf = 'uploads/pdf/' . $pdfName;
            }

            // Update fields
            $campaign->campaign_name = $validatedData['campaign_name'];
            $campaign->description = $validatedData['description'] ?? null;
            $campaign->start_date = $validatedData['start_date'];
            $campaign->end_date = $validatedData['end_date'];
            $campaign->save();
            return redirect()->route('list.campaignadmin')->with('successMessage', 'Campaign Updated Successfully');
        } 
        catch (\Exception $e) {
            Log::error('Database Update Error: ' . $e->getMessage());
            return redirect()->route('list.campaignadmin')->with('dangerMessage', 'Failed to update campaign! Please try again.');
        }
    }

    // ---------------------------Campaign Delete By Admin-------------------------------------

    public function campaignDeleteByAdmin($id)
    {
        $campaignDelete = CampaignModel::findOrFAil($id);
        $campaignDelete->delete();
        return redirect()->back()->with('success_delete', 'Campaign Successfully Deleted By Admin');
    }


    // ---------------------------image upload camp List-------------------------------------

    public function imageuploadcampList(Request $request)
    {
        $fieldagent_id = $request->input('fieldagent_id');

        // Query month filter ke sath
        $query = ImageUploadCampaignModel::with('fieldagent')
            ->whereMonth('date', now('Asia/Kolkata')->month);

        if ($fieldagent_id) {
            $query->where('fieldagent_id', $fieldagent_id);
        }

        // Group by fieldagent and get
        $imageuploadcamps = $query
            ->select('fieldagent_id', DB::raw('COUNT(*) as total_images'))
            ->groupBy('fieldagent_id')
            ->get();

        return view('admin.imageuploadcamp-list', compact('imageuploadcamps', 'fieldagent_id'));
    }



    // ---------------------------Download Monthly Image PDF-------------------------------------

    public function DownloadMonthlyImagePDF(Request $request)
    {
        $fieldagent_id = $request->input('fieldagent_id');
        // $id=ImageUploadCampaignModel::where('fieldagent_id')
        if (!$fieldagent_id) {
            return redirect()->back()->with('error', 'Field Agent ID is required.');
        }

        // Current month ke saari images fetch karein
        $imageuploadcamps = ImageUploadCampaignModel::where('fieldagent_id', $fieldagent_id)
            ->whereMonth('date', now()->month)
            ->get();

        if ($request->has('download_pdf')) {
            if ($imageuploadcamps->isEmpty()) {
                return redirect()->back()->with('error', 'No images available for this month.');
            }

            // PDF generate karne ke liye images ko pass karein
            $pdf = Pdf::loadView('admin.monthly-images-pdf', ['imageuploadcamps' => $imageuploadcamps]);

            return $pdf->download('Monthly_Images_' . now()->format('F_Y') . '.pdf');
        }

        return view('admin.imageuploadcamp-list', compact('imageuploadcamps'));
    }
}
