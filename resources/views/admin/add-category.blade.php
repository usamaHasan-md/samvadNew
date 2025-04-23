@extends('layout.main')
@section('content')
<div class="row">
    <div class="col-xl-12 mx-auto text-end mb-2">
         <a href="{{route('admin.dashboard')}}" class="btn btn-primary btn-sm ">Back<i class="fa fa-arrow-left ms-1"></i></a>
    </div>
    <div class="col-xl-6 mx-auto">

        <div class="card thm-card ">
            <div class="card-header thm-card-head bg-gradient-danger">
                <h5 class="card-title thm-card-title">Create Category<i class="fa fa-th-large ms-2"></i></h5>
               
            </div>
            <div class="card-body thm-card-body">
                <form action="{{route('store.vendor.category')}}" method="POST"> <!-- Added form tag -->
				    @csrf
                    <div class="row mb-3">
                        <label for="inputEnterYourName" class="col-sm-3 col-form-label">Create Category:</label>
                        <div class="col-sm-9">
                            <input type="text" name="category" class="form-control" id="inputEnterYourName" placeholder="Enter Category Name">
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-3 col-form-label"></label>
                        <div class="col-sm-9">
                            <button type="submit" class="btn btn-primary px-5">Create</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-xl-6 mx-auto">
        <div class="card thm-card">
            <div class="card-header bg-gradient-danger thm-card-head d-flex flex-wrap align-items-center justify-content-between">
                <h5 class="card-title thm-card-title ms-0">Create Sub-Category<i class="bi bi-box ms-2"></i></h5>
                <!-- <a href="{{route('admin.dashboard')}}" class="btn btn-light btn-sm text-primary">Back<i class="fa fa-arrow-left ms-1"></i></a> -->
            </div>
            <div class="card-body thm-card-body">
                <form action="{{route('store.vendor.subcategory')}}" method="POST"> <!-- Added form tag -->
                    @csrf
                    <div class="row mb-3">
                        <label for="inputEnterYourName" class="col-sm-3 col-form-label">Select Category:</label>
                        <div class="col-sm-9">
                            <select id="category_select" name="category_id" class="form-control" required>
                                <option value="">--Select Category--</option>
                                @foreach($cat as $cats)
                                <option value="{{ $cats->id }}" data-name="{{ $cats->category }}">{{ $cats->category }}</option>
                                @endforeach
                            </select>
                            <!-- Hidden input to store selected category name -->
                            <input type="hidden" name="category" id="category_name">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEnterYourName" class="col-sm-3 col-form-label">Create Sub-Category:</label>
                        <div class="col-sm-9">
                            <input type="text" name="sub_category" class="form-control" placeholder="Enter Sub-Category Name" required>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-3 col-form-label"></label>
                        <div class="col-sm-9">
                            <button type="submit" class="btn btn-primary px-5">Create</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

   <div class="col-xl-12">
        <div class="card thm-card ">
            <div class="card-header thm-card-head bg-gradient-danger d-flex flex-wrap align-items-center justify-content-between">
                <h5 class="card-title thm-card-title ms-0">Create Assets<i class="fa fa-th-large ms-2"></i></h5>
                <!--<a href="{{route('admin.dashboard')}}" class="btn btn-light btn-sm text-primary">Back<i class="fa fa-arrow-left ms-1"></i></a>-->
            </div>
            <div class="card-body thm-card-body">
                <form action="{{route('store.vendor.assets')}}"  class="vldt-form" method="POST"> <!-- Added form tag -->
				    @csrf
                    <div class="row mb-3">
                        <label for="inputEnterYourName" class="col-sm-3 col-form-label">Hoarding ID:</label>
                        <div class="col-sm-9">
                            <input type="text" name="hoarding_id" class="form-control vldt requd" id="inputEnterYourName" placeholder="Enter Hoarding ID" >
                            <span class="js-error"></span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Select Category:</label>
                        <div class="col-sm-9">
                            <select name="category" id="category-select" class="form-control vldt requd" >
                                <option value="">-- Select Category --</option>
                                @foreach($cat as $cats)
                                    <option value="{{ $cats->id }}">{{ $cats->category }}</option>
                                @endforeach
                            </select>
                            <span class="js-error"></span>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Select Sub-Category:</label>
                        <div class="col-sm-9">
                            <select name="sub_category" id="subcategory-select" class="form-control multiple-select" data-placeholder="Choose Sub Categories" >
                                <!-- Filled dynamically -->
                            </select>
                            <span class="js-error"></span>
                        </div>
                    </div>
                    <div class="contact-section">
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">State:</label>
                        <div class="col-sm-9">
                            <select name="state" class="form-control state">
                                <option value="" selected disabled>Select State</option>
                            </select>
                            @error('state')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">District:</label>
                        <div class="col-sm-9">
                            <select name="district" class="form-control city vldt requd">
                                <option value="" selected disabled>Select District</option>
                            </select>
                            <span class="js-error"></span>
                            @error('city')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    </div>
                    <div class="row mb-3">
                        <label for="inputEnterYourName" class="col-sm-3 col-form-label">District Area:</label>
                        <div class="col-sm-9">
                            <input type="text" name="district_area" class="form-control vldt requd" id="inputEnterYourName" placeholder="Enter District Area" >
                            <span class="js-error"></span>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="location_address" class="col-sm-3 col-form-label">Location address:</label>
                        <div class="col-sm-9">
                            <textarea name="location_address" class="form-control vldt requd" id="location_address" rows="3" placeholder="Enter Location Address"></textarea>
                           <span class="js-error"></span>
                        </div>
                    </div>
                    

                    <div class="row">
                        <label class="col-sm-3 col-form-label"></label>
                        <div class="col-sm-9">
                            <button type="submit" class="btn btn-primary px-5">Create</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>

       <div class="col-xl-12">
            <div class="card thm-card">
            <div class="card-header thm-card-head  bg-gradient-danger d-flex flex-wrap align-items-center justify-content-between">
                <h5 class="card-title thm-card-title ms-0">Asset List<i class="fa fa-list ms-2"></i></h5>
                <!-- <a href="{{route('admin.dashboard')}}" class="btn btn-light btn-sm text-primary">Back<i class="fa fa-arrow-left ms-1"></i></a> -->
            </div>

            <div class="card-body thm-card-body">
                <div class="table-responsive">
                    <table class="table thm-tbl">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Hoarding ID</th>
                                <th>Category</th>
                                <th>Sub Category</th>
                                <th>State</th>
                                <th>District</th>
                                <th>District Area</th>
                                <th>Location Address</th>
                            </tr>
                        </thead>
                        <tbody>
						    @php $key = 1; @endphp
                            @foreach ($assets as $asset)
                            <tr>
                                <td>{{ $key }}</td>
                                <td>{{ $asset->hoarding_id }}</td>
                                <td>{{ $asset->Category->category ?? 'N/A' }}</td>
                                <td>{{ $asset->subCategory->sub_category ?? 'N/A' }}</td>                                
                                <td>{{$asset->state}}</td>
                                <td>{{$asset->district}}</td>
                                <td>{{$asset->district_area}}</td>
                                <td>{{$asset->location_address}}</td>
                            </tr>
                            @php $key++ @endphp
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
       </div>
    </div>

<script>
    document.getElementById('category_select').addEventListener('change', function () {
        const selectedOption = this.options[this.selectedIndex];
        const categoryName = selectedOption.getAttribute('data-name');
        document.getElementById('category_name').value = categoryName || '';
    });
</script>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    $('.multiple-select').select2({
        width: '100%',
        placeholder: $(this).data('placeholder')
    });

    $('#category-select').on('change', function () {
        let categoryId = $(this).val();

        if (!categoryId) {
            $('#subcategory-select').empty().append('<option value="">-- Select Subcategory --</option>');
            return;
        }

        $.ajax({
            url: '{{ route("get.subcategories") }}',
            type: 'POST',
            data: {
                category_ids: [categoryId],
                _token: '{{ csrf_token() }}'
            },
            success: function (response) {
                $('#subcategory-select').empty();
                if (response.length > 0) {
                    response.forEach(function (subcat) {
                        $('#subcategory-select').append(
                            `<option value="${subcat.id}">${subcat.sub_category}</option>`
                        );
                    });
                } else {
                    $('#subcategory-select').append('<option value="">No Subcategories Found</option>');
                }
            }
        });
    });
});
</script>
@endsection