@extends('layout.main')
@section('content')
<div class="card thm-card">
    <div class="card-header thm-card-head bg-gradient-danger d-flex flex-wrap align-items-center justify-content-between">
        <h5 class="thm-card-title card-title ms-0">
            Campaign Details<i class="fa fa-list-alt ms-2"></i>
        </h5>
        <a href="{{ url()->previous() }}" class="btn btn-light btn-sm text-primary">Back<i class="fa fa-arrow-left ms-1"></i></a>
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
                        <td style="min-width:450px;max-width:500px; white-space:normal;">{!!$campaigns->description!!}</td>
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

        <div class="row g-3">
            <h5 class="mb-0">Campaign Image</h5>
            <hr>
            <div class="table-responsive">
                <table class="table thm-tbl align-middle">
                    <!-- <table class="table table-bordered align-middle"> -->
                    <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Hoarding ID</th>
                            <th>Latitude</th>
                            <th>Longitude</th>
                            <th>Date/Time</th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($imageUploads->count() > 0) 
                        @foreach($imageUploads as $key => $image)
                        <tr id="row-{{ $key }}">
                            <td>{{$key+1}}</td>
                            <td>{{ $image->hoarding_id ?? 'N/A' }}</td>
                            <td>{{ $image->latitude ?? 'N/A' }}</td>
                            <td>{{ $image->longtitude ?? 'N/A' }}</td>
                            <td>{{ \Carbon\Carbon::parse($image->date)->format('d-M-Y H:i:s') ?? 'N/A' }}</td>
                            <td>
                                <img 
                                    src="{{ asset('public/'.$image->image) }}" 
                                    class="image-clickable"
                                    alt="Campaign Image"
                                    data-agent="{{ $image->agent_name ?? 'Unknown' }}"
                                    data-place="{{ $image->hoarding_id ?? 'Unknown' }}"
                                    data-lat="{{ $image->latitude ?? 'N/A' }}"
                                    data-lng="{{ $image->longtitude ?? 'N/A' }}"
                                    data-date="{{ \Carbon\Carbon::parse($image->date)->format('d-M-Y H:i:s') ?? 'N/A' }}"
                                    
                                    style="height: 100px;"
                                >
                            </td>
                            <td style="width: 350px;">
                                @if($image->is_verified === 1)
                                    <span class="text-success fw-bold"> ✓ Approved</span>
                                @elseif($image->is_verified === 0 && $image->vendor_remarks)
                                    <span class="text-danger fw-bold"> ✗ Rejected</span><br>
                                    <span class="text-dark fw-bold">Remark : </span><span class="text-danger fw-bold">{{$image->vendor_remarks}}</span>
                                @else
                                    <!-- Show Approve/Reject buttons only if not verified/rejected -->
                                    <div class="d-flex gap-2 mb-2 align-items-center justify-content-center" id="buttons-{{ $key }}">
                                        <!-- Approve Button -->
                                        <button 
                                            class="btn btn-success btn-sm" 
                                            onclick="approveImage({{ $key }}, '{{ route('vendor.image.verify', ['id' => $image->id]) }}')">
                                            ✓ Approve
                                        </button>
                                
                                        <!-- Reject Button -->
                                        <button 
                                            class="btn btn-danger btn-sm" 
                                            onclick="showRejectForm({{ $key }})">
                                            ✗ Reject
                                        </button>
                                    </div>
                                
                                    <!-- Hidden Remark Form -->
                                    <div id="remark-form-{{ $key }}" class="d-none">
                                        <form action="{{ route('vendor.image.reject', ['id' => $image->id]) }}" method="POST" id="reject-form-{{ $key }}">
                                            @csrf
                                            <textarea 
                                                class="form-control mb-2" 
                                                name="remark" 
                                                id="remark-{{ $key }}" 
                                                rows="2" 
                                                placeholder="Enter reason..." 
                                                required></textarea>
                                            <button type="submit" class="btn btn-danger btn-sm">Submit Remark</button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="7" class="text-center text-dark fw-bold">No Data Found</td>
                        </tr>
                        @endif
                    </tbody>
                    <!-- </table> -->
                </table>
            </div>
        </div>


        <!-- Image Modal -->
        <div class="modal fade" id="imageModal" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content bg-dark text-white" style="max-height:80vh; width:fit-content; margin-left:auto; margin-right:auto;">
                    <div class="modal-body position-relative p-0">
                        <button type="button" class="btn btn-warning position-absolute top-0 end-0 m-2" data-bs-dismiss="modal">✖</button>
                        <img id="modalImage" class="img-fluid rounded" alt="Preview" style="max-height:80vh; width:fit-content;margin-left:auto; margin-right:auto;">
                        <div class="p-3 position-absolute bottom-0" id="imageDetails"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>




