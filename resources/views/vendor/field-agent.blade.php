@extends('layout.main')
@section('content')
<div class="row">
    <div class="col-xl-9 mx-auto">
        <!--<h6 class="mb-0 text-uppercase">Create Field Agent</h6>-->
        <!--<hr />-->
        <div class="card thm-card" >
            <div class="card-header thm-card-head bg-gradient-danger d-flex flex-wrap align-items-center justify-content-between">
                 <h5 class="card-title thm-card-title ms-0">
                     Create Field Agent<i class="fa fa-user-plus ms-2"></i>
                 </h5>
                 <a href="{{route('vendor.dashboard')}}" class="btn btn-light btn-sm text-primary">Back<i class="fa fa-arrow-left ms-1"></i></a>
             </div>
            <div class="card-body thm-card-body">
                <div class="rounded">
                    <form action="{{route('fieldagent.store')}}" method="POST"> <!-- Added form tag -->
                        @csrf
                        <div class="row mb-3">
                            <label for="inputEnterYourName" class="col-sm-3 col-form-label">Name:</label>
                            <div class="col-sm-9">
                                <input type="text" name="name" class="form-control" id="inputEnterYourName" placeholder="Enter User Name" required>
                                <input type="hidden" name="added_by" class="form-control" value="{{auth('vendor')->user()->id}}" required>
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="inputEmailAddress2" class="col-sm-3 col-form-label">Contact Number:</label>
                            <div class="col-sm-9">
                                <input type="text" name="number" class="form-control" id="inputcontactNumber2" placeholder="Contact Number" required>
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                     

                        <input type="hidden" name="role" id="inputRoleNo2" value="fieldagent">
                        <div class="row mb-3">
                            <label for="password2" class="col-sm-3 col-form-label">Password:</label>
                            <div class="col-sm-9">
                               <div class="position-relative">
                                <input type="password" name="password" class="form-control" id="password2" placeholder="Enter Password" required>
                                <i class="fa fa-eye toggle-password position-absolute top-50 end-0 translate-middle-y me-3" data-target="password2" style="cursor: pointer;"></i>
                                    @error('password')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                               </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="password_confirmation" class="col-sm-3 col-form-label">Confirm Password:</label>
                            <div class="col-sm-9">
                               <div class="position-relative">
                                <input type="password" name="confirmation_password" class="form-control" id="confirmation_password" placeholder="Confirm Password" required>
                                <i class="fa fa-eye toggle-password position-absolute top-50 end-0 translate-middle-y me-3" data-target="confirmation_password" style="cursor: pointer;"></i>
                                @error('confirmation_password')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                               </div>
                            </div>
                        </div>
                        <div class="row">
                            <label class="col-sm-3 col-form-label "></label>
                            <div class="col-sm-9">
                                <button type="submit" class="btn btn-primary px-5">Submit</button>
                            </div>
                        </div>
                    </form> <!-- Form tag closed here -->
                </div>
            </div>
        </div>
    </div>
</div>
<!--end row-->
<script>
        document.querySelectorAll('.toggle-password').forEach(function(icon) {
        icon.addEventListener('click', function() {
            const inputId = this.getAttribute('data-target');
            const input = document.getElementById(inputId);
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    });
</script>
@endsection
