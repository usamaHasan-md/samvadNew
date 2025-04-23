<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\CampaignModel;
use App\Models\ImageUploadCampaignModel;
use App\Models\Asset;

class ReportController extends Controller
{


    public function campaignWiseReport(Request $request)
    {
        // Main Query
        $reportQuery = DB::table('campaign')
            ->join('campaign_vendor', 'campaign.id', '=', 'campaign_vendor.campaign_id')
            ->join('assets', 'campaign_vendor.hoarding_id', '=', 'assets.hoarding_id')
            ->leftJoin('imageuploadcamp', function ($join) {
                $join->on('campaign_vendor.campaign_id', '=', 'imageuploadcamp.campaign_id')
                     ->on('campaign_vendor.hoarding_id', '=', 'imageuploadcamp.hoarding_id');
            })
            ->leftJoin('categories', 'assets.category', '=', 'categories.id')
            ->leftJoin('sub_categories', 'assets.sub_category', '=', 'sub_categories.id')
            ->select(
                'campaign.campaign_name',
                'campaign.start_date',
                'campaign.end_date',
                'assets.hoarding_id',
                'categories.category as category',
                'sub_categories.sub_category as sub_category',
                'assets.state',
                'assets.district',
                'assets.district_area',
                'assets.location_address',
                'imageuploadcamp.image',
                'imageuploadcamp.date',
                'imageuploadcamp.latitude',
                'imageuploadcamp.longtitude'
            );
    
        // Filters (if any)
        if ($request->has('filter')) {
            $filters = $request->input('filter');
    
            if (!empty($filters['campaign_id'])) {
                $reportQuery->whereIn('campaign.id', $filters['campaign_id']);
            }
    
            if (!empty($filters['hoarding_id'])) {
                $reportQuery->whereIn('assets.hoarding_id', $filters['hoarding_id']);
            }
    
            if (!empty($filters['category'])) {
                $reportQuery->whereIn('assets.category', (array) $filters['category']);
            }
    
            if (!empty($filters['sub_category'])) {
                $reportQuery->whereIn('assets.sub_category', (array) $filters['sub_category']);
            }
    
            if (!empty($filters['state'])) {
                $reportQuery->where('assets.state', $filters['state']);
            }
    
            // if (!empty($filters['district'])) {
            //     $reportQuery->where('assets.district', $filters['district']);
            // }
    
            $filters = $request->input('filter', []);
    
            $district = $filters['district'] ?? '';
            $district_area = $filters['district_area'] ?? '';
            $location_address = $filters['location_address'] ?? '';
            
            // Make sure none of them are arrays (safety check)
            $district = is_array($district) ? ($district[0] ?? '') : $district;
            $district_area = is_array($district_area) ? ($district_area[0] ?? '') : $district_area;
            $location_address = is_array($location_address) ? ($location_address[0] ?? '') : $location_address;
            
            if (!empty($district)) {
                $reportQuery->where('assets.district', $district);
            }
            
            if (!empty($district_area)) {
                $reportQuery->where('assets.district_area', 'like', '%' . $district_area . '%');
            }
            
            if (!empty($location_address)) {
                $reportQuery->where('assets.location_address', 'like', '%' . $location_address . '%');
            }
    
        }
    
        $reportData = $reportQuery->orderBy('campaign.campaign_name', 'asc')->get();
    
        $filteredData = collect($reportData)->groupBy('hoarding_id')->map(function ($items) {
            $first = $items->first();
            return (object)[
                'hoarding_id' => $first->hoarding_id,
                'campaign_name' => $first->campaign_name,
                'category' => $first->category,
                'sub_category' => $first->sub_category,
                'start_date' => $first->start_date,
                'end_date' => $first->end_date,
                'state' => $first->state,
                'district' => $first->district,
                'district_area' => $first->district_area,
                'location_address' => $first->location_address,
                'images' => $items->filter(fn($item) => $item->image)->map(function ($img) {
                    return [
                        'image' => $img->image,
                        'date' => $img->date,
                        'latitude' => $img->latitude,
                        'longtitude' => $img->longtitude,
                    ];
                }),
            ];
        })->values();
    
        // Dropdowns (only once, not in AJAX)
        if (!$request->ajax()) {
            $categoryIds = Asset::distinct()->pluck('category');
            $subCategoryIds = Asset::distinct()->pluck('sub_category');
            $campaigns = CampaignModel::all();
            $categories = Category::whereIn('id', $categoryIds)->get();
            $subCategories = SubCategory::whereIn('id', $subCategoryIds)->get();
            $hoardingIds = Asset::distinct()->pluck('hoarding_id');
    
            return view('reports.campaign-wise', compact(
                'filteredData', 'campaigns', 'hoardingIds', 'categories', 'subCategories'
            ));
        }
    
        // For AJAX filter
        $html = view('partials.table_body', compact('filteredData'))->render();
        return response()->json(['html' => $html]);
    }
    













public function fieldagentReport(Request $request)
{
    // Main Query
    $reportQuery = DB::table('campaign')
        ->join('campaign_fieldagent', 'campaign.id', '=', 'campaign_fieldagent.campaign_id')
        ->join('fieldagent', 'campaign_fieldagent.fieldagent_id', '=', 'fieldagent.id')
        ->join('assets', 'campaign_fieldagent.hoarding_id', '=', 'assets.hoarding_id')
        ->leftJoin('imageuploadcamp', function ($join) {
            $join->on('campaign_fieldagent.campaign_id', '=', 'imageuploadcamp.campaign_id')
                 ->on('campaign_fieldagent.hoarding_id', '=', 'imageuploadcamp.hoarding_id');
        })
        ->leftJoin('categories', 'assets.category', '=', 'categories.id')
        ->leftJoin('sub_categories', 'assets.sub_category', '=', 'sub_categories.id')
        ->select(
            'fieldagent.name',
            'campaign.campaign_name',
            'campaign.start_date',
            'campaign.end_date',
            'assets.hoarding_id',
            'categories.category as category',
            'sub_categories.sub_category as sub_category',
            'assets.state',
            'assets.district',
            'assets.district_area',
            'assets.location_address',
            'imageuploadcamp.image',
            'imageuploadcamp.date',
            'imageuploadcamp.latitude',
            'imageuploadcamp.longtitude'
        );

    // Filters (if any)
    if ($request->has('filter')) {
        $filters = $request->input('filter');

        if (!empty($filters['campaign_id'])) {
            $reportQuery->whereIn('campaign.id', $filters['campaign_id']);
        }

        if (!empty($filters['hoarding_id'])) {
            $reportQuery->whereIn('assets.hoarding_id', $filters['hoarding_id']);
        }

        if (!empty($filters['category'])) {
            $reportQuery->whereIn('assets.category', (array) $filters['category']);
        }

        if (!empty($filters['sub_category'])) {
            $reportQuery->whereIn('assets.sub_category', (array) $filters['sub_category']);
        }

        if (!empty($filters['state'])) {
            $reportQuery->where('assets.state', $filters['state']);
        }

        if (!empty($filters['district'])) {
            $reportQuery->where('assets.district', $filters['district']);
        }

        if (!empty($filters['district_area'])) {
            $reportQuery->where('assets.district_area', 'like', '%' . $filters['district_area'] . '%');
        }

        if (!empty($filters['location_address'])) {
            $reportQuery->where('assets.location_address', 'like', '%' . $filters['location_address'] . '%');
        }
    }

    $reportData = $reportQuery->orderBy('campaign.campaign_name', 'asc')->get();

    $fieldaAgentFilteredData = collect($reportData)->groupBy('hoarding_id')->map(function ($items) {
        $first = $items->first();
        return (object)[
            'hoarding_id' => $first->hoarding_id,
            'name'=>$first->name,
            'campaign_name' => $first->campaign_name,
            'category' => $first->category,
            'sub_category' => $first->sub_category,
            'start_date' => $first->start_date,
            'end_date' => $first->end_date,
            'state' => $first->state,
            'district' => $first->district,
            'district_area' => $first->district_area,
            'location_address' => $first->location_address,
            'images' => $items->filter(fn($item) => $item->image)->map(function ($img) {
                return [
                    'image' => $img->image,
                    'date' => $img->date,
                    'latitude' => $img->latitude,
                    'longtitude' => $img->longtitude,
                ];
            }),
        ];
    })->values();

    // Dropdowns (only once, not in AJAX)
    if (!$request->ajax()) {
        $categoryIds = Asset::distinct()->pluck('category');
        $subCategoryIds = Asset::distinct()->pluck('sub_category');
        $campaigns = CampaignModel::all();
        $categories = Category::whereIn('id', $categoryIds)->get();
        $subCategories = SubCategory::whereIn('id', $subCategoryIds)->get();
        $hoardingIds = Asset::distinct()->pluck('hoarding_id');

        return view('reports.fieldagent-report', compact(
            'fieldaAgentFilteredData', 'campaigns', 'hoardingIds', 'categories', 'subCategories'
        ));
    }

    // For AJAX filter
    $html = view('partials.fieldagent_table_body', compact('filteredData'))->render();
    return response()->json(['html' => $html]);
}


}
