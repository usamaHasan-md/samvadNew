@extends('layout.main')
@section('content')
<div class="row">
    <div class="col-xl-9 mx-auto">
        <div class="card thm-card">
            <div class="card-header thm-card-head bg-gradient-danger d-flex flex-wrap align-items-center justify-content-between">
                <h5 class="card-title thm-card-title ms-0">Campaign Report <i class="fa fa-list ms-2"></i></h5>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-light btn-sm text-primary">Back<i class="fa fa-arrow-left ms-1"></i></a>
            </div>
            <div class="card-body thm-card-body">
                <form class="contact-section" id="filterForm">
                    <div class="col-md-12">
                        <label for="validationDefault01" class="form-label">Select Campaign:<span class="text-danger">*</span></label>
                        <select name="filter[campaign_id][]" class="form-control selectpicker" multiple data-live-search="true" title="-- Select Campaign --" data-selected-text-format="count > 4">
                            @foreach ($campaigns as $campaign)
                            <option value="{{ $campaign->id }}" {{ request('campaign_id') == $campaign->id ? 'selected' : '' }}>{{ $campaign->campaign_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="validationDefault01" class="form-label">Hoarding ID</label>
                            <select name="filter[hoarding_id][]" class="form-control selectpicker" multiple data-live-search="true" title="-- Select Hoardings --" data-selected-text-format="count > 4">
                                <option value="" disabled>-- Select Hoarding ID --</option>
                                @foreach ($hoardingIds as $id)
                                <option value="{{ $id }}" {{ in_array($id, request('filter.hoarding_id', [])) ? 'selected' : '' }}>{{ $id }}</option>
                                @endforeach
                            </select>
                        </div>
                        @php
                            $selectedCategory = request('filter.category');
                            $selectedCategory = is_array($selectedCategory) ? ($selectedCategory[0] ?? '') : $selectedCategory;
                        
                            $selectedSubCategory = request('filter.sub_category');
                            $selectedSubCategory = is_array($selectedSubCategory) ? ($selectedSubCategory[0] ?? '') : $selectedSubCategory;
                        @endphp
                        
                        <div class="col-md-6">
                            <label for="validationDefault01" class="form-label">Select Category</label>
                            <select name="filter[category]" class="form-select">
                                <option value="">-- Select Category --</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $selectedCategory == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->category }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="col-md-6">
                            <label for="validationDefault02" class="form-label">Select Sub Category</label>
                            <select name="filter[sub_category]" class="form-select">
                                <option value="">-- Select Sub Category --</option>
                                @foreach ($subCategories as $sub)
                                    <option value="{{ $sub->id }}" {{ $selectedSubCategory == $sub->id ? 'selected' : '' }}>
                                        {{ $sub->sub_category }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="validationDefault02" class="form-label">Select State</label>
                            <select name="filter[state]" class="form-select state">
                                <option value="">-- Select State --</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="validationDefault02" class="form-label">District</label>
                            <select name="filter[district]" class="form-select city">
                                <option value="">-- Select District --</option>
                            </select>
                        </div>
                        @php
                            $district = request('filter.district');
                            $districtValue = is_array($district) ? ($district[0] ?? '') : $district;
                        @endphp
                        <div class="col-md-6">
                            <label for="validationDefault02" class="form-label">District Area</label>
                            <input type="text" name="filter[district]" class="form-control" value="{{ $districtValue }}" placeholder="District Area">
                        </div>
                        <div class="col-md-6">
                            <label for="validationDefault02" class="form-label">Location Address</label>
                            <input type="text" name="filter[location_address]" class="form-control" placeholder="Location Address">
                        </div>
                    </div>
                    <!--<div class="col-12">
                        <button class="btn btn-primary" name="search" value="1">Filter<i class="fa fa-filter ms-2"></i></button>
                    </div>-->
                </form>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 mx-auto">
        <div class="card thm-card">
            <div class="thm-card-head bg-gradient-danger card-header d-flex align-items-center justify-content-between">
                <h5 class="card-title thm-card-title ms-0">Campaign Details <i class="fa fa-info ms-2"></i></h5>
                <div>
                    <button class="btn btn-sm btn-light text-primary" id="exportBtn">Export To Excel <i class="fa fa-download ms-2"></i></button>
				    <button class="btn btn-sm btn-light text-primary" id="exportPdfBtn">Export To PDF <i class="fa fa-download ms-2"></i></button>
                </div>
            </div>
            <div class="card-body thm-card-body">
                <div class="table-responsive" id="reportTable">
                    <table class="table table-bordered thm-tbl">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Hoarding ID</th>
                                <th>Field Agent Name</th>
                                <th>Campaign Name</th>
                                <th>Category</th>
                                <th>Sub Category</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>State</th>
                                <th>District</th>
                                <th>District Area</th>
                                <th>Location Address</th>
                                <th>Images</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            @include('partials.fieldagent_table_body')
                        </tbody>
                    </table>
                    <!-- <button type="submit" class="btn btn-primary">Assign Selected</button> -->
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    $('#filterForm :input').on('change', function () {
        let formData = $('#filterForm').serialize();
        console.log("Form data:", formData);

        $.ajax({
            url: '{{ route('report.campaignWise') }}',
            method: 'GET',
            data: formData,
            success: function (res) {
                $('#tableBody').html(res.html);
            },
            error: function (xhr) {
                console.error("Error:", xhr.responseText);
            }
        });
    });
});
</script>
<!-- Excel Export Script -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
    document.getElementById('exportPdfBtn').addEventListener('click', function () {
        const element = document.getElementById('reportTable');

        var opt = {
            margin:       0.5,
            filename:     'Campaign_Report.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2 },
            jsPDF:        { unit: 'in', format: 'a4', orientation: 'landscape' }
        };

        html2pdf().from(element).set(opt).save();
    });
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
    document.getElementById('exportBtn').addEventListener('click', function () {
        let table = document.getElementById('reportTable');
        let workbook = XLSX.utils.table_to_book(table, { sheet: "Report" });
        XLSX.writeFile(workbook, 'Campaign_Report.xlsx');
    });
</script>
@endsection
