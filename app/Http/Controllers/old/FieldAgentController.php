<?php

namespace App\Http\Controllers;

use App\Models\ImageUploadCampaignModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FieldAgentController extends Controller
{
    

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
