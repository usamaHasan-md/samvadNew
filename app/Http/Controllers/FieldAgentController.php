<?php

namespace App\Http\Controllers;

use App\Models\ImageUploadCampaignModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\FieldAgentModel;

class FieldAgentController extends Controller
{
    public function fieldagentLoginPage(){
        return view('auth.fieldagent-login');
    }

    public function fieldagentLogedin(Request $request){

        $request->validate([
            'number' => 'required|digits:10',  // 👈 This ensures exactly 10 digits
            'password' => 'required',
        ]);


        // Check Field Agent Login
        $fieldAgent = FieldAgentModel::where('number', $request->number)->first();
        if ($fieldAgent && $fieldAgent->status == 1 && auth('fieldagent')->attempt(['number' => $request->number, 'password' => $request->password])) {
            Log::info('Field Agent logged in successfully:', ['number' => $request->number, 'ip' => $request->ip()]);
            return redirect()->route('fieldagent.dashboard')->with('successMessage', 'Field Agent Login Successful');
        } elseif ($fieldAgent && $fieldAgent->status == 0) {
            Log::warning('Inactive Field Agent attempted login', ['number' => $request->number, 'ip' => $request->ip()]);
            return redirect()->back()->with('dangerMessage', 'Your account is inactive. Please contact support.');
        }

        // If credentials are incorrect
        Log::warning('Failed login attempt:', ['number' => $request->number, 'ip' => $request->ip()]);
        return redirect()->back()->with('dangerMessage', 'Invalid credentials');
    }
    
    public function fieldagentlogout(Request $request)
    {
        foreach (['fieldagent'] as $guard) {
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

        return redirect()->route('fieldagent.login.page')->with('successMessage', 'You have Been Successfully Logged Out');
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

}
