@extends('layout.main')
@section('content')
<div class="row">
    <div class="col-xl-9 mx-auto">
        <div class="card thm-card">
            <div class="thm-card-head bg-gradient-danger card-header d-flex flex-wrap align-items-center justify-content-between">
                <h5 class="thm-card-title  card-title ms-0">
                    Vendor Profile <i class="fa fa-user ms-2"></i>
                </h5>
                <a href="{{route('list.vendor')}}" class="btn btn-light btn-sm text-primary">Back<i class="fa fa-arrow-left ms-1"></i></a>
            </div>
            <div class="card-body thm-card-body">
                <div class=" col-lg-12 mx-auto">
                    <div class="card shadow-sm border-0 overflow-hidden">
                        <div class="card-body">
                            <div class="text-center">
                                <h4 class="mb-1">{{ $vendor->name }}</h4>
                                <p class="mb-0 text-secondary">{{ $vendor->city }}, {{ $vendor->state }}, India</p>
                                <div class="mt-4"></div>
                            </div>
                            <hr>
                            <div class="text-start">
                                <!-- <h5 class="mb-2">Contact Details</h5> -->
                                <table class="table table-striped table-bordered">
                                    <tr>
                                        <th colspan="2">
                                            <h5 class="my-auto">Hoarding, Category & Subcategory Details</h5>
                                        </th>
                                    </tr>

                                    {{-- Hoarding IDs --}}
                                    <tr>
                                        <th>Assigned Hoardings</th>
                                        <td>
                                            @php
                                            $hoardingIds = json_decode($vendor->assigned_hoarding_id, true);
                                            @endphp
                                            @if (!empty($hoardingIds))
                                            {{ implode(', ', $hoardingIds) }}
                                            @else
                                            N/A
                                            @endif
                                        </td>
                                    </tr>

                                    {{-- Category --}}
                                    <tr>
                                        <th>Category</th>
                                        <td>
                                            @php
                                                $categories = json_decode($vendor->category, true);
                                                $categoryNames = [];
                                    
                                                if (!empty($categories)) {
                                                    $categoryNames = \App\Models\Category::whereIn('id', $categories)->pluck('category')->toArray();
                                                }
                                            @endphp
                                    
                                            {{ !empty($categoryNames) ? implode(', ', $categoryNames) : 'N/A' }}
                                        </td>
                                    </tr>
                                    
                                    {{-- Subcategory --}}
                                    <tr>
                                        <th>Subcategory</th>
                                        <td>
                                            @php
                                                $subcategories = json_decode($vendor->sub_category, true);
                                                $subcategoryNames = [];
                                    
                                                if (!empty($subcategories)) {
                                                    $subcategoryNames = \App\Models\SubCategory::whereIn('id', $subcategories)->pluck('sub_category')->toArray();
                                                }
                                            @endphp
                                    
                                            {{ !empty($subcategoryNames) ? implode(', ', $subcategoryNames) : 'N/A' }}
                                        </td>
                                    </tr>


                                    <tr>
                                        <th colspan="2">
                                            <h5 class="my-auto">Contact Details</h5>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td>{{ $vendor->email }}</td>
                                    </tr>
                                    <tr>
                                        <th>Mobile No.</th>
                                        <td>{{ $vendor->contact }}</td>
                                    </tr>
                                    <tr>
                                        <th>City</th>
                                        <td>{{ $vendor->city }}</td>
                                    </tr>
                                    <tr>
                                        <th>State</th>
                                        <td>{{ $vendor->state }}</td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">
                                            <h5 class="my-auto">Contact Person Details</h5>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>Contact Person Name</th>
                                        <th>Contact Number</th>
                                    </tr>

                                    @php
                                    $contactPersons = json_decode($vendor->contact_persons, true);
                                    @endphp

                                    @if(!empty($contactPersons))
                                    @foreach($contactPersons as $person)
                                    <tr>
                                        <td>{{ $person['name'] }}</td>
                                        <td>{{ $person['number'] }}</td>
                                    </tr>
                                    @endforeach
                                    @else
                                    <tr>
                                        <td colspan="2">No contact persons available.</td>
                                    </tr>
                                    @endif

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection