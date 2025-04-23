@extends('layout.main')
@section('content')
<div class="row">
    <div class="col-xl-9 mx-auto">
        <!--<h6 class="mb-0 text-uppercase">Create New Campaign</h6>-->
        <!--<hr />-->
        <div class="card thm-card">
            <div class="card-header bg-gradient-danger thm-card-head d-flex flex-wrap align-items-center justify-content-between">
                <h5 class="card-title thm-card-title ms-0">
                    Create New Campaign<i class="fa fa-plus-circle ms-2"></i>
                </h5>
                <a href="{{route('admin.dashboard')}}" class="btn btn-light btn-sm text-primary">Back<i class="fa fa-arrow-left ms-1"></i></a>
            </div>
            <div class="card-body thm-card-body">
                <div class="rounded">
                     <form action="{{route('store.campaignadmin')}}" method="POST" enctype="multipart/form-data"> <!-- Form tag -->
                        @csrf
                        <!-- Category Selection -->
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Select Categories:</label>
                            <div class="col-sm-9">
                                <div class="mb-2">
                                    <input type="checkbox" id="select-all-categories" class="form-check-input me-1">
                                    <label for="select-all-categories" class="form-check-label fw-bold">Select All Categories</label>
                                </div>
                                <div id="category-checkboxes">
                                    @foreach($categories as $cat)
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input category-checkbox" type="checkbox" name="category[]" value="{{ $cat->id }}" id="cat_{{ $cat->id }}"
                                                {{ in_array($cat->id, old('category', [])) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="cat_{{ $cat->id }}">{{ $cat->category }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Subcategory Selection -->
                        <div class="row mb-3">
                            <label class="col-sm-3 col-form-label">Select Sub-Categories:</label>
                            <div class="col-sm-9">
                                <select name="sub_category[]" id="subcategory-select" class="form-control multiSelect" data-placeholder="Choose Sub Categories" multiple="multiple" required>
                                   <option value="all">Select All</option>
                                </select>
                            </div>
                        </div>

                        <!-- Campaign Name -->
                        <div class="row mb-3">
                            <label for="decriptionNo2" class="col-sm-3 col-form-label">Campaign Name</label>
                            <div class="col-sm-9">
                                <input type="text" name="campaign_name" class="form-control" placeholder="Campaign Name" value="{{ old('campaign_name') }}" required>
                                @error('campaign_name')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Image Design Upload -->
                        <div class="row mb-3 image-upload-wrapper">
                            <label class="col-sm-3 col-form-label">Upload Image Design</label>
                            <div class="col-sm-9" id="image-upload-container">
                                <div class="image-upload-group mb-2 d-flex align-items-center">
                                    <input type="file" name="images[]" class="form-control me-2" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" onchange="previewImage(this)" required>
                                    <button type="button" class="btn btn-success add-image-btn">Add</button>
                                </div>
                                <div class="preview mt-2"></div>
                                @error('images')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>


                        <!-- PDF Design Upload -->
                        <div class="row mb-3 image-upload-wrapper">
                            <label class="col-sm-3 col-form-label">Upload Pdf Design</label>
                            <div class="col-sm-9">
                                <div class="image-upload-group mb-2 d-flex align-items-center">
                                    <input type="file" name="pdf[]" class="form-control me-2" accept="application/pdf" required>
                                    <button type="button" class="btn btn-success add-pdf-btn">Add</button>
                                </div>
                                @error('pdf')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Description (Quill Editor) -->
                        <div class="row mb-3">
                            <label for="description2" class="col-sm-3 col-form-label">Description</label>
                            <div class="col-sm-9">
                                <div id="quill-editor" style="height: 150px;">{{ old('description') }}</div>
                                <input type="hidden" name="description" id="description2">
                                @error('description')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Date Inputs -->
                        <div class="row mb-3">
                            <label for="decriptionNo2" class="col-sm-3 col-form-label">Start Date</label>
                            <div class="col-sm-9">
                                <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}" required>
                                @error('start_date')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="decriptionNo2" class="col-sm-3 col-form-label">End Date</label>
                            <div class="col-sm-9">
                                <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}" required>
                                @error('end_date')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="row">
                            <label class="col-sm-3 col-form-label"></label>
                            <div class="col-sm-9">
                                <button type="submit" class="btn btn-primary px-5">Create</button>
                            </div>
                        </div>
                    </form> <!-- Form tag -->
                </div>
            </div>
        </div>
    </div>
</div>
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<!-- Footer or before </body> -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    var quill = new Quill('#quill-editor', {
        theme: 'snow',
        placeholder: 'Description...',
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline'], 
                [{
                    'list': 'ordered'
                }, {
                    'list': 'bullet'
                }], 
                ['link'],
                [{
                    'align': []
                }], 
                ['clean'] 
            ]
        }
    });

    document.querySelector('form').addEventListener('submit', function() {
        document.querySelector('#description2').value = quill.root.innerHTML;
    });
