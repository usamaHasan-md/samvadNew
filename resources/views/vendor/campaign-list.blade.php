@extends('layout.main')
@section('content')
<div class="card thm-card">
    <div class="card-header thm-card-head bg-gradient-danger d-flex flex-wrap align-items-center justify-content-between">
        <h5 class="card-title thm-card-title ms-0">Assigned Campaign List<i class="fa fa-list ms-2"></i></h5>
        <a href="{{route('vendor.dashboard')}}" class="btn btn-light btn-sm text-primary">Back<i class="fa fa-arrow-left ms-1"></i></a>
    </div>
    <div class="card-body thm-card-body">
        <!--<div class="d-flex align-items-center">-->
            <!--<h5 class="mb-3">Assigned Campaign List</h5>-->
            {{-- ------For delete---- --}}

            
        <!--</div>-->
        <div class="table-responsive mt-3">
            <table class="table align-middle thm-tbl" id="example">
                <thead class="table-secondary text-center">
                    <tr>
                        <th>Sr. No.</th>
                        <th>Campaign Name</th>
                        <th>Campaign Images</th>
                        <th>Workorder of Campaign</th>
                        <th style="min-width:400px;">Description</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <!--<th>Assign to Field Agent</th>-->
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach($compaigns as $compaign)
                    <tr>
                        <td>{{$compaign->id}}</td>
                        <td>{{$compaign->campaign_name}}</td>
                        @php
                        $images = json_decode($compaign->images);
                        @endphp
                        <td>
                            @if(!empty($images))
                            @foreach($images as $image)
                            <a href="{{ asset('public/' . $image) }}" data-fancybox="gallery">
                              <img src="{{ asset('public/' . $image) }}" alt="Uploaded Image" width="30" class="select-img-preview">
                            </a>
                            @endforeach
                            @else
                            No Image
                            @endif
                        </td>
                        <td>
                            @if(!empty($compaign->pdf))
                            <a href="{{ asset('public/' . $compaign->pdf) }}" target="_blank" style="color: red"><i class="fa fa-download" style="font-size:25px;color:rgb(15, 175, 234)" aria-hidden="true"></i></a>
                            @else
                            No PDF
                            @endif
                        </td>
                        <td style="word-wrap: break-word; white-space: normal;">{!!$compaign->description!!}</td>
                        <td>{{$compaign->start_date}}</td>
                        <td>{{$compaign->end_date}}</td>
                        <!--<td>-->
                        <!--   <a href="{{ route('campaignAssign.vendorPage', ['id' => $compaign->id]) }}" class="btn btn-primary " data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit">Assign To Field Agent</a>-->
                        <!--</td>-->
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection