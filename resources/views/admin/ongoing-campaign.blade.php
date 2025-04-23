@extends('layout.main')

@section('content')
<div class="card thm-card">
    <div class="card-header thm-card-head bg-gradient-danger d-flex flex-wrap align-items-center justify-content-between">
        <h5 class="card-title thm-card-title ms-0">
            Ongoing Campaign List<i class="fa fa-list-alt ms-2"></i>
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
                    @if($campaigns->count() > 0)
                        @foreach ($campaigns as $campaignVendor)
                            <tr>
                                <td>{{ $sr++ }}</td>
                                <td>{{ $campaignVendor->campaign_name }}</td>
                                <td>{{ $campaignVendor->start_date }}</td>
                                <td>{{ $campaignVendor->end_date }}</td>
                                <td>
                                    <a href="{{ route('VendorCampaignView.admin', ['campaign_id' => $campaignVendor->id]) }}" class="btn btn-primary">View Campaign</a>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-center text-dark fw-bold">No Data Found</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
