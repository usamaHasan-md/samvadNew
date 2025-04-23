@extends('layout.main')

@section('content')

@if(session('success_delete'))
<div class="alert alert-success">{{ session('success_delete') }}</div>
@endif

@if(session('delete_error'))
<div class="alert alert-danger">{{ session('delete_error') }}</div>
@endif

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

<div class="card thm-card">
    <div class="card-header thm-card-head d-flex align-items-center justify-content-between">
        <h5 class="card-title thm-card-title ms-0">Campaign List <i class="fa fa-list ms-2"></i></h5>
        <a href="{{ route('fieldagent.dashboard') }}" class="btn btn-light text-primary btn-sm">Back <i class="fa fa-arrow-left ms-1"></i></a>
    </div>
    <div class="card-body thm-card-body">

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.26/webcam.min.js"></script>

        <div class="table-responsive mt-3">
            <table class="table align-middle thm-tbl" id="example1">
                <thead class="text-center">
                    <tr>
                        <th>Sr. No</th>
                        <th>Campaign Name</th>
                        <th>Images</th>
                        <th>Hoarding ID</th>
                        <th>Upload Image</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    @php $sr = 1; @endphp
                    @foreach($campaignlistToFieldagents as $campaign)
                        @php $hoarding_ids = $campaign->fieldAgents->pluck('pivot.hoarding_id')->unique(); @endphp

                        @foreach($hoarding_ids as $hoarding_id)
                            @php $uniqueId = $campaign->id . '_' . $hoarding_id; @endphp

                            <tr>
                                <td>{{ $sr++ }}</td>
                                <td>{{ $campaign->campaign_name }}</td>
                                <td>
                                    @php $images = json_decode($campaign->images); @endphp
                                    @if(!empty($images))
                                        @foreach($images as $image)
                                            <img src="{{ asset('public/' . $image) }}" alt="Uploaded Image" style="height: 80px;">
                                        @endforeach
                                    @else
                                        No Image
                                    @endif
                                </td>
                                <td>{{ $hoarding_id }}</td>
                                <td>
                                    <button onclick="openCamera('{{ $uniqueId }}')" class="btn btn-warning me-2" style="font-size:13px;">
                                        Open Camera <i class="fa fa-camera ms-1"></i>
                                    </button>

                                    <button onclick="takeSnapshot('{{ $uniqueId }}')" class="btn btn-danger" style="font-size:13px;">
                                        Capture Image <i class="fa fa-picture-o ms-1"></i>
                                    </button>

                                    <div id="cameraContainer{{ $uniqueId }}"></div>
                                    <div id="results{{ $uniqueId }}"></div>

                                    <form id="uploadForm{{ $uniqueId }}" class="uploadForm mt-2">
                                        @csrf
                                        <input type="hidden" name="campaign_id" id="campaign_id{{ $uniqueId }}" value="{{ $campaign->id }}">
                                        <input type="hidden" name="fieldagent_id" id="fieldagent_id{{ $uniqueId }}" value="{{ Auth::guard('fieldagent')->id() }}">
                                        <input type="hidden" name="hoarding_id" id="hoarding_id{{ $uniqueId }}" value="{{ $hoarding_id }}">
                                        <input type="hidden" name="image" id="image{{ $uniqueId }}">
                                        <input type="hidden" name="longtitude" id="longtitude{{ $uniqueId }}">
                                        <input type="hidden" name="latitude" id="latitude{{ $uniqueId }}">
                                        <button type="submit" class="btn btn-success mt-1" style="font-size:13px;">
                                            Upload <i class="fa fa-upload ms-1"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function openCamera(id) {
        let container = document.getElementById('cameraContainer' + id);
        container.innerHTML = '<div id="camera' + id + '"></div>';

        Webcam.set({
            width: 320,
            height: 240,
            image_format: 'jpeg',
            jpeg_quality: 90
        });

        Webcam.attach('#camera' + id);
    }

    function takeSnapshot(id) {
        Webcam.snap(function (data_uri) {
            document.getElementById('results' + id).innerHTML = '<img src="' + data_uri + '" width="200"/>';
            document.getElementById('image' + id).value = data_uri;

            // Get Geolocation
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    document.getElementById('longtitude' + id).value = position.coords.longitude;
                    document.getElementById('latitude' + id).value = position.coords.latitude;

                    // Enable the upload button once data is ready
                    $('#uploadForm' + id + ' button[type=submit]').prop('disabled', false);
                }, function (error) {
                    alert("Error getting location: " + error.message);
                });
            } else {
                alert("Geolocation is not supported by this browser.");
            }
        });

        // Prevent form submission before snapshot & location
        $('#uploadForm' + id + ' button[type=submit]').prop('disabled', true);
    }

    $(document).ready(function () {
        $(".uploadForm").submit(function (e) {
            e.preventDefault();

            let formId = $(this).attr('id');
            let id = formId.replace('uploadForm', '');

            // Log the form data for debugging
            console.log({
                campaign_id: $('#campaign_id' + id).val(),
                fieldagent_id: $('#fieldagent_id' + id).val(),
                hoarding_id: $('#hoarding_id' + id).val(),
                image: $('#image' + id).val(),
                longtitude: $('#longtitude' + id).val(),
                latitude: $('#latitude' + id).val()
            });

            $.ajax({
                url: "{{ route('upload.image') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    campaign_id: $('#campaign_id' + id).val(),
                    fieldagent_id: $('#fieldagent_id' + id).val(),
                    hoarding_id: $('#hoarding_id' + id).val(),
                    image: $('#image' + id).val(),
                    longtitude: $('#longtitude' + id).val(),
                    latitude: $('#latitude' + id).val()
                },
                success: function (response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Uploaded',
                        text: response.success
                    });
                },
                error: function (xhr) {
                    console.log(xhr.responseText); // Add this for error debugging
                    Swal.fire({
                        icon: 'error',
                        title: 'Upload Failed',
                        text: xhr.responseJSON?.message || 'Something went wrong!'
                    });
                }
            });
        });
    });
</script>

@endsection
