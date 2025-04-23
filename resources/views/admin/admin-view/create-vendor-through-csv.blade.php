@extends('layout.main')
@section('content')
<div class="row">
    <div class="col-xl-9 mx-auto">
        <!--<h6 class="mb-0 text-uppercase">Create Vendor Through CSV</h6>-->
        <!--<hr />-->
      
        <div class="card thm-card">
            <div class="card-header thm-card-head d-flex flex-wrap align-items-center justify-content-between">
                <h5 class="card-title thm-card-title  mb-sm-0 mb-3 m-0">
                    Create Vendor Through CSV<i class="fa fa-user-plus ms-2"></i>
                </h5>
                <a href="{{ route('vendors.downloadCsvSample') }}" class="btn btn-outline-light">
                     Download CSV Format<i class="fa fa-download ms-2"></i>
                </a>
            </div>
            <div class="card-body thm-card-body">
                <div class="rounded">
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

                    <div>
                        <form action="{{ route('vendorsCsv.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                             <div class="col-12 row mx-auto">
                               <div class="col-md-8 my-2">
                                    <input type="file" name="csv_file" accept=".csv" required class="form-control ">
                               </div>
                               <div class="col-md-auto my-2">
                                    <button type="submit" class="btn btn-success">Upload CSV<i class="fa fa-upload ms-2"></i></button>
                               </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end row-->
@endsection