<script>
    document.querySelectorAll('.image-clickable').forEach(img => {
        img.addEventListener('click', function() {
            const src = this.getAttribute('src');
            const agent = this.dataset.agent;
            const place = this.dataset.place;
            const lat = this.dataset.lat;
            const lng = this.dataset.lng;
            const date = this.dataset.date;
           
            document.getElementById('modalImage').src = src;

            document.getElementById('imageDetails').innerHTML = `
            <div><strong>Agent:</strong> ${agent}</div>
            <div><strong>Place:</strong> ${place}</div>
            <div><strong>Coordinates:</strong> ${lat}, ${lng}</div>
            <div><strong>Date:</strong> ${date}</div>
            
        `;

            new bootstrap.Modal(document.getElementById('imageModal')).show();
        });
    });




    // Approve logic
    function approveImage(id) {
        document.getElementById('status-msg-' + id).innerHTML = '<span class="text-success">✅ Approved</span>';

        // Remove both buttons
        const buttonsDiv = document.getElementById('buttons-' + id);
        if (buttonsDiv) {
            buttonsDiv.querySelectorAll('button').forEach(btn => btn.remove());
        }

        // Hide remark form in case it's open
        const remarkForm = document.getElementById('remark-form-' + id);
        if (remarkForm) {
            remarkForm.classList.add('d-none');
        }
    }

    // Show inline remark form
    function showRejectForm(id) {
        const remarkForm = document.getElementById('remark-form-' + id);
        if (remarkForm) {
            remarkForm.classList.remove('d-none');
        }
    }

    // Submit remark inline
    function submitRemark(id) {
        const remarkInput = document.getElementById('remark-' + id);
        const remark = remarkInput ? remarkInput.value.trim() : '';

        if (!remark) {
            alert('Please enter a rejection reason.');
            return;
        }

        // Show rejected message with remark
        document.getElementById('status-msg-' + id).innerHTML =
            `<span class="text-danger">❌ Rejected: ${remark}</span>`;

        // Remove buttons
        const buttonsDiv = document.getElementById('buttons-' + id);
        if (buttonsDiv) {
            buttonsDiv.querySelectorAll('button').forEach(btn => btn.remove());
        }

        // Hide remark form
        const remarkForm = document.getElementById('remark-form-' + id);
        if (remarkForm) {
            remarkForm.classList.add('d-none');
        }
    }
</script>
<script>
    // Show the reject form when the reject button is clicked
    function showRejectForm(key) {
        // Show the reject form and hide the approve/reject buttons
        document.getElementById('remark-form-' + key).classList.remove('d-none');
        document.getElementById('buttons-' + key).classList.add('d-none');
    }
</script>
<script>
    function confirmApproval(element) {
        if (confirm('Are you sure you want to approve this image?')) {
            window.location.href = element.getAttribute('data-url');
        }
    }
</script>

<script>
    function approveImage(key, url) {
        if (confirm('Are you sure you want to approve this image?')) {
            window.location.href = url;
        }
    }

    function showRejectForm(key) {
        document.getElementById(`remark-form-${key}`).classList.remove('d-none');
    }

    function submitRemark(key) {
        const remark = document.getElementById(`remark-${key}`).value.trim();
        if (remark === '') {
            alert('Please enter a reason for rejection.');
            return;
        }

        document.getElementById(`reject-form-${key}`).submit();
    }
</script>
<script>
    setTimeout(function() {
        const alertBox = document.getElementById('session-alert');
        if (alertBox) {
            alertBox.style.transition = 'opacity 0.5s ease-out';
            alertBox.style.opacity = 0;

            // Optional: remove it from DOM completely after fade
            setTimeout(() => alertBox.remove(), 500);
        }
    }, 3000); // 3 seconds
</script>
@endsection

