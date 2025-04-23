@extends('layout.main')
@section('content')
<div class="row">
    <div class="col-xl-9 mx-auto">
        <!--<h6 class="mb-0 text-uppercase">Campaign Edit</h6>-->
        <!--<hr />-->
        <div class="card thm-card">
            <div class="thm-card-head card-head bg-gradient-danger d-flex flex-wrap align-items-center justify-content-between">
                <h5 class="thm-card-title card-title ms-0">
                   Campaign Update<i class="fa fa-pencil ms-2"></i>
                </h5>
                <a href="{{route('list.campaignadmin')}}" class="btn btn-light btn-sm text-primary">Back<i class="fa fa-arrow-left ms-1"></i></a>
            </div>
            <div class="card-body thm-card-body">
                <div class="rounded">
                    <!--<div class="card-title d-flex align-items-center">-->
                    <!--    <h5 class="mb-0">Campaign Update</h5>-->
                    <!--</div>-->
                    <!--<hr />-->
                    <form action="{{ route('campaignUpdate.admin', $EditByAdmins->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') <!-- Added for updating the record -->
                        <div class="row mb-3">
                            <label for="agentName2" class="col-sm-3 col-form-label">Campaign Name</label>
                            <div class="col-sm-9">
                                <input type="text" name="campaign_name" value="{{ old('campaign_name', $EditByAdmins->campaign_name) }}" class="form-control" id="agentName2" required>
                                @error('campaign_name')
                                     <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="images2" class="col-sm-3 col-form-label">Images</label>
                            <div class="col-sm-9">
                                <input type="file" name="images[]" class="form-control" accept="image/*" id="images2" multiple>
                                @error('images')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                @php
                                $images = is_array($EditByAdmins->images) ? $EditByAdmins->images : json_decode($EditByAdmins->images, true);
                                @endphp
                                @if(!empty($images))
                                @foreach($images as $image)
                                <img src="{{ asset('public/' . $image) }}" alt="Uploaded Image" class="me-2 select-img-preview">
                                @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="PDFNo2" class="col-sm-3 col-form-label">PDF File</label>
                            <div class="col-sm-9">
                                <input type="file" name="pdf" accept="application/pdf" class="form-control" id="PDFNo2">
                                @error('pdf')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                                @if(!empty($EditByAdmins->pdf))
                                <div class="mt-2">
                                    <a href="{{ asset('public/' . $EditByAdmins->pdf) }}" target="_blank" class="btn btn-sm btn-outline-primary">View Uploaded PDF<i class="fa fa-file-pdf-o ms-1"></i></a>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="descriptionNo2" class="col-sm-3 col-form-label">Description</label>
                            <div class="col-sm-9">
                                <textarea type="text" name="description" class="form-control" id="descriptionNo2" placeholder="description" required>{{ old('description', $EditByAdmins->description) }}</textarea>
                                @error('description')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="decriptionNo2" class="col-sm-3 col-form-label">Start Date</label>
                            <div class="col-sm-9">
                                <input type="date" name="start_date" value="{{ old('description', $EditByAdmins->start_date) }}" class="form-control" id="description2" placeholder="Start Date" required>
                                @error('start_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="decriptionNo2" class="col-sm-3 col-form-label">End Date</label>
                            <div class="col-sm-9">
                                <input type="date" name="end_date" value="{{ old('description', $EditByAdmins->end_date) }}" class="form-control" id="description2" placeholder="End Date" required>
                                @error('end_date')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-3 col-form-label"></label>
                            <div class="col-sm-9">
                                <button type="submit" class="btn btn-primary px-5">Update</button>
                            </div>
                        </div>
                    </form>
                    <!-- Form tag closed here -->
                </div>
            </div>
        </div>
    </div>
</div>
<!--end row-->
@endsection