<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiLoginController;
use App\Http\Controllers\Api\ApiCampaignController;


Route::get('/test-api', function () {
    return response()->json(['status' => 'API is working!']);
});

Route::post('/fieldagent-login', [ApiLoginController::class, 'fieldagentlogin']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/fieldagent-profile', [ApiLoginController::class, 'profile']);
    Route::get('/fieldagent-campaigns/{id}', [ApiCampaignController::class, 'campaignListForFieldAgent']);
    Route::post('/upload-image-app', [ApiCampaignController::class, 'uploadImageFromApp']);
    Route::get('/fieldagent/campaign/{campaign_id}', [ApiCampaignController::class, 'apiFieldAgentCampaignView']);
    Route::get('/fieldagent/images', [ApiCampaignController::class, 'apiVendorViewImages']);
    Route::post('/fieldagent-logout', [ApiLoginController::class, 'fieldagentlogout']);
});