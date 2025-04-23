@extends('layout.main')
@section('content')
<div class="row">
    <div class="col-xl-9 mx-auto">
        <div class="card thm-card">
            <div class="card-header thm-card-head bg-gradient-danger d-flex flex-wrap align-items-center justify-content-between">
                <h5 class="card-title thm-card-title ms-0">Assign Campaign <i class="fa fa-list ms-2"></i></h5>
                <a href="{{route('admin.dashboard')}}" class="btn btn-light btn-sm text-primary">Back<i class="fa fa-arrow-left ms-1"></i></a>
            </div>
            
            <div class="card-body thm-card-body">
                <form method="GET" action="{{ route('campaign.assign.filter') }}" class="row g-3 contact-section">
                    <div class="col-md-12">
                        <label for="validationDefault01" class="form-label">Select Campaign:<span class="text-danger">*</span></label>
                        <select name="campaign_id" class="form-control" required>
                            <option value="">-- Select Campaign --</option>
                            @foreach ($campaigns as $campaign)
                            <option value="{{ $campaign->id }}" {{ request('campaign_id') == $campaign->id ? 'selected' : '' }}>{{ $campaign->campaign_name }}</option>
                            @endforeach
                            
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label for="validationDefault01" class="form-label">Hoarding ID</label>
                        <select name="filter[hoarding_id][]" class="form-control selectpicker" multiple data-live-search="true" title="-- Select Hoardings --" data-selected-text-format="count > 4">
                            <option value="" disabled>-- Select Hoarding ID --</option>
                            @foreach ($hoardingIds as $id)
                                <option value="{{ $id }}" {{ in_array($id, request('filter.hoarding_id', [])) ? 'selected' : '' }}>{{ $id }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault01" class="form-label">Select Category</label>
                        <select name="filter[category]" class="form-select">
                            <option value="">-- Select Category --</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('filter.category') == $cat->id ? 'selected' : '' }}>
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
                                <option value="{{ $sub->id }}" {{ request('filter.sub_category') == $sub->id ? 'selected' : '' }}>
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
                    
                    <div class="col-md-6">
                        <label for="validationDefault02" class="form-label">District Area</label>
                        <input type="text" name="filter[district_area]" class="form-control" placeholder="District Area">
                    </div>
                    <div class="col-md-6">
                        <label for="validationDefault02" class="form-label">Location Address</label>
                        <input type="text" name="filter[location_address]" class="form-control" placeholder="Location Address">
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary" name="search" value="1">Filter<i class="fa fa-filter ms-2"></i></button>
                    </div>
                    <!--<div class="col-12">-->
                    <!--    <div class="form-check">-->
                    <!--        <input class="form-check-input" type="checkbox" value="" id="invalidCheck2" required>-->
                    <!--        <label class="form-check-label" for="invalidCheck2">Select All Vendors</label>-->
                    <!--    </div>-->
                    <!--</div>-->

                    <!--<div class="col-12">-->
                    <!--    <button class="btn btn-primary" type="submit">Assign Vendor</button>-->
                    <!--</div>-->
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row">
  <div class="col-lg-12 mx-auto">
      <div class="card thm-card">
          <div class="thm-card-head bg-gradient-danger card-header">
              <h5 class="card-title thm-card-title">Hoarding Details<i class="fa fa-list ms-2"></i></h5>
          </div>
          <div class="card-body thm-card-body">
                <!-- Result Table -->
                @if(!empty($assets) && count($assets))
                <form method="POST" action="{{ route('assign.campaign.final') }}">
                    @csrf
                    <input type="hidden" name="campaign_id" value="{{ request('campaign_id') }}">
            
                    <table class="table table-bordered thm-tbl mt-4">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="checkAll" class="align-middle"><span class="ms-2">Select All</span></th>
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
                            @foreach($assets as $asset)
                            <tr>
                                <td><input type="checkbox" name="hoarding_ids[]" value="{{ $asset->hoarding_id }}"></td>
                                <td>{{ $asset->hoarding_id }}</td>
                                <td>{{ $asset->categoryData->category }}</td>
                                <td>{{ $asset->subCategoryData->sub_category }}</td>
                                <td>{{ $asset->state }}</td>
                                <td>{{ $asset->district }}</td>
                                <td>{{ $asset->district_area }}</td>
                                <td>{{ $asset->location_address }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button type="submit" class="btn btn-primary">Assign Selected</button>
                </form>
                @endif
          </div>
      </div>
      
  </div>
</div>
<script>
    document.getElementById('checkAll')?.addEventListener('click', function() {
        document.querySelectorAll('input[name="hoarding_ids[]"]').forEach(cb => cb.checked = this.checked);
    });
</script>
@endsection


