@if(!empty($assets) && count($assets))
<form method="POST" action="{{ route('assign.campaign.final') }}">
    @csrf
    <input type="hidden" name="campaign_id" value="{{ request('campaign_id') }}">
    <table class="table table-bordered thm-tbl mt-4">
        <thead>
            <tr>
                <th><input type="checkbox" id="checkAll" class="align-middle"><span class="ms-2">Select All</span></th>
                <th>Hoarding ID</th>
                <th>Field Agent Name</th>
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
@else
<p>No hoardings found for the selected filters.</p>
@endif
