<?php

namespace App\Http\Controllers;

use App\Mail\CampaignAssignedVendorMail;
use App\Models\AdminModel;
use App\Models\CampaignModel;
use App\Models\FieldAgentModel;
use App\Models\ImageUploadCampaignModel;
use App\Models\VendorModel;
use App\Models\Category;
use App\Models\Asset;
use App\Models\SubCategory;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPDFPDF;
use Exception;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;


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
        // Check Admin Login
        if (auth('admin')->attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('admin.dashboard')->with('successMessage', 'Admin Login Successful');
        }

        // If credentials are incorrect
        Log::warning('Failed login attempt:', ['email' => $request->email, 'ip' => $request->ip()]);
        return redirect()->back()->with('dangerMessage', 'Invalid credentials');
    }


    public function admin_dashboard()
{
    $today = now()->toDateString();

    $totalVendors = VendorModel::count();
    $totalFieldAgents = FieldAgentModel::count();
    $totalCampaigns = CampaignModel::count();
    $totalPrevCamp = CampaignModel::whereDate('end_date', '<', $today)->count();
    $totalCurCamp = CampaignModel::whereDate('start_date', '<=', $today)->whereDate('end_date', '>=', $today)->count();
    $totalassignvendor = DB::table('campaign_vendor')->distinct()->pluck('vendor_id')->count();

    $campaigns = CampaignModel::whereDate('end_date', '<', $today)->get();
    $ongoingcampaigns = CampaignModel::with('vendors')->whereDate('start_date', '<=', $today)->whereDate('end_date', '>=', $today)->get();

    $categoryWise = DB::table('assets')
        ->join('categories', 'assets.category', '=', 'categories.id')
        ->join('sub_categories', 'assets.sub_category', '=', 'sub_categories.id')
        ->select(
            'categories.id as category_id',
            'categories.category',
            'sub_categories.id as sub_category_id',
            'sub_categories.sub_category',
            DB::raw('COUNT(DISTINCT campaign.id) as total')
        )
        ->leftJoin('campaign_vendor', 'assets.hoarding_id', '=', 'campaign_vendor.hoarding_id')
        ->leftJoin('campaign', 'campaign_vendor.campaign_id', '=', 'campaign.id')
        ->groupBy('categories.id', 'categories.category', 'sub_categories.id', 'sub_categories.sub_category')
        ->get()
        ->groupBy('category');

    $districtWise = DB::table('assets')
        ->select(
            'assets.district',
            DB::raw('COUNT(DISTINCT campaign.id) as total')
        )
        ->leftJoin('campaign_vendor', 'assets.hoarding_id', '=', 'campaign_vendor.hoarding_id')
        ->leftJoin('campaign', 'campaign_vendor.campaign_id', '=', 'campaign.id')
        ->groupBy('assets.district')
        ->get();

    $lighthoarding = Asset::where('sub_category', 1)->count();
    $nonlighthoarding = Asset::where('sub_category', 2)->count();
    $lightunipole = Asset::where('sub_category', 3)->count();
    $nonlightunipole = Asset::where('sub_category', 4)->count();

    $vendors = VendorModel::with(['campaigns' => function($query) use ($today) {
        $query->orderBy('start_date', 'desc');
    }])->get();

    return view('admin.dashboard', compact(
        'totalVendors', 'totalFieldAgents', 'totalCampaigns', 'totalCurCamp', 'totalPrevCamp',
        'totalassignvendor', 'campaigns', 'ongoingcampaigns', 'vendors',
        'categoryWise', 'districtWise', 'lighthoarding', 'nonlighthoarding', 'lightunipole', 'nonlightunipole'
    ));
}

    // ---------------------Add Vendor--------------------------
	
	public function getSubcategories(Request $request)
    {
    $categoryIds = $request->category_ids;

    $subcategories = SubCategory::whereIn('category_id', $categoryIds)->get();

    return response()->json($subcategories);
    }
    
    
    
   public function add_vendor()
    {
        $hoarding_ids = Asset::distinct()->pluck('hoarding_id');
        return view('admin.add-vendor', compact('hoarding_ids'));
    }


    public function getCategoriesByHoarding(Request $request)
    {
        $hoardings = $request->hoardings;
    
        $results = DB::table('assets')
            ->join('categories', 'assets.category', '=', 'categories.id') // Assumes 'category' in assets points to the 'id' in categories table
            ->join('sub_categories', 'assets.sub_category', '=', 'sub_categories.id') // Same for sub_categories
            ->whereIn('assets.hoarding_id', $hoardings)
            ->select('categories.category as category_name', 'sub_categories.sub_category as subcategory_name', 'categories.id as category_id', 'sub_categories.id as sub_category_id')
            ->get();
    
        $categories = [];
        $subcategories = [];
    
        foreach ($results as $item) {
            $categories[] = ['id' => $item->category_id, 'name' => $item->category_name];
            $subcategories[] = ['id' => $item->sub_category_id, 'name' => $item->subcategory_name];
        }
    
        return response()->json([
            'categories' => $categories,
            'subcategories' => $subcategories
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:vendor,email',
            'contact' => 'required|digits:10',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'role' => 'required',
            'password' => ['required', 'regex:/^\d{5}$/', 'confirmed'],
    
            'assigned_hoarding_id' => 'nullable|array',
            'assigned_hoarding_id.*' => 'string',
    
            'category' => 'required|array|min:1',
            'category.*' => 'string',
    
            'sub_category' => 'nullable|array',
            'sub_category.*' => 'string',
    
            'contact_person_name' => 'nullable|array',
            'contact_person_number' => 'nullable|array',
            'contact_person_name.*' => 'nullable|string|max:255',
            'contact_person_number.*' => 'nullable|digits:10',
        ]);
    
        try {
            $hoardingIds = $request->input('assigned_hoarding_id');
            $categories = $request->input('category');
            $subcategories = $request->input('sub_category');
    
            $contactPersons = [];
            if ($request->has('contact_person_name') && $request->has('contact_person_number')) {
                foreach ($request->contact_person_name as $index => $name) {
                    if (!empty($name) && !empty($request->contact_person_number[$index])) {
                        $contactPersons[] = [
                            'name' => $name,
                            'number' => $request->contact_person_number[$index]
                        ];
                    }
                }
            }
    
            $data = [
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'contact' => $validatedData['contact'],
                'state' => $validatedData['state'],
                'city' => $validatedData['city'],
                'role' => $validatedData['role'],
                'password' => Hash::make($validatedData['password']),
                'plain_password' => $validatedData['password'],
                'assigned_hoarding_id' => json_encode($hoardingIds),
                'category' => json_encode($categories),
                'sub_category' => json_encode($subcategories),
                'contact_persons' => json_encode($contactPersons),
            ];
    
            VendorModel::create($data);
    
            return redirect()->route('list.vendor')->with('success', 'Vendor Registered Successfully');
        } catch (\Exception $e) {
            // Log complete request and validation data for debugging
            Log::error('Vendor Store Error', [
                'error_message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'input_data' => $request->all(),
                'validated_data' => $validatedData ?? null,
            ]);
    
            return redirect()->back()->with('error', 'Failed to register vendor! Please try again.');
        }
    }
        public function list()
    {
        $vendors = VendorModel::orderBy('id','desc')->get();
        return view('admin.vendor-list', compact('vendors'));
    }
    
    public function vendor_details($id)
    {
        try {
            $vendor = VendorModel::findOrFail($id);
            return view('admin.vendor-details', compact('vendor'));
        } catch (\Exception $e) {
            Log::error('Error fetching vendor', [
                'error' => $e->getMessage(),
                'id' => $id
            ]);
            return redirect()->route('list.vendor')->with('error', 'Vendor Details not found.');
        }
    }


    public function toggleStatus($id)
    {
        try {
            $vendor = VendorModel::findOrFail($id);
            $vendor->status = $vendor->status == 1 ? 0 : 1; // Toggle Vendor Status
            $vendor->save();
            
            FieldAgentModel::where('added_by', $vendor->id)->update([
            'status' => $vendor->status
            ]);
    
            if ($vendor->status == 1) {
                return redirect()->back()->with('successMessage', 'Status Activated Successfully.');
            } else {
                return redirect()->back()->with('dangerMessage', 'Status Deactivated Successfully.');
            }
        } catch (\Exception $e) {
            Log::error('Error toggling vendor status', [
                'error' => $e->getMessage(),
                'vendor_id' => $id,
            ]);
            return redirect()->back()->with('dangerMessage', 'Something went wrong.');
        }
    }



    public function destroy($id)
    {
        try {
            $vendor = VendorModel::findOrFail($id);
    
            // Delete field agents created by this vendor
            FieldAgentModel::where('added_by', $vendor->id)->delete();
    
            // Delete the vendor
            $vendor->delete();
    
            return redirect()->back()->with('successMessage', 'Vendor Deleted Successfully.');
        } 
        catch (\Exception $e) {
            Log::error('Error deleting vendor', [
                'error' => $e->getMessage(),
                'vendor_id' => $id,
            ]);
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
                'city' => 'required|string|max:255',
            ]);
        try {
            $vendors = VendorModel::findOrFail($id);
            $oldData = $vendors->toArray(); // Pura purana data save karenge
            $vendors->update([
                'name' => $request->name,
                'email' => $request->email,
                'contact' => $request->contact,
                'city' => $request->city,
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


    public function createVendorThroughCsv()
    {
        return view('admin.create-vendor-through-csv');

    }
    // GET: Show sample CSV download
    public function csvSampleDownload()
    {
        $filename = "vendor-sample.csv";
        $columns = ['name', 'email', 'contact', 'city', 'password']; // headers
    
        $callback = function () use ($columns) {
            $file = fopen('php://output', 'w');
    
            // Header row
            fputcsv($file, $columns);
    
            // Optional: sample data row
            fputcsv($file, ['John Doe', 'john@example.com', '9876543210', 'Raipur', 'password123']);
    
            fclose($file);
        };
    
        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
        ]);
    }
    
// POST: Handle CSV Upload

    public function vendorCsvImportant(Request $request)
    {
        // Validate file
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt',
        ]);
    
        try {
            // Get uploaded file
            $file = $request->file('csv_file');
            $handle = fopen($file->getRealPath(), 'r');
            $header = fgetcsv($handle); // First row (column names)
    
            Log::info('CSV Import Started', ['header' => $header]);
    
            $rowCount = 0;
            $skippedRows = [];
    
            // Read each row
            while (($row = fgetcsv($handle)) !== false) {
                $data = array_combine($header, $row);
                $rowCount++;
    
                if (!isset($data['name'], $data['email'], $data['contact'], $data['city'], $data['password'])) {
                    $skippedRows[] = ['reason' => 'Missing fields', 'data' => $data];
                    continue;
                }
                $data['role'] = $data['role'] ?? 'vendor';
    
    
                // Validate data
                $validator = Validator::make($data, [
                    'name' => 'required|string|max:255',
                    'email' => 'required|email|unique:vendor,email',
                    'contact' => 'required|string|max:15',
                    'city' => 'required|string|max:100',
                    'role'=>'required',
                    'password' => 'required|string|min:6',
                ]);
    
                if ($validator->fails()) {
                    $skippedRows[] = ['reason' => 'Validation failed', 'data' => $data, 'errors' => $validator->errors()];
                    continue;
                }
    
                // Create vendor
                VendorModel::create([
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'contact' => $data['contact'],
                    'city' => $data['city'],
                    'role'=>$data['role'],
                    'password' => Hash::make($data['password']),
                ]);
            }
    
            fclose($handle);
    
            Log::info('CSV Import Completed', [
                'total_rows' => $rowCount,
                'skipped' => count($skippedRows),
                'skipped_details' => $skippedRows,
            ]);
    
            return redirect()->back()->with('success', 'Vendors imported successfully!');
        } catch (\Exception $e) {
            Log::error('CSV Import Error', ['message' => $e->getMessage()]);
            return redirect()->back()->with('error', 'An error occurred while importing vendors.');
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
        $categories=Category::all();
        return view('admin.add-campaignadmin', compact('addcampaign','categories'));
    }

    // ----------------------store data to campaign by admin-------------------------
  // Make sure this is on top

