@extends('layout.main')
@section('content')
<div class="card thm-card">
     <div class="card-header thm-card-head bg-gradient-danger d-flex flex-wrap align-items-center justify-content-between">
        <h5 class="card-title thm-card-title ms-0">
            Campaign List<i class="fa fa-list ms-2"></i>
        </h5>
        <a href="{{route('admin.dashboard')}}" class="btn btn-light btn-sm text-primary">Back<i class="fa fa-arrow-left ms-1"></i></a>

    </div>
    <div class="card-body thm-card-body">
        <div class="d-flex align-items-center">
            <!--<h5 class="mb-0">Campaign List</h5>-->
            @php
            $sr=1;
            @endphp
        </div>
        <div class="table-responsive mt-3">
            <table class="table align-middle thm-tbl table-bordered" id="example">
                <thead class="table-secondary text-center">
                    <tr>
                        <th>S.No.</th>
                        <th>Campaign Name</th>
                        <th>Images</th>
                        <th>Workorder of Campaign</th>
                        <th style="min-width:400px;white-space:normal;">Description</th>
                        <th>Type</th>
                        <th style="min-width:400px;white-space:normal;">Sub-Type</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <!--<th>Campaign Assign<br>  To vendor</th>-->
                        <!--<th>Aciton</th>-->
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach($listcampaigns as $listcampaign)
                    <tr>
                        <td>{{$sr++}}</td>
                        <td>{{$listcampaign->campaign_name}}</td>
                        @php
                        $images = json_decode($listcampaign->images);
                        @endphp
                        <td>
                            @if(!empty($images))
                            @foreach($images as $image)
                          <a data-fancybox="gallery" href="{{ asset('public/' . $image) }}"><img src="{{ asset('public/' . $image) }}" alt="Uploaded Image"  class="me-2 col-12 select-img-preview"></a>
                          <!--<a data-fancybox="gallery" href="{{ asset('public/' . $image) }}"><img src="{{ asset('public/' . $image) }}" alt="Uploaded Image"  class="me-2 col-12 select-img-preview"></a>-->
                            @endforeach
                            @else
                            No Image
                            @endif
                        </td>
                        <td>
                            @if(!empty($listcampaign->pdf))
                            <a href="{{ asset('public/' . $listcampaign->pdf) }}" target="_blank"><i class="fa fa-download" style="font-size:25px;color:rgb(15, 175, 234)" aria-hidden="true"></i></a>
                            @else
                            No PDF
                            @endif
                        </td>
                        <td style="min-width:400px; white-space:normal;">{!!$listcampaign->description!!}</td>
                        <td>
                            @php
                                $categories = json_decode($listcampaign->category, true);
                                $categoryNames = [];
                    
                                if (!empty($categories)) {
                                    $categoryNames = \App\Models\Category::whereIn('id', $categories)->pluck('category')->toArray();
                                }
                            @endphp
                    
                            {{ !empty($categoryNames) ? implode(', ', $categoryNames) : 'N/A' }}
                        </td>
                        <td style="min-width:400px;white-space:normal;">
                            @php
                                $subcategories = json_decode($listcampaign->sub_category, true);
                                $subcategoryNames = [];
                    
                                if (!empty($subcategories)) {
                                    $subcategoryNames = \App\Models\SubCategory::whereIn('id', $subcategories)->pluck('sub_category')->toArray();
                                }
                            @endphp
                    
                            {{ !empty($subcategoryNames) ? implode(', ', $subcategoryNames) : 'N/A' }}
                        </td>
                        <td>{{$listcampaign->start_date}}</td>
                        <td>{{$listcampaign->end_date}}</td>
                        <!--<td>-->
                        <!--    <a href="{{ route('campaignAssign.adminPage', ['id' => $listcampaign->id]) }}" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit">Assign To Vendor</a>-->
                        <!--</td>-->
                        <!--<td>-->
                        <!--    <div class="table-actions d-flex align-items-center fs-6">-->
                        <!--        {{-- -----------------for update-------------------- --}}-->
                        <!--        <a href="{{ route('campaignEdit.admin', ['id' => $listcampaign->id]) }}" class="btn btn-secondary me-2 btn-sm" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit"><i class="bi bi-pencil-fill mx-auto"></i></a>-->
                        <!--        {{-- -----------for delete------------ --}}-->
                        <!--        <form action="{{ route('campaignDelete.admin', $listcampaign->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this campaign?');">-->
                        <!--            @csrf-->
                        <!--            @method('DELETE')-->
                        <!--            <button type="submit" class="btn btn-danger btn-sm" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete">-->
                        <!--                <i class="bi bi-trash-fill mx-auto "></i>-->
                        <!--            </button>-->
                        <!--        </form>-->
                        <!--        {{-- -----------View---------- --}}-->
                                <!--<a href="{{ route('campaignView.admin', ['id' => $listcampaign->id]) }}" class="text-info" data-bs-toggle="tooltip" data-bs-placement="bottom" title="View"><i class="bi bi-eye-fill"></i></a>-->
                        <!--    </div>-->
                        <!--</td>-->
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection