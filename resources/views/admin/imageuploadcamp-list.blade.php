@extends('layout.main')
@section('content')
<div class="card thm-card">
    <div class="card-header thm-card-head bg-gradient-danger d-flex flex-wrap align-items-center justify-content-between">
        <h5 class="card-title thm-card-title ms-0">
            Images Upload By Field Agent<i class="fa fa-image ms-2"></i>
        </h5>
        <a href="{{route('admin.dashboard')}}" class="btn btn-light btn-sm text-primary">Back<i class="fa fa-arrow-left ms-1"></i></a>
    </div>
    <div class="card-body thm-card-body">
        <div class="d-flex align-items-center">
            <!--<h5 class="mb-0">Images Upload By Field Agent</h5>-->
            {{-- ------For delete---- --}}
            @if(session('success_delete'))
            <div class="alert alert-success">
                {{ session('success_delete') }}
            </div>
            @endif

            @if(session('delete_error'))
            <div class="alert alert-danger">
                {{ session('delete_error') }}
            </div>
            @endif
            {{-- ------for status update---- --}}
            @if(session('success'))
            <script>
                Swal.fire({
                    icon: "success",
                    title: "Success!",
                    text: "{{ session('success') }}",
                    timer: 2000,
                    showConfirmButton: false
                });
            </script>
            @endif

            @if(session('error'))
            <script>
                Swal.fire({
                    icon: "error",
                    title: "Error!",
                    text: "{{ session('error') }}",
                    timer: 2000,
                    showConfirmButton: false
                });
            </script>
            @endif


            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form class="ms-auto position-relative">
                <div class="position-absolute top-50 translate-middle-y search-icon px-3"><i class="bi bi-search"></i></div>
                <input class="form-control ps-5" type="text" placeholder="search">
            </form>
        </div>
        <div class="table-responsive mt-3">
            <table class="table align-middle thm-tbl">
                <thead class="text-center">
                    <tr>
                        <th>Field Agent Name</th>
                        <th>Campaign Images</th>
                        <!--<th>Date</th>-->
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @foreach($imageuploadcamps as $imageuploadcamp)
                    <tr>
                        <td>{{ $imageuploadcamp->fieldagent->name ?? 'N/A' }}</td>
                        @php
                        $images=\App\Models\ImageUploadCampaignModel::where('fieldagent_id',$imageuploadcamp->fieldagent_id)
                        ->whereMonth('date',now('Asia/Kolkata')->month)
                        ->limit(3)
                        ->get();
                        @endphp
                        @php
                        $printedFieldAgents = [];
                        @endphp
                        <td>
                            <div class="d-flex align-items-center justify-content-center">
                                @foreach($images as $img)
                                <div style="position:relative; text-align: center; margin:10px; border:1px solid lightgrey; box-shadow:2px 2px 3px grey; border-radius:5px;padding:8px;">
                                    <img src="{{ asset('public/uploads/' . basename($img->image)) }}" class="mb-3" width="80">
                                    <p class="my-auto">longtitude: {{ $img->longtitude}}</p>
                                    <p class="my-auto">Latitude: {{ $img->latitude }}</p>
                                    <p class="my-auto">Date: {{ $img->date}}</p>
                                </div>
                                @endforeach
                            </div>
                        </td>
                        <td>
                            <div class="">
                                @if(!in_array($img->fieldagent_id, $printedFieldAgents))
                                <div class="pdf" style="" class="d-flex align-items-center">
                                    <form action="{{ route('download.monthly.images.pdf') }}" method="GET">
                                        <input type="hidden" name="fieldagent_id" value="{{ $img->fieldagent_id }}">
                                        <input type="hidden" name="download_pdf" value="1">
                                        <button type="submit" class="btn btn-primary pdf-download">Download PDF<i class="fa fa-download ms-2"></i></button>
                                    </form>
                                </div>
                            </div>
                        </td>
                        @php
                        $printedFieldAgents[] = $img->fieldagent_id; // Mark this agent's button printed
                        @endphp
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @php
            $imageuploadcamp = App\Models\ImageUploadCampaignModel::select('fieldagent_id')->first();
            @endphp
        </div>
    </div>
</div>
@endsection