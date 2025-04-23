@extends('layout.main')
@section('content')
<div class="row">
    <div class="col-xl-9 mx-auto">
        <!--<h6 class="mb-0 text-uppercase">Camapign Edit</h6>-->
        <!--<hr />-->
        <div class="card thm-card">
            <div class="card-header thm-card-head bg-gradient-danger d-flex flex-wrap align-items-center justify-content-between">
                <h5 class="card-title thm-card-title ms-0">Campaign Update<i class="fa fa-pencil ms-2"></i></h5>
                <a href="javascript:void(0)" class="btn btn-light btn-sm text-primary">Back<i class="fa fa-arrow-left ms-1"></i></a>
            </div>
            <div class="card-body thm-card-body">
                <div class="rounded">
                    <!--<div class="card-title d-flex align-items-center">-->
                    <!--    <h5 class="mb-0">Campaign Update</h5>-->
                    <!--</div>-->
                    <!--<hr />-->
                    @if(session('success'))
                    <div class="alert alert-success">{{ session('update_success') }}</div>
                    @endif
                    @if(session('error'))
                    <div class="alert alert-danger">{{ session('update_error') }}</div>
                    @endif



                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('campaign.update', $compaign->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') <!-- Added for updating the record -->

                        <div class="row mb-3">
                            <label for="agentName2" class="col-sm-3 col-form-label">Agent Name</label>
                            <div class="col-sm-9">
                                <input type="text" name="agent_name" value="{{ old('agent_name', $compaign->agent_name) }}" class="form-control" id="agentName2" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="images2" class="col-sm-3 col-form-label">Images</label>
                            <div class="col-sm-9">
                                <input type="file" name="images[]" class="form-control" id="images2" multiple required>
                                @php
                                $images = is_array($compaign->images) ? $compaign->images : json_decode($compaign->images, true);
                                @endphp

                                @if(!empty($images))
                                @foreach($images as $image)
                                <img src="{{ asset('uploads/images/' . $image) }}" alt="Uploaded Image" width="30" class="me-2">
                                @endforeach
                                @endif


                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="PDFNo2" class="col-sm-3 col-form-label">PDF File</label>
                            <div class="col-sm-9">
                                <input type="file" name="pdf" class="form-control" id="PDFNo2" required>
                                @if(!empty($compaign->pdf))
                                <div class="mt-2">
                                    <a href="{{ asset('uploads/pdf/' . $compaign->pdf) }}" target="_blank" class="btn btn-outline-primary">View Uploaded PDF<i class="fa fa-file-pdf-o ms-2"></i></a>
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="descriptionNo2" class="col-sm-3 col-form-label">Description</label>
                            <div class="col-sm-9">
                                <input type="text" name="description" value="{{ old('description', $compaign->description) }}" class="form-control" id="descriptionNo2" placeholder="description" required>
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