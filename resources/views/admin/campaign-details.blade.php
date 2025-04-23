@extends('layout.main')

@section('content')
<div class="card thm-card">
    <div class="card-header thm-card-head bg-gradient-danger d-flex flex-wrap align-items-center justify-content-between">
        <h5 class="card-title thm-card-title ms-0">
            Campaign List<i class="fa fa-list-alt ms-2"></i>
        </h5>
        <a href="{{route('admin.dashboard')}}" class="btn btn-light btn-sm text-primary">Back<i class="fa fa-arrow-left ms-1"></i></a>
    </div>
    <div class="card-body thm-card-body">
        <div class="d-flex align-items-center">
            <!--<h4 class="mb-3">Assigned Campaign Details</h4>-->
            @php
            $sr=1;
            @endphp
        </div>
        <div class="table-responsive mt-3">
            <table class="table align-middle thm-tbl" id="example">
                <thead class="table-secondary">
                    <tr>
                        <th>Sr. No.</th>
                        <th>Campaign Name</th>
                        <!--<th>Assigned Vendors</th>-->
                        <th>Start Date</th>
                        <th>Start End</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($campaignVendors as $campaignVendor)
                    <tr>
                        <td>{{ $sr++ }}</td>
                        <td>{{ $campaignVendor->campaign_name }}</td>
                        <!--<td>{{ $campaignVendor->vendor_names }}</td>-->
                        <td>01-04-2025</td>
                        <td>25-04-2025</td>
                        <td>
                            <a href="{{url('/admin/view-campaign-details')}}" class="btn btn-primary">View Campaign</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
