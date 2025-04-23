@extends('layout.main')
@section('content')
<div class="card thm-card">
    <div class="card-header thm-card-head bg-gradient-danger d-flex flex-wrap align-items-center justify-content-between">
        <h5 class="thm-card-title card-title ms-0">
            Campaign Details<i class="fa fa-list-alt ms-2"></i>
        </h5>
        <a href="{{route('campaignView.admin')}}" class="btn btn-light btn-sm text-primary">Back<i class="fa fa-arrow-left ms-1"></i></a>
    </div>
    <div class="card-body thm-card-body">
        <!-- Campaign Information -->
        <div class="table-responsive mt-3">
            <table class="table align-middle thm-tbl">
                <thead>
                    <tr>
                        <th colspan="2">Campaign Information</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>Name:</th>
                        <td>{{ $campaigns->campaign_name }}</td>
                    </tr>
                    <tr>
                        <th>Description:</th>
                        <td style="min-width:450px;max-width:500px; white-space:normal;"> {{ $campaigns->description }}</td>
                    </tr>
                    <tr>
                        <th>Start Date:</th>
                        <td>{{ \Carbon\Carbon::parse($campaigns->start_date)->format('d-M-Y') }}</td>
                    </tr>
                    <tr>
                        <th>End Date:</th>
                        <td>{{ \Carbon\Carbon::parse($campaigns->end_date)->format('d-M-Y') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
   
     <!-- Vendors Tables -->
        <div class="table-responsive mt-3">
            <table class="table align-middle thm-tbl">
                <thead>
                    <tr>
                        <th colspan="2">Assigned Field Agents</th>
                    </tr>
                    <tr>
                        <th>Field Agent Name</th>
                        <th>Field Agent Number</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($fieldAgents as $fieldAgent)
                    <tr>
                        <td>{{ $fieldAgent->name }}</td>
                        <td>({{ $fieldAgent->number }})</td>
                    </tr>
                     @endforeach
                </tbody>
            </table>
        </div>


    <!-- Campaign Images -->
    
    <div class="row g-3">
            <h5 class="mb-0">Campaign Image</h5>
            <hr>
            <div class="col-md-3 text-center">
                <!-- Image 1 -->
                <div class="border p-2 rounded shadow-sm position-relative campaign-img-box" id="image-box-1">
                    <img src="{{asset('public/assets/images/compaign/swachh1.jpg')}}"
                        alt="Campaign Image 1"
                        class="img-fluid rounded image-clickable"
                        data-id="1"
                        data-place="Place 1"
                        data-lat="28.6"
                        data-lng="77.2"
                        data-date="2025-04-10"
                        data-time="10:44 AM">

                    <div class="mt-2" id="actions-1">
                        <button class="btn btn-success btn-sm me-1" onclick="approveImage(1)">✓</button>
                        <button class="btn btn-danger btn-sm" onclick="rejectImage(1)">✗</button>
                    </div>

                    <div id="status-msg-1" class="mt-1 fw-bold text-primary"></div>
                </div>
            </div>
            <div class="col-md-3 text-center">
                <!-- Image 2 -->
                <div class="border p-2 rounded shadow-sm position-relative campaign-img-box " id="image-box-2">
                    <img src="{{asset('public/assets/images/compaign/swachh2.jfif')}}"
                        alt="Campaign Image 2"
                        class="img-fluid rounded image-clickable"
                        data-id="2"
                        data-place="Place 2"
                        data-lat="28.7"
                        data-lng="77.3"
                        data-date="2025-04-10"
                        data-time="11:00 AM">

                    <div class="mt-2" id="actions-2">
                        <button class="btn btn-success btn-sm me-1" onclick="approveImage(2)">✓</button>
                        <button class="btn btn-danger btn-sm" onclick="rejectImage(2)">✗</button>
                    </div>

                    <div id="status-msg-2" class="mt-1 fw-bold text-primary"></div>
                </div>
            </div>
            <div class="col-md-3 text-center">
                <!-- Image 3 -->
                <div class="border p-2 rounded shadow-sm position-relative campaign-img-box " id="image-box-3">
                    <img src="{{asset('public/assets/images/compaign/ayushman.jpg')}}"
                        alt="Campaign Image 3"
                        class="img-fluid rounded image-clickable"
                        data-id="3"
                        data-place="Place 3"
                        data-lat="28.8"
                        data-lng="77.4"
                        data-date="2025-04-10"
                        data-time="11:15 AM">

                    <div class="mt-2" id="actions-3">
                        <button class="btn btn-success btn-sm me-1" onclick="approveImage(3)">✓</button>
                        <button class="btn btn-danger btn-sm" onclick="rejectImage(3)">✗</button>
                    </div>

                    <div id="status-msg-3" class="mt-1 fw-bold text-primary"></div>
                </div>
            </div>
            <div class="col-md-3 text-center">
                <!-- Image 4 -->
                <div class="border p-2 rounded shadow-sm position-relative campaign-img-box " id="image-box-4">
                    <img src="{{asset('public/assets/images/compaign/swachh1.jpg')}}"
                        alt="Campaign Image 4"
                        class="img-fluid rounded image-clickable"
                        data-id="4"
                        data-place="Place 4"
                        data-lat="28.9"
                        data-lng="77.5"
                        data-date="2025-04-10"
                        data-time="11:30 AM">

                    <div class="mt-2" id="actions-4">
                        <button class="btn btn-success btn-sm me-1" onclick="approveImage(4)">✓</button>
                        <button class="btn btn-danger btn-sm" onclick="rejectImage(4)">✗</button>
                    </div>

                    <div id="status-msg-4" class="mt-1 fw-bold text-primary"></div>
                </div>
            </div>
        </div>
        
        <!-- Modal -->
            <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg">
                    <div class="modal-content bg-transparent border-0">

                        <div class="modal-body p-0 position-relative">
                            <!-- Close button -->
                            <button type="button" class="btn btn-warning  position-absolute top-0 end-0 m-2" data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-times fs-4 mx-auto text-white align-baseline"></i></button>

                            <img id="modalImage" src="" class="img-fluid w-100 rounded" alt="Campaign Image">

                            <!-- Dark overlay for details -->
                            <div class="position-absolute bottom-0 start-0 w-100 p-3 bg-dark bg-opacity-75 text-white rounded-bottom" id="imageDetails">
                                <!-- Details will be inserted dynamically -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
 </div>
</div>
<script>
     // Image popup modal
        document.querySelectorAll('.image-clickable').forEach(img => {
            img.addEventListener('click', function() {
                const src = this.getAttribute('src');
                const place = this.dataset.place;
                const lat = this.dataset.lat;
                const lng = this.dataset.lng;
                const date = this.dataset.date;
                const time = this.dataset.time;

                document.getElementById('modalImage').src = src;

                document.getElementById('imageDetails').innerHTML = `
            <div><strong>Place:</strong> ${place}</div>
            <div><strong>Coordinates:</strong> ${lat}, ${lng}</div>
            <div><strong>Date:</strong> ${date}</div>
            <div><strong>Time:</strong> ${time}</div>
        `;

                new bootstrap.Modal(document.getElementById('imageModal')).show();
            });
        });

        // Approve image
        function approveImage(id) {
            document.getElementById('actions-' + id).remove();
            document.getElementById('status-msg-' + id).innerHTML = '<span class="text-success">✅ Approved</span>';
        }

        // Reject image
        function rejectImage(id) {
            document.getElementById('actions-' + id).remove();
            document.getElementById('status-msg-' + id).innerHTML = '<span class="text-danger">❌ Rejected</span>';
        }
</script>
@endsection
