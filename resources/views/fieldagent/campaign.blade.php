@extends('layout.main')
@section('content')
<div class="row">
    <div class="col-xl-9 mx-auto">
        <!--<h6 class="mb-0 text-uppercase">Create Compaign</h6>-->
        <!--<hr />-->
        <div class="card thm-card">
            <div class="card-header thm-card-head bg-gradient-danger d-flex flex-wrap align-items-center justify-content-between">
                <h5 class="card-title thm-card-title ms-0">Compaign Registration<i class="fa fa-bullhorn ms-2"></i></h5>
                <a href="javascript:void(0)" class="btn btn-light btn-sm text-primary">Back<i class="fa fa-arrow-left ms-1"></i></a>
            </div>
            <div class="card-body thm-card-body">
                <div class="rounded">
                    <!--<div class="card-title d-flex align-items-center">-->
                    <!--    <h5 class="mb-0">Compaign Registration</h5>-->
                    <!--</div>-->
                    <!--<hr />-->
                    @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
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


                    <form action="{{route('campaign.store')}}" method="POST" enctype="multipart/form-data"> <!-- Added form tag -->
                        @csrf
                        <div class="row mb-3">
                            <label for="inputEnterAgentName" class="col-sm-3 col-form-label">Agent Name</label>
                            <div class="col-sm-9">
                                <select name="agent_name" class="form-control" id="inputEnterYourName" placeholder="Enter User Name" required>
                                    <option value="hidden">Not Selected</option>
                                    @foreach($campaignpages as $campaign)
                                    <option value="{{ $campaign->agent_name}}">{{$campaign->agent_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="imageFile2" class="col-sm-3 col-form-label">Image File</label>
                            <div class="col-sm-9">
                                <input type="file" name="images[]" class="form-control" id="imageFile2" placeholder="Image File" multiple required>
                            </div>
                        </div>


                        <div class="row mb-3">
                            <label for="pdfFile2" class="col-sm-3 col-form-label">Pdf File</label>
                            <div class="col-sm-9">
                                <input type="file" name="pdf" class="form-control" id="pdfFile2" placeholder="PDF File" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="decriptionNo2" class="col-sm-3 col-form-label">Description</label>
                            <div class="col-sm-9">
                                <input type="text" name="description" class="form-control" id="description2" placeholder="Description" required>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-3 col-form-label"></label>
                            <div class="col-sm-9">
                                <button type="submit" class="btn btn-primary px-5">Register</button>
                            </div>
                        </div>
                    </form> <!-- Form tag closed here -->

                </div>
            </div>
        </div>
    </div>
</div>
<!--end row-->
@endsection