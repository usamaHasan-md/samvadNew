@extends('layout.main')
@section('content')
<div class="row">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    <div class="col-xl-9 mx-auto">
        <!--<h6 class="mb-0 text-uppercase">Create New Vendor</h6>-->
        <!--<hr />-->
        <div class="card thm-card">
            <div class="card-header bg-gradient-danger thm-card-head d-flex flex-wrap align-items-center justify-content-between">
                <h5 class="card-title thm-card-title ms-0">Add New Vendor<i class="fa fa-user-plus ms-2"></i></h5>
                <a href="{{route('admin.dashboard')}}" class="btn btn-light btn-sm text-primary">Back<i class="fa fa-arrow-left ms-1"></i></a>
            </div>
            <div class="card-body thm-card-body">
                <div class="rounded">
                    <form action="{{route('store.vendor')}}" method="POST"> <!-- Added form tag -->
                        @csrf
                        <!-- Hoarding Selection -->
                        {{-- <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Select Hoarding IDs:</label>
                            <div class="col-sm-9">
                                <select name="assigned_hoarding_id[]" id="hoarding" class="form-control selectpicker" multiple data-live-search="true" title="-- Select Hoardings --" data-selected-text-format="count > 4">
                                    <option value="Not applicable">Not Applicable</option>
                                    <option value="all">Select All</option>
                                    @foreach($hoarding_ids as $hid)
                                    <option value="{{ $hid }}">{{ $hid }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> --}}

                        <!-- Display-only for Category and Subcategory -->
                       
                       <!-- Hoarding IDs -->
                   <div class="row mb-3">
                       <label class="col-sm-3 col-form-label">Select Hoarding IDs:</label>
                       <div class="col-sm-9">
                           <select name="assigned_hoarding_id[]" id="hoarding" class="form-control selectpicker" multiple data-live-search="true" title="-- Select Hoardings --" data-selected-text-format="count > 4">
                               <option value="Not applicable">Not Applicable</option>
                               <option value="all">Select All</option>
                               @foreach($hoarding_ids as $hid)
                                   <option class="hoarding-opt" value="{{ $hid }}">{{ $hid }}</option>
                               @endforeach
                           </select>
                       </div>
                   </div>
        
                <!-- Category Section -->
                <div class="row mb-3" id="category-row">
                    <label class="col-sm-3 col-form-label">Categories:</label>
                    <div class="col-sm-9" id="category-box">
                        <select class="form-select multiSelect" name="category[]" id="cat-select" multiple>
                         <!-- Options will be filled dynamically if needed -->
                        </select>
                    </div>
                </div>
                        
                <!-- Subcategory Section -->
                <div class="row mb-3" id="subcategory-row">
                    <label class="col-sm-3 col-form-label">Sub-Categories:</label>
                    <div class="col-sm-9" id="subcategory-box">
                        <select class="form-select multiSelect" name="sub_category[]" id="subcat-select" multiple>
                            <!-- Options will be filled dynamically if needed -->
                        </select>
                    </div>
                </div>
                        <div class="row mb-3">
                            <label for="inputEnterYourName" class="col-sm-3 col-form-label">Vendor Name:</label>
                            <div class="col-sm-9">
                                <input type="text" name="name" class="form-control" id="inputEnterYourName" value="{{ old('name') }}" placeholder="Enter Vendor Name" required>
                                @error('name')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputEmailAddress2" class="col-sm-3 col-form-label">Email Address:</label>
                            <div class="col-sm-9">
                                <input type="email" name="email" class="form-control" id="inputEmailAddress2" value="{{ old('email') }}" placeholder="Email Address" required>
                                @error('email')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Contact:</label>
                            <div class="col-sm-9">
                                <div>
                                    <div class="input-group mb-2">
                                        <input type="text" name="contact" class="form-control" value="{{ old('contact') }}" placeholder="Phone No" required>
                                    </div>
                                </div>
                                @error('contact')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Contact Person:</label>
                            <div class="col-sm-9">
                                <div id="contact-person-wrapper">
                                    @if(old('contact_person_name') && old('contact_person_number'))
                                    @foreach(old('contact_person_name') as $i => $name)
                                    <div class="row mb-2 contact-person-group">
                                        <div class="col-md-5">
                                            <input type="text" name="contact_person_name[]" class="form-control" placeholder="Contact Person Name" value="{{ $name }}">
                                        </div>
                                        <div class="col-md-5">
                                            <input type="text" name="contact_person_number[]" class="form-control" placeholder="Contact Person Number" value="{{ old('contact_person_number')[$i] }}">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-success add-contact-person"><i class="fa fa-plus"></i></button>
                                        </div>
                                    </div>
                                    @endforeach
                                    @else
                                    {{-- Default one row --}}
                                    <div class="row mb-2 contact-person-group">
                                        <div class="col-md-5">
                                            <input type="text" name="contact_person_name[]" class="form-control" placeholder="Contact Person Name">
                                        </div>
                                        <div class="col-md-5">
                                            <input type="text" name="contact_person_number[]" class="form-control" placeholder="Contact Person Number">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-success add-contact-person"><i class="fa fa-plus mx-auto"></i></button>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="contact-section">
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">State:</label>
                                <div class="col-sm-9">
                                    <select name="state" class="form-control state" required>
                                        <option value="" selected disabled>Select State</option>
                                    </select>
                                    @error('state')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label class="col-sm-3 col-form-label">City:</label>
                                <div class="col-sm-9">
                                    <select name="city" class="form-control city" required>
                                        <option value="" selected disabled>Select City</option>
                                    </select>
                                    @error('city')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="role" id="inputRoleNo2" value="vendor">
                        <div class="row mb-3">
                            <label for="password2" class="col-sm-3 col-form-label">Password:</label>
                            <div class="col-sm-9">
                                <div class="position-relative">
                                    <input type="password" name="password" class="form-control" id="password2" placeholder="Enter Password" required>
                                    <i class="fa fa-eye toggle-password position-absolute top-50 end-0 translate-middle-y me-3" data-target="password2" style="cursor: pointer;"></i>
                                    @error('password')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="password_confirmation" class="col-sm-3 col-form-label">Confirm Password:</label>
                            <div class="col-sm-9">
                                <div class="position-relative">
                                    <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Confirm Password" required>
                                    <i class="fa fa-eye toggle-password position-absolute top-50 end-0 translate-middle-y me-3" data-target="password_confirmation" style="cursor: pointer;"></i>
                                    @error('password_confirmation')
                                    <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-3 col-form-label"></label>
                            <div class="col-sm-9">
                                <button type="submit" class="btn btn-primary px-5">Create</button>
                            </div>
                        </div>
                    </form> <!-- Form tag closed here -->
                </div>
            </div>
        </div>
    </div>
</div>
<!--end row-->
<script>
    document.querySelectorAll('.toggle-password').forEach(function(icon) {
        icon.addEventListener('click', function() {
            const inputId = this.getAttribute('data-target');
            const input = document.getElementById(inputId);
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    });
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.multiple-select').select2({
            width: '100%'
        });

       $('#hoarding').change(function() {
    var hoardings = $(this).val();

    if (hoardings.length > 0) {
        $.ajax({
            url: "{{ route('get.categories.by.hoarding') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                hoardings: hoardings
            },
            success: function(response) {
                var categories = response.categories;
                var subcategories = response.subcategories;

                // Remove duplicates
                var uniqueCategories = [];
                var uniqueSubcategories = [];

                categories.forEach(function(category) {
                    if (!uniqueCategories.some(c => c.id === category.id)) {
                        uniqueCategories.push(category);
                    }
                });

                subcategories.forEach(function(subcategory) {
                    if (!uniqueSubcategories.some(s => s.id === subcategory.id)) {
                        uniqueSubcategories.push(subcategory);
                    }
                });

                // Clear old options
                $('#cat-select').empty();
                $('#subcat-select').empty(); // if you're doing similar for subcategory

                // Append new options
                uniqueCategories.forEach(function(category) {
                    $('#cat-select').append(
                        '<option value="' + category.id + '" selected>' + category.name + '</option>'
                    );
                });

                uniqueSubcategories.forEach(function(subcategory) {
                    $('#subcat-select').append(
                        '<option value="' + subcategory.id + '" selected>' + subcategory.name + '</option>'
                    );
                });

                $('#cat-select').trigger('change');
                $('#subcat-select').trigger('change');
            },
            error: function(xhr, status, error) {
                console.error("Error occurred: " + error);
                alert("An error occurred while fetching data.");
            }
        });
    } else {
        // Clear selects if no hoardings selected
        $('#cat-select').empty().trigger('change');
        $('#subcat-select').empty().trigger('change');
    }
});

        // Handle adding contact person
        $(document).on('click', '.add-contact-person', function() {
            const html = `
            <div class="row mb-2 contact-person-group">
                <div class="col-md-5">
                    <input type="text" name="contact_person_name[]" class="form-control" placeholder="Contact Person Name" required>
                </div>
                <div class="col-md-5">
                    <input type="text" name="contact_person_number[]" class="form-control" placeholder="Contact Person Number" required>
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger remove-contact-person"><i class="fa fa-minus mx-auto"></i></button>
                </div>
            </div>`;
            $('#contact-person-wrapper').append(html);
        });

        // Handle removing contact person
        $(document).on('click', '.remove-contact-person', function() {
            $(this).closest('.contact-person-group').remove();
        });
    });
