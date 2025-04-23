<div class="iconmenu">
    <div class="nav-toggle-box">
        <div class="nav-toggle-icon"><i class="bi bi-list"></i></div>
    </div>
    <ul class="nav nav-pills flex-column">
        @php
        $admin = auth()->guard('admin')->user();
        $vendor = auth()->guard('vendor')->user();
        $fieldAgent = auth()->guard('fieldagent')->user();
        @endphp
        @if(auth('vendor')->check())
        <a href="{{route('vendor.dashboard')}}">
            <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="right" title="Dashboards">
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#pills-dashboards" type="button"><i class="bi bi-house-door-fill"></i></button>
            </li>
        </a>
        @else
        <a href="{{route('admin.dashboard')}}">
            <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="right" title="Dashboards">
                <button class="nav-link" data-bs-toggle="pill" data-bs-target="#pills-dashboards" type="button"><i class="bi bi-house-door-fill"></i></button>
            </li>
        </a>
        @endif
        @if($admin && $admin->role === 'admin')
        
        <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="right" title="Asset Management">
            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#pills-assets" type="button">
                <i class="bi bi-plus-circle-dotted"></i>
            </button>
        </li>
        <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="right" title="Campaign Management">
            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#pills-campaign" type="button">
                <i class="bi bi-megaphone"></i>
            </button>
        </li>
        <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="right" title="Vendor Management">
            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#pills-admin" type="button">
                <i class="bi bi-person"></i>
            </button>
        </li>
        
        <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="right" title="Assign Campaign">
            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#pills-campaignbyfilter" type="button">
                <i class="bi bi-calendar-plus"></i>
            </button>
        </li>
        <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="right" title="Campaign History">
            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#pills-vendorViewDetails" type="button">
                <i class="bi bi-clock"></i>
            </button>
        </li>
        <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="right" title="Campaign Report">
            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#pills-campaignReport" type="button">
                <i class="bi bi-file-earmark-ruled"></i>
            </button>
        </li>
        <!--<li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="right" title="Campaign Images">-->
        <!--    <button class="nav-link" data-bs-toggle="pill" data-bs-target="#pills-imageUploadByCampaign" type="button">-->
        <!--        <i class="bi bi-file-image"></i>-->
        <!--    </button>-->
        <!--</li>-->
        @endif
        {{-- Vendor Section --}}
    
        @if(auth('vendor')->check())
        <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="right" title="Field Agent Management">
            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#pills-vendor" type="button">
                <i class="bi bi-person"></i>
            </button>
        </li>
        <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="right" title="View Assigned Campaigns">
            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#pills-vendor-campaign" type="button">
                <i class="bi bi-megaphone"></i>
            </button>
        </li>
        <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="right" title="Campaign History">
            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#pills-vendor-campaign-history" type="button">
                <i class="bi bi-clock"></i>
            </button>
        </li>
        <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="right" title="Campaign History">
            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#pills-vendor-report" type="button">
                <i class="bi bi-clock"></i>
            </button>
        </li>
        @endif
        {{-- Field Agent Section --}}
        @if($fieldAgent && $fieldAgent->role === 'fieldagent')
        <li class="nav-item" data-bs-toggle="tooltip" data-bs-placement="right" title="View Assigned Campaign">
            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#pills-fieldagent" type="button">
                <!-- Use the Magento Brand Icon -->
                <i class="bi bi-megaphone"></i></button>
        </li>
        @endif
    </ul>
