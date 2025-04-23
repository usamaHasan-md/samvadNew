<?php

namespace App\Http\Controllers;

use App\Mail\CampaignAssignedFieldAgentMail;
use App\Models\Asset;
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
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class VendorController extends Controller
{
    
    public function vendorLoginPage(){
        return view('auth.vendor-login');
    }

    public function vendorLogedin(Request $request){
            $request->validate([
                'contact' => 'required|digits:10',  // 👈 This ensures exactly 10 digits
                'password' => 'required',
            ]);
    
            // Check Vendor Login
            $vendor = VendorModel::where('contact', $request->contact)->first();
            if ($vendor && $vendor->status == 1 && auth('vendor')->attempt(['contact' => $request->contact, 'password' => $request->password])) {
                Log::info('Vendor logged in successfully:', ['number' => $request->number, 'ip' => $request->ip()]);
                return redirect()->route('vendor.dashboard')->with('successMessage', 'Vendor Login Successful');
            } elseif ($vendor && $vendor->status == 0) {
                Log::warning('Inactive Vendor attempted login', ['number' => $request->number, 'ip' => $request->ip()]);
                return redirect()->back()->with('dangerMessage', 'Your account is inactive. Please contact support.');
            }
            // If credentials are incorrect
            Log::warning('Failed login attempt:', ['number' => $request->number, 'ip' => $request->ip()]);
            return redirect()->back()->with('dangerMessage', 'Invalid credentials');
    }
    
    public function vendorlogout(Request $request)
    {
        foreach (['vendor'] as $guard) {
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

        return redirect()->route('vendor.login.page')->with('successMessage', 'You have Been Successfully Logged Out');
    }
    
    public function vendor_dashboard()
    {
        $today = now()->toDateString();
    
        // $totalVendors = VendorModel::count();
        $totalFieldAgents = FieldAgentModel::count();
        $totalCampaigns = CampaignModel::count();
        $totalPrevCamp = CampaignModel::whereDate('end_date', '<', $today)->count();
        $totalCurCamp = CampaignModel::whereDate('start_date', '<=', $today)->whereDate('end_date', '>=', $today)->count();
        $totalassignvendor = DB::table('campaign_vendor')->distinct()->pluck('vendor_id')->count();
        $totalverifiedimg = DB::table('imageuploadcamp')->distinct()->pluck('is_verified')->count();

    
        $campaigns = CampaignModel::whereDate('end_date', '<', $today)->get();
        $ongoingcampaigns = CampaignModel::with('vendors')->whereDate('start_date', '<=', $today)->whereDate('end_date', '>=', $today)->get();
        $fieldAgentName = FieldAgentModel::where('name')->get();

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
    
        return view('vendor.vendor-dashboard', compact(
            'fieldAgentName','totalFieldAgents', 'totalCampaigns', 'totalCurCamp', 'totalPrevCamp',
            'totalassignvendor', 'campaigns', 'ongoingcampaigns', 'vendors',
            'categoryWise', 'districtWise', 'lighthoarding', 'nonlighthoarding', 'lightunipole', 'nonlightunipole','totalverifiedimg'
        ));
    }
    
    public function page()
    {
        return view('vendor.field-agent');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
		    'added_by' => 'required',
            'name' => 'required|string|max:255',
            'number' => 'required|string|digits:10',
            'role' => 'required',
            'password' => 'nullable|digits:5',
            'confirmation_password'=>'nullable|digits:5,'
        ]);
        try {

            $data = [
			    'added_by' => $validatedData['added_by'],
                'name' => $validatedData['name'],
                'number' => $validatedData['number'],
                'role' => $validatedData['role'],
                'password' => Hash::make($validatedData['password']),
                'confirmation_password' => $validatedData['confirmation_password'],
            ];
            FieldAgentModel::create($data);
            return redirect()->route('fieldagent.list')->with('successMessage', 'Field Agent Registered Successfully');
        } catch (Exception $e) {
            Log::error('Database Insert Error: ' . $e->getMessage());
            return redirect()->back()->with('dangerMessage', 'Failed to register field agent! Please try again.');
        }
    }


    public function fielagentlist()
    {
        $fieldagentlists = FieldAgentModel::orderBy('id','desc')
        ->get();
        return view('vendor.field-agent-list', compact('fieldagentlists'));
    }

    public function campaignlist()
    {
    $user = auth()->guard('vendor')->user()->id;
    
    $compaigns = CampaignModel::whereHas('vendors', function ($query) use ($user) {
        $query->where('vendor_id', $user);
    })
    ->orderBy('id','desc')
    ->get();

    $fieldagents = FieldAgentModel::all();

    return view('vendor.campaign-list', compact('compaigns', 'fieldagents'));
    }

    public function campaignAssignVendorPage($id)
    {
        $user = auth()->guard('vendor')->user()->id;
    
        // Retrieve the specific campaign by its ID
        $campaign = CampaignModel::whereHas('vendors', function ($query) use ($user) {
            $query->where('vendor_id', $user);
        })->findOrFail($id);
    
        // Retrieve unique cities from FieldAgentModel
        $cities = FieldAgentModel::distinct()->pluck('city');
    
        // Pass the campaign and cities to the view
        return view('vendor.campaign-assign-to-fieldagent', compact('campaign', 'cities'));
    }
    
    
    public function campaignAssignVendor(Request $request, $id)
    {
        $request->validate([
            'city' => 'required|array',
            'city.*' => 'exists:fieldagent,city',
            'fieldagent_limit' => 'nullable|array',
        ]);
    
        try {
            $campaign = CampaignModel::findOrFail($id);
            $campaignFieldAgent = [];
            $alreadyAssignedfieldAgent = [];
            $allFieldAgents = [];
    
            foreach ($request->city as $cityName) {
                $limit = $request->fieldagent_limit[$cityName] ?? null;
    
                $fieldagentQuery = FieldAgentModel::where('city', $cityName)->inRandomOrder();
    
                if (!empty($limit) && is_numeric($limit) && $limit > 0) {
                    $fieldagentQuery->limit((int)$limit);
                }
    
                $fieldagents = $fieldagentQuery->get();
    
                foreach ($fieldagents as $fieldagent) {
                    $exists = DB::table('campaign_fieldagent')
                        ->where('campaign_id', $campaign->id)
                        ->where('fieldagent_id', $fieldagent->id)
                        ->exists();
    
                    if ($exists) {
                        $alreadyAssignedfieldAgent[] = $fieldagent->name . " (City: $cityName)";
                    } else {
                        $campaignFieldAgent[] = [
                            'campaign_id' => $campaign->id,
                            'fieldagent_id' => $fieldagent->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                        $allFieldAgents[] = $fieldagent;
                    }
                }
            }
    
            // Insert new records
            if (!empty($campaignFieldAgent)) {
                DB::table('campaign_fieldagent')->insert($campaignFieldAgent);
            }
    
            // Send mail
            $emails = collect($allFieldAgents)->pluck('email')->toArray();
            Log::info('Sending campaign mail to:', ['emails' => $emails]);
    
            foreach ($allFieldAgents as $agent) {
                if (!empty($agent->email)) {
                    Mail::to($agent->email)->send(new CampaignAssignedFieldAgentMail($agent, $campaign));
                }
            }
    
            return redirect()->back()->with('successMessage', 'Campaign assigned successfully.');
    
        } catch (\Exception $e) {
            Log::error('Error updating admin', [
                'error' => $e->getMessage(),
                'admin_id' => $id,
                'time' => now(),
            ]);
            return redirect()->back()->with('dangerMessage', 'Something went wrong! Unable to assign campaign.');
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

           if ($vendors->status == 1) {
                return redirect()->back()->with('successMessage', 'Status Activated Successfully.');
            } else {
                return redirect()->back()->with('dangerMessage', 'Status Deactivated Successfully.');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('dangerMessage', 'Something went wrong.');
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
            'number' => 'required|string|digits:10,'.$id,
            'password'=>'required|string|digits:5',
            'confirmation_password' => 'required|string|digits:5|same:password',
        ]);

        try {
            $fieldagentUpdate = FieldAgentModel::findOrFail($id);
            $oldData = $fieldagentUpdate->toArray(); // Pura purana data save karenge

            $updatedBy = auth('vendor')->check() ? auth('vendor')->user()->name : 'system';

            $fieldagentUpdate->update([
                'name' => $request->name,
                'number' => $request->number,
                'password'=>Hash::make($request->password),
                'confirmation_password'=>$request->confirmation_password,
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
    
    public function campaignShow()
    {
    try {
        // Fetch campaigns with assigned vendors
        $campaignFieldagents = DB::table('campaign_fieldagent')
            ->join('fieldagent', 'campaign_fieldagent.fieldagent_id', '=', 'fieldagent.id')
            ->join('campaign', 'campaign_fieldagent.campaign_id', '=', 'campaign.id')
            ->select(
                'campaign.id as campaign_id',
                'campaign.campaign_name as campaign_name',
                DB::raw('GROUP_CONCAT(fieldagent.name ORDER BY fieldagent.name SEPARATOR ", ") as fieldagent_names')
            )
            ->groupBy('campaign.id', 'campaign.campaign_name')
            ->get();

        // Return the view with data
        return view('vendor.show-all-campaign-list', [
            'campaignFieldagents' => $campaignFieldagents,
            'message' => $campaignFieldagents->isEmpty() ? 'No campaigns assigned yet.' : null
        ]);
    } catch (\Exception $e) {
        // Log the error
        Log::error('Error fetching campaign details', [
            'error' => $e->getMessage(),
            'time' => now(),
        ]);

        return view('vendor.show-all-campaign-list', [
            'campaignFieldagents' => [],
            'error' => 'Something went wrong. Please try again.'
        ]);
    }
    }


    public function FieldAgentCampaignView($campaign_id)
    {
        try {
            // Step 1: Get logged-in vendor ID
            $vendor_id = Auth::guard('vendor')->id();
    
            // Step 2: Fetch campaign details
            $campaigns = DB::table('campaign')
                ->where('id', $campaign_id)
                ->first();
    
            if (!$campaigns) {
                return redirect()->back()->with('dangerMessage', 'Campaign not found.');
            }
    
            // Step 3: Fetch all field agents assigned to this vendor for this campaign
            $fieldAgents = DB::table('campaign_fieldagent')
                ->join('fieldagent', 'campaign_fieldagent.fieldagent_id', '=', 'fieldagent.id')
                ->where('campaign_fieldagent.campaign_id', $campaign_id)
                ->where('fieldagent.added_by', $vendor_id) // 👈 Filter by logged-in vendor
                ->select('fieldagent.id', 'fieldagent.name', 'fieldagent.number')
                ->get();
    
            $fieldAgentIds = $fieldAgents->pluck('id')->toArray(); // get list of IDs
    
            // Step 4: Fetch images uploaded by those field agents for this campaign
            $imageUploads = DB::table('imageuploadcamp')
                ->leftJoin('fieldagent', 'imageuploadcamp.fieldagent_id', '=', 'fieldagent.id')
                ->where('imageuploadcamp.campaign_id', $campaign_id)
                ->whereIn('imageuploadcamp.fieldagent_id', $fieldAgentIds) // 👈 Only from this vendor's agents
                ->select(
                    'imageuploadcamp.*',
                    'fieldagent.name as agent_name'
                )
                ->orderBy('id','desc')
                ->get();
    
            return view('vendor.campaign-history', compact('campaigns', 'fieldAgents', 'imageUploads'));
        } catch (\Exception $e) {
            Log::error('Error fetching campaign details', [
                'dangerMessage' => $e->getMessage(),
                'time' => now()
            ]);
    
            return redirect()->back()->with('dangerMessage', 'Something went wrong. Please try again.');
        }
    }

	
	public function index()
    {
        $images = ImageUploadCampaignModel::with('fieldagent')
            ->where('is_verified', false)
            ->get();

        return view('vendor.image-verification', compact('images'));
    }

    public function verify($id)
    {
        $image = ImageUploadCampaignModel::findOrFail($id);
        $image->is_verified = 1;
        $image->vendor_remarks = 'Approved by vendor';
        $image->verified_by = Auth::guard('vendor')->id();
        $image->verified_at = Carbon::now('Asia/Kolkata');;
        $image->save();
    
        return redirect()->back()->with('success', 'Image approved successfully.');
    }
    
    public function reject(Request $request, $id)
    {
        $request->validate([
            'remark' => 'required|string|max:255',
        ]);
    
        $image = ImageUploadCampaignModel::findOrFail($id);
        $image->is_verified = 0;
        $image->vendor_remarks = $request->remark;
        $image->verified_by = Auth::guard('vendor')->id();
        $image->verified_at = Carbon::now('Asia/Kolkata');;
        $image->save();
    
        return redirect()->back()->with('error', 'Image rejected: ' . $request->remark);
    }

    public function ongoingCampaigns()
    {
    $vendorId = auth()->guard('vendor')->user()->id;
    $today = now()->toDateString();

    $campaigns = CampaignModel::whereHas('vendors', function ($query) use ($vendorId) {
        $query->where('vendor_id', $vendorId);
    })
    ->whereDate('start_date', '<=', $today)
    ->whereDate('end_date', '>=', $today)
    ->orderBy('id','desc')
    ->get();

    $fieldagents = FieldAgentModel::all();

    return view('vendor.ongoing-campaigns', compact('campaigns', 'fieldagents'));
    }

    public function previousCampaigns()
    {
    $vendorId = auth()->guard('vendor')->user()->id;
    $today = now()->toDateString();

    $campaigns = CampaignModel::whereHas('vendors', function ($query) use ($vendorId) {
        $query->where('vendor_id', $vendorId);
    })
    ->whereDate('end_date', '<', $today)
    ->orderBy('id','desc')
    ->get();

    $fieldagents = FieldAgentModel::all();

    return view('vendor.previous-campaigns', compact('campaigns', 'fieldagents'));
    }



}
