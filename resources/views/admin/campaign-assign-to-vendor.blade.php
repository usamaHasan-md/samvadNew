@extends('layout.main')
@section('content')

{{-- @if(session('successMessage'))
    <div class="alert alert-success custom-alert success">
        {{ session('successMessage') }}
    </div>
@endif

@if(session('dangerMessage'))
    <div class="alert alert-danger custom-alert danger">
        {{ session('dangerMessage') }}
    </div>
@endif --}}
<div class="row">
    <div class="col-xl-9 mx-auto">
         <div class="card thm-card  assign-campaign-container">
            <div class="card-header bg-gradient-danger thm-card-head d-flex flex-wrap align-items-center justify-content-between">
                <h5 class="card-title thm-card-title ms-0">Assign Campaign: {{ $campaign->campaign_name }}</h5>
                <a href="{{route('list.campaignadmin')}}" class="btn btn-light btn-sm text-primary">Back<i class="fa fa-arrow-left ms-1"></i></a>
            </div>
   
    <!--<h4 class="heading"></h4>-->
            <div class="card-body thm-card-body">
                <form action="{{ route('campaignAssign.vendors', $campaign->id) }}" method="POST" class="assign-form">
                    @csrf
            
                    <div class="form-group">
                        <label class="form-label fs-5">Select City</label>
            
                        <div class="select-all-wrapper">
                            <input type="checkbox" id="select_all" />
                            <label for="select_all" class="select-all-label"><strong>Select All</strong></label>
                        </div>
            
                        <div class="city-list">
                            @foreach($cities as $city)
                                <div class="form-check city-block d-flex align-items-center" style="margin-bottom: 5px;">
                                    <input class="form-check-input city-checkbox ms-0" type="checkbox" name="city[]" value="{{ $city }}" id="city_{{ $loop->index }}">
                                    <label class="form-check-label" for="city_{{ $loop->index }}">
                                        {{ $city }}
                                    </label>
            
                                    <!-- Vendor Limit Input -->
                                    <div class="vendor-limit" id="vendor_limit_{{ $loop->index }}" style="display: none; margin-left: 20px;">
                                        <label for="limit_{{ $loop->index }}">Vendors:</label>
                                        <input type="number" name="vendor_limit[{{ $city }}]" id="limit_{{ $loop->index }}" placeholder="All" min="1" class="form-control form-control-sm ms-2" style="width: 80px; display: inline-block;">
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
            
                    <button type="submit" class="btn-assign btn-primary">Assign to Vendors</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .assign-campaign-container {
        /*width:600px;*/
        margin: 30px auto;
        background-color: #f8f9fa;
        /*padding: 30px;*/
        border-radius: 12px;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
        font-family: 'Segoe UI', sans-serif;
    }

    .heading {
        margin-bottom: 20px;
        color: #333;
        text-align: center;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        font-weight: bold;
        display: block;
        margin-bottom: 10px;
        color: #555;
    }

    .select-all-wrapper {
        margin-bottom: 10px;
    }

    .select-all-label {
        margin-left: 5px;
        color: #007bff;
        cursor: pointer;
    }

    .city-list {
        max-height: 300px;
        overflow-y: auto;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 8px;
        background: #fff;
    }

    .form-check {
        margin-bottom: 8px;
    }

    .form-check-label {
        margin-left: 5px;
        color: #444;
    }

    .btn-assign {
        display: block;
        width: 100%;
        padding: 12px;
        /*background-color: #28a745;*/
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .btn-assign:hover {
        /*background-color: #218838;*/
    }

    .custom-alert {
        max-width: 600px;
        margin: 20px auto;
        padding: 15px;
        border-radius: 8px;
        text-align: center;
    }

    .custom-alert.success {
        background-color: #d4edda;
        color: #155724;
    }

    .custom-alert.danger {
        background-color: #f8d7da;
        color: #721c24;
    }

    .vendor-limit label {
        margin-right: 5px;
        font-size: 14px;
        color: #666;
    }
    .city-block{
        padding:10px;
        border-bottom:1px solid lightgrey;
       
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Toggle vendor limit input on city checkbox click
        document.querySelectorAll('.city-checkbox').forEach((checkbox, index) => {
            checkbox.addEventListener('change', function () {
                const vendorLimit = document.getElementById(`vendor_limit_${index}`);
                vendorLimit.style.display = this.checked ? 'inline-block' : 'none';
            });
        });

        // Select All checkbox
        const selectAllCheckbox = document.getElementById('select_all');
        selectAllCheckbox.addEventListener('change', function () {
            const cityCheckboxes = document.querySelectorAll('.city-checkbox');
            cityCheckboxes.forEach((checkbox, index) => {
                checkbox.checked = this.checked;
                const vendorLimit = document.getElementById(`vendor_limit_${index}`);
                vendorLimit.style.display = this.checked ? 'inline-block' : 'none';
            });
        });
    });
</script>
@endsection