</script>
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
    // Add new image upload input field
    $(document).on('click', '.add-image-btn', function () {
        const newImageUpload = `
            <div class="col-sm-9 ms-auto">
                <div class="image-upload-group mb-2 d-flex align-items-center">
                    <input type="file" name="images[]" class="form-control me-2" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" required onchange="previewImage(this)">
                    <button type="button" class="btn btn-danger remove-image-btn">×</button>
                </div>
                <div class="preview mt-2"></div>
            </div>
        `;
        $(this).closest('.image-upload-wrapper').append(newImageUpload);
    });
    
    // Preview image when file is selected
    function previewImage(input) {
        const previewBox = $(input).closest('.image-upload-group').next('.preview');
        previewBox.empty(); // Clear previous preview
    
        if (input.files && input.files[0]) {
            const reader = new FileReader();
    
            reader.onload = function (e) {
                const img = $('<img/>', {
                    src: e.target.result,
                    style: 'max-width: 200px; margin-top: 10px;',
                    class:'select-img-preview'
                });
                previewBox.append(img);
            };
    
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    // Remove the image input field
    $(document).on('click', '.remove-image-btn', function () {
        const group = $(this).closest('.image-upload-group');
        const preview = group.next('.preview');
        group.remove();
        preview.remove();
    });


    // Add new pdf upload input field
    $(document).on('click', '.add-pdf-btn', function () {
        const newPdfUpload = `
          <div class="col-sm-9 ms-auto">
            <div class="image-upload-group mb-2 d-flex align-items-center">
                <input type="file" name="pdf[]" class="form-control me-2" accept="application/pdf" required>
                <button type="button" class="btn btn-danger remove-pdf-btn">×</button>
            </div>
          </div>
        `;
        $(this).closest('.image-upload-wrapper').append(newPdfUpload);
    });

    // Remove image or pdf upload field
    $(document).on('click', '.remove-image-btn, .remove-pdf-btn', function () {
        $(this).closest('.image-upload-group').remove();
    });
</script>
<script>

$(document).ready(function() {
    $('.multiple-select').select2({
        width: '100%',
        placeholder: function(){
            $(this).data('placeholder');
        }
    });
});

    $(document).ready(function () {
    // Handle individual checkbox change
    $(document).on('change', '.category-checkbox', function () {
        updateSubcategories();

        // Uncheck "Select All" if any checkbox is unchecked
        if (!$('.category-checkbox').not(':checked').length) {
            $('#select-all-categories').prop('checked', true);
        } else {
            $('#select-all-categories').prop('checked', false);
        }
    });

    // Handle "Select All" checkbox
    $('#select-all-categories').on('change', function () {
        let checked = $(this).is(':checked');
        $('.category-checkbox').prop('checked', checked);
        updateSubcategories();
    });

    function updateSubcategories() {
    let selectedCategories = [];
    $('.category-checkbox:checked').each(function () {
        selectedCategories.push($(this).val());
    });

    if (selectedCategories.length === 0) {
        // Clear subcategories if no category is selected
        $('#subcategory-select').empty().append('<option value="">-- Select Subcategory --</option>');
        return;
    }

    // If categories are selected, fetch subcategories
    $.ajax({
        url: '{{ route("get.subcategories") }}',
        type: 'POST',
        data: {
            category_ids: selectedCategories,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            $('#subcategory-select').empty();
            if (response.length > 0) {
                 $('#subcategory-select').append(
                     `<option value="all">Select All</option>`
                 );
                response.forEach(function(subcat) {
                    $('#subcategory-select').append(
                        `<option value="${subcat.id}">${subcat.sub_category}</option>`
                    );
                });
            }
        }
    });
}

});

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
                <button type="button" class="btn btn-danger remove-contact-person"><i class="fa fa-minus"></i></button>
            </div>
        </div>`;
        $('#contact-person-wrapper').append(html);
    });

    $(document).on('click', '.remove-contact-person', function() {
        $(this).closest('.contact-person-group').remove();
    });
</script>

@endsection