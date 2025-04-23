<?php

use App\Http\Controllers\ReportController;    
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\FieldAgentController;
use App\Http\Controllers\WebcamController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\NoCacheMiddleware;




Route::get('/web-test', function () {
    return 'Web Route is working!';
});




Route::get('/',[AdminController::class,'login'])->name('login.page');
Route::post('login',[AdminController::class,'logedin'])->name('logedin.admin');
Route::get('vendor-login',[VendorController::class,'vendorLoginPage'])->name('vendor.login.page');
Route::post('vendor-login',[VendorController::class,'vendorLogedin'])->name('logedin.vendor');
Route::get('fieldagent-login',[FieldAgentController::class,'fieldagentLoginPage'])->name('fieldagent.login.page');
Route::post('fieldagent-login',[FieldAgentController::class,'fieldagentLogedin'])->name('logedin.fieldagent');

Route::middleware(['auth.admin'])->group(function () {
Route::get('admin/dashboard',[AdminController::class,'admin_dashboard'])->name('admin.dashboard');

Route::get('/admin/add-category',[CategoryController::class,'add_category'])->name('add.vendor.category');
Route::post('/admin/store-category',[CategoryController::class,'storecategory'])->name('store.vendor.category');
Route::post('/admin/store-assets',[CategoryController::class,'storeassets'])->name('store.vendor.assets');

Route::get('/admin/add-sub-category',[CategoryController::class,'add_sub_category'])->name('add.vendor.subcategory');
Route::post('/admin/store-sub-category',[CategoryController::class,'storesubcategory'])->name('store.vendor.subcategory');

Route::get('/admin/add-vendor',[AdminController::class,'add_vendor'])->name('add.vendor');
Route::post('/get-subcategories', [AdminController::class, 'getSubcategories'])->name('get.subcategories');
Route::post('/get-categories-by-hoarding', [AdminController::class, 'getCategoriesByHoarding'])->name('get.categories.by.hoarding');

Route::post('/admin/store-vendor',[AdminController::class,'store'])->name('store.vendor');
Route::get('/admin/create-vendor-through-csv',[AdminController::class,'createVendorThroughCsv'])->name('createvendor.throughcsv');
Route::get('/admin/download-csv-sample',[AdminController::class,'csvSampleDownload'])->name('vendors.downloadCsvSample');
Route::post('/admin/import-csv',[AdminController::class,'vendorCsvImportant'])->name('vendorsCsv.import');

Route::get('/admin/vendor-list',[AdminController::class,'list'])->name('list.vendor'); 
Route::get('/admin/add-campaignadmin',[AdminController::class,'addcampaign'])->name('add.campaignadmin');
Route::post('/admin/add-campaignadmin',[AdminController::class,'storecampaign'])->name('store.campaignadmin');
Route::get('/admin/campaign-list',[AdminController::class,'listcampaign'])->name('list.campaignadmin');
Route::get('/admin/campaign-details', [AdminController::class, 'campaignDetails'])->name('campaignView.admin');
Route::get('/admin/view-vendor-campaign-details/{campaign_id}', [AdminController::class, 'VendorCampaignView'])->name('VendorCampaignView.admin');
Route::get('/admin/Reports', [AdminController::class, 'report']);


Route::get('/admin/imageuploadcamp-list',[AdminController::class,'imageuploadcampList'])->name('list.imageuploadcamp');
Route::get('/admin/download-monthly-images-pdf', [AdminController::class, 'DownloadMonthlyImagePDF'])->name('download.monthly.images.pdf');
Route::get('/admin/campaign-assign-to-vendor/{id}',[AdminController::class,'showAssignPage'])->name('campaignAssign.adminPage');
Route::post('/admin/campaign-assign-to-vendor/{id}',[AdminController::class,'campaignAssignByAdmin'])->name('campaignAssign.vendors');
Route::get('/admin/campaign-edit/{id}',[AdminController::class,'campaignEditByAdmin'])->name('campaignEdit.admin');
Route::put('/admin/campaign-edit/{id}',[AdminController::class,'campaignUpdateByAdmin'])->name('campaignUpdate.admin');
Route::delete('/admin/campaign-list/{vendor_id}',[AdminController::class,'campaignDeleteByAdmin'])->name('campaignDelete.admin');
Route::put('/admin/toggle-status/{id}', [AdminController::class, 'toggleStatus'])->name('vendor.toggleStatus');
Route::delete('/admin/vendor/{id}', [AdminController::class, 'destroy'])->name('vendor.destroy');
Route::get('/admin/vendor-details/{id}', [AdminController::class, 'vendor_details'])->name('vendor.view');
Route::get('/admin/vendor-edit/{id}', [AdminController::class, 'edit'])->name('vendor.edit');
Route::put('/admin/vendor-edit/{id}', [AdminController::class, 'update'])->name('vendor.update');

Route::get('/admin/campaign-wise-report', [ReportController::class, 'campaignWiseReport'])->name('report.campaignWise');

Route::get('logout', [AdminController::class, 'logout'])->name('admin.logout');

Route::get('admin/campaignassignfilter', [CampaignController::class, 'showFilterForm'])->name('campaign.assign.filter');
Route::post('/admin/assign-campaign-final', [CampaignController::class, 'assignCampaignFinal'])->name('assign.campaign.final');


Route::get('/admin/ongoing-campaigns', [AdminController::class, 'adminongoingCampaigns'])->name('admin.ongoingCampaigns');
Route::get('/admin/previous-campaigns', [AdminController::class, 'adminpreviousCampaigns'])->name('admin.previousCampaigns');
});


