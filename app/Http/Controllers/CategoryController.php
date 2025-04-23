<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


use Carbon\Carbon;

class CategoryController extends Controller
{
    public function add_category()
    {
        $cat = Category::all();
        $subcat = SubCategory::all();
        $assets = Asset::with(['category', 'subCategory'])->get();
        return view('admin.add-category', compact('cat', 'subcat','assets'));
    }
	
	public function storecategory(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:255|unique:categories',
        ]);
        try {
            $formFields = [
                'category' => $request->category,
            ];
            Category::create($formFields);
            return redirect()->back()->with('successMessage', 'Category Added Successfully!');
        } catch (\Exception $e) {
            Log::error('Error adding category: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->with('dangerMessage', 'Failed to save data');
        }
    }

    public function storesubcategory(Request $request)
    {
        $validated = $request->validate([
		    'category_id' => 'required',
            'category' => 'required|string|max:255',
            'sub_category' => 'required|string|max:255|unique:sub_categories',
        ]);
        try {
            $formFields = [
			    'category_id' => $request->category_id,
                'category' => $request->category,
                'sub_category' => $request->sub_category,
            ];
            SubCategory::create($formFields);
            return redirect()->back()->with('successMessage', 'Sub Category Added Successfully!');
        } catch (\Exception $e) {
            Log::error('Error adding sub-category: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->with('dangerMessage', 'Failed to save data');
        }
    }

    
    public function storeassets(Request $request)
    {
        $validated = $request->validate([
                'hoarding_id' => 'required|unique:assets',
                'category' => 'required|integer',
                'sub_category' => 'string|max:255',
                'state' => 'required|string|max:255',
                'district' => 'required|string|max:255',
                'district_area' => 'required|string|max:255',
                'location_address' => 'required|string|max:255',
        ]);
        
        try {
            $formFields = [
                'hoarding_id' => $request->hoarding_id,
                'category' => $request->category,
                'sub_category' =>$request->sub_category,
                'state' => $request->state,
                'district' => $request->district,
                'district_area' => $request->district_area,
                'location_address' => $request->location_address,
            ];
            Asset::create($formFields);
            return redirect()->back()->with('successMessage', 'Sub Category Added Successfully!');
        } 
        catch (\Exception $e) {
            Log::error('Exception occurred while storing asset: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);
            return redirect()->back()->with('dangerMessage', 'Failed to save data');
        }
    }
    


}