public function storecampaign(Request $request)
{
    $validatedData = $request->validate([
        'campaign_name' => 'required|string|max:255',
        'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        'pdf.*' => 'nullable|max:5120',
        'description' => 'nullable|string|max:500',
        'category' => 'required|array|min:1',
        'category.*' => 'integer|exists:categories,id',
        'sub_category' => 'nullable|array',
        'sub_category.*' => 'integer|exists:sub_categories,id',
        'start_date' => 'required',
        'end_date' => 'required',
    ]);

    try {

        $categories = $request->input('category');
        $subcategories = $request->input('sub_category');

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $imagePath = 'uploads/images/' . $imageName;
                $image->move(public_path('uploads/images'), $imageName);
                $imagePaths[] = $imagePath;
            }
        }

        $pdfPaths = [];
        if ($request->hasFile('pdf')) {
            $pdfFiles = $request->file('pdf');
            foreach ($pdfFiles as $pdfFile) {
                $pdfName = time() . '_' . $pdfFile->getClientOriginalName();
                $pdfPath = 'uploads/pdf/' . $pdfName;
                $pdfFile->move(public_path('uploads/pdf'), $pdfName);
                $pdfPaths[] = $pdfPath;
            }
        }
        
        $campaign = CampaignModel::create([
            'campaign_name' => $validatedData['campaign_name'],
            'images' => json_encode($imagePaths),
            'pdf' => $pdfPath,
            'description' => $validatedData['description'] ?? '',
            'category' => json_encode($categories),
            'sub_category' => json_encode($subcategories),
            'start_date' => $validatedData['start_date'],
            'end_date' => $validatedData['end_date'],
        ]);

        Log::info('Campaign created successfully', ['id' => $campaign->id]);

        return redirect()->route('list.campaignadmin')->with('successMessage', 'Campaign Created Successfully');
    } catch (\Exception $e) {
        Log::error('Database Insert Error: ' . $e->getMessage());
        Log::error('Request data on failure', $request->all());

        return redirect()->back()->with('dangerMessage', 'Failed to create campaign! Please try again.');
    }
}

    // ---------------------------list campaign------------------------------

    public function listcampaign()
{
    $listcampaigns = CampaignModel::orderBy('id','desc')->get();
    $vendors = VendorModel::all();

    foreach ($listcampaigns as $campaign) {
        // Decode category
        $categoryIds = json_decode($campaign->category, true);
        $categoryIds = is_array($categoryIds) ? $categoryIds : [];

        // Decode sub_categories (handle JSON or CSV fallback)
        $subcategoryIds = json_decode($campaign->sub_categories, true);
        if (!is_array($subcategoryIds)) {
            $subcategoryIds = explode(',', $campaign->sub_categories); // fallback if CSV format
        }

        // Fetch names
        $categoryNames = Category::whereIn('id', $categoryIds)->pluck('category')->toArray();
        $subCategoryNames = SubCategory::whereIn('id', $subcategoryIds)->pluck('sub_category')->toArray();

        // Assign to object
        $campaign->category_names = $categoryNames;
        $campaign->subcategory_names = $subCategoryNames;
    }

    return view('admin.campaign-list', compact('listcampaigns', 'vendors'));
}

    // ---------------------------Campaign Edit By Admin------------------------------
    public function campaignEditByAdmin($id)
    {
        $EditByAdmins = CampaignModel::findOrFail($id);
        return view('admin.campaign-edit', compact('EditByAdmins'));
    }


    // -------------------Campain Assign by admin To vendor---------------------------
  // Go to assign page