Route::middleware(['auth.vendor'])->group(function () {     
    Route::get('/vendor/vendor-dashboard',[VendorController::class,'vendor_dashboard'])->name('vendor.dashboard');
    Route::get('vendorlogout', [VendorController::class, 'vendorlogout'])->name('vendor.logout');
    Route::get('/vendor/field-agent', [VendorController::class, 'page'])->name('fieldagent.page');
    Route::post('/vendor/field-agent', [VendorController::class, 'store'])->name('fieldagent.store');
    Route::get('/vendor/create-field-agent-through-csv', [VendorController::class, 'FieldagentCsvPage'])->name('fieldAgentCsv.Page');
    Route::get('/vendor/download-field-agent-sample-csv', [VendorController::class, 'downloadFieldAgentCsvSample'])->name('downloadFieldAgentCsv.Sample');
    Route::post('/vendor/import-field-agent-csv-file', [VendorController::class, 'importFieldAgentCsvFile'])->name('importFieldAgentCsv.file');

    



    Route::get('/vendor/field-agent-list', [VendorController::class, 'fielagentlist'])->name('fieldagent.list');
    Route::put('/vendor/field-agent-list/{id}', [VendorController::class, 'fielagentToggle'])->name('fieldagent.toggleStatus');
    Route::delete('/vendor/field-agent-list/{id}', [VendorController::class, 'fieldagentDestroy'])->name('fieldagent.destroy');
    Route::get('/vendor/field-agent-edit/{id}', [VendorController::class, 'fieldagentEdit'])->name('fieldagent.edit');
    Route::post('/vendor/field-agent-edit/{id}', [VendorController::class, 'fieldagentUpdate'])->name('fieldagent.update');
    Route::get('/vendor/add-campaign',[VendorController::class,'addcampaignvendor'])->name('add.campaignvendor');
    Route::post('/vendor/add-campaignvendor',[VendorController::class,'storecampaignvendor'])->name('store.campaignvendor');
    Route::get('/vendor/campaign-list', [VendorController::class, 'campaignlist'])->name('campaign.list');
    Route::get('/vendor/campaign-edit-vendor/{id}', [VendorController::class, 'vendorCampaignEdit'])->name('vendorCampaign.edit');
    Route::put('/vendor/campaign-edit-vendor/{id}', [VendorController::class, 'vendorCampaignUpdate'])->name('vendorCampaign.update');
    Route::post('/vendor/compaign-list', [VendorController::class, 'uploadImage'])->name('upload.image');
    Route::get('/vendor/campaign-assign-to-fieldagent/{id}', [VendorController::class, 'campaignAssignVendorPage'])->name('campaignAssign.vendorPage');
    Route::post('/vendor/campaign-assign-to-fieldagent/{id}', [VendorController::class, 'campaignAssignVendor'])->name('campaignAssign.vendor');
    // Route::post('/vendor/campaign-list/{id}', [VendorController::class, 'campaignAssignVendor'])->name('campaignAssign.vendor');
    Route::get('/vendor/show-all-campaign-list', [VendorController::class, 'campaignShow'])->name('allCampaignAssign.list');
    Route::get('/vendor/campaign-history/{campaign_id}', [VendorController::class, 'FieldAgentCampaignView'])->name('FieldAgentCampaign.View');
    Route::get('image-verification/{id}/approve', [VendorController::class, 'verify'])->name('vendor.image.verify');
    Route::post('image-verification/{id}/reject', [VendorController::class, 'reject'])->name('vendor.image.reject');

    Route::get('/vendor/ongoing-campaigns', [VendorController::class, 'ongoingCampaigns'])->name('vendor.ongoingCampaigns');
    Route::get('/vendor/previous-campaigns', [VendorController::class, 'previousCampaigns'])->name('vendor.previousCampaigns');

    Route::get('/vendor/fieldagent-report', [ReportController::class, 'fieldagentReport'])->name('fieldagent.report');

   });

Route::middleware(['auth.fieldagent'])->group(function () {
    Route::get('fieldagent/fieldagent-dashboard', [CampaignController::class, 'fieldagent_dashboard'])->name('fieldagent.dashboard');
    Route::get('fieldagentlogout', [FieldAgentController::class, 'fieldagentlogout'])->name('fieldagent.logout');
    Route::get('/fieldagent/campaign', [CampaignController::class, 'campaignpage'])->name('campaign.page');
    Route::post('/fieldagent/campaign', [CampaignController::class, 'store'])->name('campaign.store');
    Route::get('/fieldagent/campaign-list', [CampaignController::class, 'campaignlistToFieldagent'])->name('campaignlistToFieldagent.list');
    Route::post('/fieldagent/campaign-list', [CampaignController::class, 'uploadImage'])->name('upload.image');
    Route::get('/fieldagent/campaign-edit/{id}', [CampaignController::class, 'editpage'])->name('campaignEdit.page');
    Route::put('/fieldagent/update/{id}', [CampaignController::class, 'update'])->name('campaign.update');
    Route::post('webcam', [CampaignController::class, 'store'])->name('webcam.capture');
    Route::delete('/fieldagent/{id}', [CampaignController::class, 'delete'])->name('campaign.delete');
});
    