</div>
<div class="textmenu">
    <div class="brand-logo">
        @if(auth('admin')->check())
        <a href="{{route('admin.dashboard')}}" class="mx-auto">
            <!--<h3 class="text-primary" style="font-family:'roboto slab';">Samvad</h3>-->
            <img src="{{asset('public/assets/images/icons/samvad-logo.png')}}" style="height:50px; width:auto;" class="my-auto">
        </a>
        @elseif(auth('vendor')->check())
        <a href="{{route('vendor.dashboard')}}" class="mx-auto">
            <!--<h3 class="text-primary" style="font-family:'roboto slab';">Samvad</h3>-->
            <img src="{{asset('public/assets/images/icons/samvad-logo.png')}}" style="height:50px; width:auto;" class="my-auto">
        </a>
        @elseif(auth('fieldagent')->check())
        <a href="{{route('fieldagent.dashboard')}}" class="mx-auto">
            <!--<h3 class="text-primary" style="font-family:'roboto slab';">Samvad</h3>-->
            <img src="{{asset('public/assets/images/icons/samvad-logo.png')}}" style="height:50px; width:auto;" class="my-auto">
        </a>
        @else
        Welcome, Guest
        @endif
    </div>
    <div class="tab-content">
    @if($admin && $admin->role === 'admin')
    
        <div class="tab-pane fade" id="pills-dashboards">
            <div class="list-group list-group-flush">
                <div class="list-group-item">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-0">Admin Management Dashboard</h5>
                    </div>
                    <!--<small class="mb-0">Dashboard content</small>-->
                </div>
                <a href="{{route('admin.dashboard')}}" class="list-group-item"><i class="bi bi-speedometer2"></i> Dashboard</a>
            </div>
        </div>
    <!--</div>-->
    @endif
    @if($vendor && $vendor->role === 'vendor')
    <!--<div class="tab-content">-->
        <div class="tab-pane fade" id="pills-dashboards">
            <div class="list-group list-group-flush">
                <div class="list-group-item">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-0">Vendor Dashboards</h5>
                    </div>
                    <!--<small class="mb-0">Dashboard content</small>-->
                </div>
                <a href="{{route('vendor.dashboard')}}" class="list-group-item"><i class="bi bi-speedometer2"></i> Dashboard</a>
            </div>
        </div>
    <!--</div>-->
    @endif
     @if($fieldAgent && $fieldAgent->role === 'fieldagent')
    <!--<div class="tab-content">-->
        <div class="tab-pane fade" id="pills-dashboards">
            <div class="list-group list-group-flush">
                <div class="list-group-item">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-0">Fieldagent Dashboards</h5>
                    </div>
                    <!--<small class="mb-0">Dashboard content</small>-->
                </div>
                <a href="{{route('fieldagent.dashboard')}}" class="list-group-item"><i class="bi bi-speedometer2"></i> Dashboard</a>
            </div>
        </div>
    <!--</div>-->
    @endif
    {{-- Super Admin Section --}}
    @if($admin && $admin->role === 'admin')
    
    
    <div class="tab-pane fade" id="pills-assets">
        <div class="list-group list-group-flush">
            <div class="list-group-item">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-0">Asset Management</h5>
                </div>
                <!--<small class="mb-0">Manage and organize vendor details</small>-->
            </div>
            <a href="{{route('add.vendor.category')}}" class="list-group-item"><i class="bi bi-plus-circle-dotted me-1"></i>Create Assets</a>
        </div>
    </div>
    
    <div class="tab-pane fade" id="pills-admin">
        <div class="list-group list-group-flush">
            <div class="list-group-item">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-0">Vendor Management</h5>
                </div>
                <small class="mb-0 text-muted">Create and manage Vendor details</small>
            </div>
            <a href="{{route('add.vendor')}}" class="list-group-item"><i class="bi bi-person-plus me-1"></i>Create Vendor</a>
            <a href="{{route('list.vendor')}}" class="list-group-item"><i class="bi bi-list-task me-1"></i>Manage Vendor</a>
        </div>
    </div>
    <div class="tab-pane fade" id="pills-campaign">
        <div class="list-group list-group-flush">
            <div class="list-group-item">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-0">Campaign Management</h5>
                </div>
                <small class="text-muted">Create and manage campaigns</small>
            </div>
            <a href="{{route('add.campaignadmin')}}" class="list-group-item"><i class="bi bi-plus-circle me-1"></i>Create Campaign</a>
            <a href="{{route('list.campaignadmin')}}" class="list-group-item"><i class="bi bi-list-ul me-1"></i>All Campaigns</a>
        </div>
    </div>
    <div class="tab-pane fade" id="pills-campaignbyfilter">
        <div class="list-group list-group-flush">
            <div class="list-group-item">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-0">Assign Campaign</h5>
                </div>
                <small class="text-muted">Assign Campaign Through Various Filter.</small>
            </div>
            <a href="{{route('campaign.assign.filter')}}" class="list-group-item"><i class="bi bi-people me-1"></i>Assign Campaign</a>
        </div>
    </div>
    <div class="tab-pane fade" id="pills-vendorViewDetails">
        <div class="list-group list-group-flush">
            <div class="list-group-item">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-0">Campaign History</h5>
                </div>
                <small class="text-muted">View Campaign Details</small>
            </div>
            <a href="{{route('admin.ongoingCampaigns')}}" class="list-group-item"><i class="bi bi-hourglass-top me-1"></i>Ongoing Campaign</a>
            <a href="{{route('admin.previousCampaigns')}}" class="list-group-item"><i class="bi bi-clock-history me-1"></i>Previous Campaign</a>
        </div>
    </div>
    
    <div class="tab-pane fade" id="pills-campaignReport">
        <div class="list-group list-group-flush">
            <div class="list-group-item">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-0">Campaign Report</h5>
                </div>
                <small class="text-muted">Download Report</small>
            </div>
            <a href="{{route('report.campaignWise')}}" class="list-group-item"><i class="bi bi-list me-1"></i>Report</a>         <!--<a href="javascript:void(0)" class="list-group-item"><i class="bi bi-people me-1"></i>Assigned Field Agents</a>-->
        </div>
    </div>
    <!--<div class="tab-pane fade" id="pills-imageUploadByCampaign">-->
    <!--    <div class="list-group list-group-flush">-->
    <!--        <div class="list-group-item">-->
    <!--            <div class="d-flex w-100 justify-content-between">-->
    <!--                <h5 class="mb-0">Image Upload by Campaign</h5>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--        <a href="{{route('list.imageuploadcamp')}}" class="list-group-item"><i class="bi bi-person-plus"></i>Images Upload by Campaign</a>-->
    <!--    </div>-->
    <!--</div>-->
    @endif
    {{-- vendor --}}
    @if($vendor && $vendor->role === 'vendor')
    <div class="tab-pane fade" id="pills-vendor">
        <div class="list-group list-group-flush">
            <div class="list-group-item">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-0">Field Agent Management</h5>
                </div>
                <small class="mb-0">Create and manage Field Agent details</small>
            </div>
            <a href="{{route('fieldagent.page')}}" class="list-group-item"><i class="bi bi-person"></i>Create Field Agent</a>
            <a href="{{route('fieldagent.list')}}" class="list-group-item"><i class="bi bi-person"></i>Manage Field Agent</a>
        </div>
    </div>
    <div class="tab-pane fade" id="pills-vendor-campaign">
        <div class="list-group list-group-flush">
            <div class="list-group-item">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-0">View Assigned Campaigns</h5>
                </div>
            </div>
            <a href="{{route('campaign.list')}}" class="list-group-item"><i class="bi bi-person-plus"></i>Assigned Campaigns</a>
        </div>
    </div>
    <div class="tab-pane fade" id="pills-vendor-campaign-history">
        <div class="list-group list-group-flush">
            <div class="list-group-item">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-0">Campaign History</h5>
                </div>
            </div>
            <a href="{{route('vendor.ongoingCampaigns')}}" class="list-group-item"><i class="bi bi-hourglass-top me-1"></i>Ongoing Campaigns</a>
            <a href="{{route('vendor.previousCampaigns')}}" class="list-group-item"><i class="bi bi-clock-history me-1"></i>Previous Campaigns</a>
        </div>
    </div>
    <div class="tab-pane fade" id="pills-vendor-report">
        <div class="list-group list-group-flush">
            <div class="list-group-item">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-0">Report</h5>
                </div>
            </div>
            <a href="{{route('fieldagent.report')}}" class="list-group-item"><i class="bi bi-hourglass-top me-1"></i>Fieldagent Report</a>
        </div>
    </div>
    @endif
    {{-- fieldagent --}}
     @if($fieldAgent && $fieldAgent->role === 'fieldagent')
    <div class="tab-pane fade" id="pills-fieldagent">
        <div class="list-group list-group-flush">
            <div class="list-group-item">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-0">View Assigned Campaign</h5>
                </div>
                {{-- <small class="mb-0">Some placeholder content</small> --}}
            </div>
            <a href="{{route('campaignlistToFieldagent.list')}}" class="list-group-item"><i class="bi bi-person-plus"></i>Campaign List</a>
        </div>
    </div>
    @endif
    </div>
</div>