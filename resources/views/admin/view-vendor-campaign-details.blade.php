@extends('layout.main')
@section('content')
<div class="card thm-card">
    <div class="card-header thm-card-head bg-gradient-danger d-flex flex-wrap align-items-center justify-content-between">
        <h5 class="thm-card-title card-title ms-0">
            Campaign Details<i class="fa fa-list-alt ms-2"></i>
        </h5>
        <a href="{{route('campaignView.admin')}}" class="btn btn-light btn-sm text-primary">Back<i class="fa fa-arrow-left ms-1"></i></a>
    </div>
    <div class="card-body thm-card-body">
         <!-- Campaign Information -->
        <div class="table-responsive mt-3">
            <table class="table align-middle thm-tbl">
                <thead>
                    <tr>
                        <th colspan="2">Campaign Information</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>Name:</th>
                        <td>{{ $campaign->campaign_name }}</td>
                    </tr>
                    <tr>
                        <th>Description:</th>
                        <td style="min-width:450px max-width:500px; white-space:normal;">{{ $campaign->description }}</td>
                    </tr>
                    <tr>
                        <th>Start Date:</th>
                        <td>{{ \Carbon\Carbon::parse($campaign->start_date)->format('d-M-Y') }}</td>
                    </tr>
                    <tr>
                        <th>End Date:</th>
                        <td>{{ \Carbon\Carbon::parse($campaign->end_date)->format('d-M-Y') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Vendors Tables -->
        <div class="table-responsive mt-3">
            <table class="table align-middle thm-tbl">
                <thead>
                    <tr>
                        <th colspan="2">Assigned Vendors</th>
                    </tr>
                    <tr>
                        <th>Vendor Name</th>
                        <th>Vendor Email</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vendors as $vendor)
                    <tr>
                        <td>{{ $vendor->name }}</td>
                        <td>({{ $vendor->email }})</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Field agents Tables -->
        <div class="table-responsive mt-3">
            <table class="table align-middle thm-tbl">
                <thead>
                    <tr>
                        <th colspan="2">Assigned Field Agents</th>
                    </tr>
                    <tr>
                        <th>Field Agents Name</th>
                        <th>Field Agents Contact Number</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($fieldAgents as $agent)
                    <tr>
                        <td>{{ $agent->name }}</td>
                        <td>({{ $agent->number }})</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

    <!-- Campaign Images -->
   <div class="table-responsive mt-3">
        <table class="table table-bordered thm-tbl">
        <thead >
            <tr>
                <th>S no.</th>
                <th>Images</th>
                <th>Uploaded On</th>
                <th>Latitude</th>
                <th>Longitude</th>
            </tr>
        </thead>
        <tbody>
            @if($imageUploads->isNotEmpty())
                @foreach($imageUploads as $index => $image)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <img src="{{ asset('public/' . $image->image) }}" alt="Campaign Image" width="50">
                    </td>
                    <td>{{ \Carbon\Carbon::parse($image->date)->format('d-M-Y') }}</td>
                    <td>{{ $image->latitude }}</td>
                    <td>{{ $image->longtitude }}</td>
                </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="5" class="text-center">No images uploaded yet.</td>
                </tr>
            @endif
        </tbody>
    </table>
   </div>

@endsection