</script>
<script>
    $(document).ready(function () {
        $('#hoarding').on('changed.bs.select', function () {
            let selected = $(this).val();

            if (selected && selected.includes('Not applicable')) {
                // Deselect all other options except 'Not applicable'
                $('#hoarding').selectpicker('val', ['Not applicable']);

                // Hide other hoarding options including 'Select All'
                $('.hoarding-opt').hide(); // hide hoarding options
                $('#hoarding option[value="all"]').hide(); // hide 'Select All' option
                $('#hoarding').selectpicker('refresh');

                // Replace dropdowns with input fields
                $('#category-box').html(`
                    <input type="text" class="form-control" name="category[]" placeholder="Enter Category" />
                `);
                $('#subcategory-box').html(`
                    <input type="text" class="form-control" name="sub_category[]" placeholder="Enter Sub-Category" />
                `);
            } else {
                // Show all options
                $('.hoarding-opt').show();
                $('#hoarding option[value="all"]').show(); // show 'Select All'
                $('#hoarding').selectpicker('refresh');

                // Restore original selects
                $('#category-box').html(`
                    <select class="form-select multiSelect" name="category[]" id="cat-select" multiple></select>
                `);
                $('#subcategory-box').html(`
                    <select class="form-select multiSelect" name="sub_category[]" id="subcat-select" multiple></select>
                `);
            }
        });
    });
</script>


@endsection