public function showAssignPage($id)
{
    $campaign = CampaignModel::findOrFail($id);
    $cities = VendorModel::distinct()->pluck('city'); // unique cities

    return view('admin.campaign-assign-to-vendor', compact('campaign', 'cities'));
}




public function campaignAssignByAdmin(Request $request, $id)
{
    $request->validate([
        'city' => 'required|array',
        'city.*' => 'string',
        'vendor_limits' => 'nullable|array',
    ]);

    try {
        $campaign = CampaignModel::findOrFail($id);
        $campaignVendors = [];
        $alreadyAssignedVendors = [];
        $newlyAssignedVendorIds = [];

        foreach ($request->city as $cityName) {
            $limit = $request->vendor_limits[$cityName] ?? null;

            $vendorsQuery = VendorModel::where('city', $cityName)->inRandomOrder();

            if (!empty($limit) && is_numeric($limit) && $limit > 0) {
                $vendorsQuery->limit((int)$limit);
            }

            $vendors = $vendorsQuery->get();

            foreach ($vendors as $vendor) {
                $exists = DB::table('campaign_vendor')
                    ->where('campaign_id', $campaign->id)
                    ->where('vendor_id', $vendor->id)
                    ->exists();

                if ($exists) {
                    $alreadyAssignedVendors[] = $vendor->name . " (City: $cityName)";
                } else {
                    $campaignVendors[] = [
                        'campaign_id' => $campaign->id,
                        'vendor_id' => $vendor->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                    $newlyAssignedVendorIds[] = $vendor->id;
                }
            }
        }

        // Step 1: Insert newly assigned vendors
        if (!empty($campaignVendors)) {
            DB::table('campaign_vendor')->insert($campaignVendors);
        }

        // Step 2: Automatically assign field agents created by the newly assigned vendors
        if (!empty($newlyAssignedVendorIds)) {
            $fieldAgents = FieldAgentModel::whereIn('added_by', $newlyAssignedVendorIds)->get();
            $campaignFieldAgents = [];

            foreach ($fieldAgents as $agent) {
                $alreadyAssigned = DB::table('campaign_fieldagent')
                    ->where('campaign_id', $campaign->id)
                    ->where('fieldagent_id', $agent->id)
                    ->exists();

                if (!$alreadyAssigned) {
                    $campaignFieldAgents[] = [
                        'campaign_id' => $campaign->id,
                        'fieldagent_id' => $agent->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            if (!empty($campaignFieldAgents)) {
                DB::table('campaign_fieldagent')->insert($campaignFieldAgents);
            }
        }

        if (!empty($alreadyAssignedVendors)) {
            return redirect()->back()->with('dangerMessage', 'Already assigned: ' . implode(', ', $alreadyAssignedVendors));
        }

        return redirect()->back()->with('successMessage', 'Campaign assigned successfully to vendors and their field agents.');

    } catch (\Exception $e) {
        Log::error('Error assigning campaign to vendors and field agents', [
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
            ->select('fieldagent.id', 'fieldagent.name', 'fieldagent.number')
            ->get();

        // Step 4: Fetch images uploaded by field agents for this campaign
        $imageUploads = DB::table('imageuploadcamp')
        ->where('campaign_id', $campaign_id)
        ->where('is_verified',1)
        ->get();

            
        return view('admin.viewCampaignDetails', compact('campaign', 'vendors', 'fieldAgents', 'imageUploads'));
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
    
    
    public function adminongoingCampaigns()
    {
        try {
            $today = now()->toDateString();
    
            $campaigns = CampaignModel::with('vendors')
                ->whereDate('start_date', '<=', $today)
                ->whereDate('end_date', '>=', $today)
                ->orderBy('id','desc')
                ->get();
    
            return view('admin.ongoing-campaign', [
                'campaigns' => $campaigns,
                'message' => $campaigns->isEmpty() ? 'No ongoing campaigns.' : null
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching ongoing campaigns', [
                'error' => $e->getMessage(),
                'time' => now(),
            ]);
    
            return view('admin.ongoing-campaign', [
                'campaigns' => [],
                'error' => 'Something went wrong. Please try again.'
            ]);
        }
    }

    
   public function adminpreviousCampaigns()
   {
       try {
           $today = now()->toDateString();
   
           $campaigns = CampaignModel::with('vendors')
               ->whereDate('end_date', '<', $today)
               ->orderBy('id','desc')
               ->get();
   
           return view('admin.previous-campaign', [
               'campaigns' => $campaigns,
               'message' => $campaigns->isEmpty() ? 'No previous campaigns.' : null
           ]);
       } catch (\Exception $e) {
           Log::error('Error fetching previous campaigns', [
               'error' => $e->getMessage(),
               'time' => now(),
           ]);
   
           return view('admin.previous-campaign', [
               'campaigns' => [],
               'error' => 'Something went wrong. Please try again.'
           ]);
       }
   }


     public function report(){
        return view('admin.Report');
     }
     

}
