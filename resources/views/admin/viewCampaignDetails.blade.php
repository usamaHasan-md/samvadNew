@extends('layout.main')
@section('content')
<style>
/* Style for our custom caption content */
.custom-caption {
  color: #fff;
  font-size: 16px;
  text-align: center;
  line-height: 1.4;
}

/* Override FancyBox caption container styling */
.fancybox__caption {
  position: absolute;  /* Make sure it's positioned over the image */
  bottom: 0;           /* Place it at the bottom */
  left: 0;
  width: 100%;
  background: rgba(0, 0, 0, 0.7); /* Semi-transparent dark overlay */
  padding: 10px;
  box-sizing: border-box;
  color: #fff;
  z-index: 10;         /* Ensure it's above the image */
}

.cmpn-verified-img{
    height:5rem;
    width:6rem;
    
}
</style>
<div class="card thm-card">
    <div class="card-header thm-card-head bg-gradient-danger d-flex flex-wrap align-items-center justify-content-between">
        <h5 class="thm-card-title card-title ms-0">
            Campaign Details<i class="fa fa-list-alt ms-2"></i>
        </h5>
        <a href="{{ url()->previous() }}" class="btn btn-light btn-sm text-primary">Back<i class="fa fa-arrow-left ms-1"></i></a>
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
                        <td style="min-width:450px max-width:500px; white-space:normal;">{!! $campaign->description !!}</td>
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
            
        <div class="row g-3">
            <h5 class="mb-0">Campaign Image</h5>
            <hr>
            <div class="table-responsive">
                <table class="table thm-tbl align-middle">
                    <!-- <table class="table table-bordered align-middle"> -->
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Hoarding ID</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th>Date/Time</th>
                            <th>Image</th>
                            <!--<th>Action</th>-->
                        </tr>
                    </thead>
                    <tbody>
                        @if($imageUploads->count() > 0) 
                            @foreach($imageUploads as $key => $image)
                            <tr id="row-{{ $key }}">
                                <td>{{$key+1}}</td>
                                <td>{{ $image->hoarding_id ?? 'N/A' }}</td>
                                <td>{{ $image->latitude ?? 'N/A' }}</td>
                                <td>{{ $image->longtitude ?? 'N/A' }}</td>
                                <td>{{ \Carbon\Carbon::parse($image->date)->format('d-M-Y H:i:s') ?? 'N/A' }}</td>
                                <td>
                                    <a
                                        data-fancybox="gallery"
                                        href="{{ asset('public/' . $image->image) }}"
                                        data-caption="
                                            <div class='custom-caption'>
                                                <strong>Vendor Name:</strong> {{ $image->vendor_name ?? 'N/A' }}<br>
                                                <strong>Date:</strong> {{ \Carbon\Carbon::parse($image->date)->format('d-M-Y') }}<br>
                                                <strong>Time:</strong> {{ \Carbon\Carbon::parse($image->date)->format('h:i A') }}<br>
                                                <strong>Location:</strong> {{ $image->latitude ?? 'N/A' }}, {{ $image->longtitude ?? 'N/A' }}
                                            </div>
                                        ">
                                        <img src="{{ asset('public/' . $image->image) }}"
                                             alt="Campaign Image"
                                             class="img-fluid rounded cmpn-verified-img">
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        @else
                        <tr>
                            <td colspan="6" class="text-center text-dark fw-bold">No Data Found</td>
                        </tr>
                        @endif
                    </tbody>
                    <!-- </table> -->
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

