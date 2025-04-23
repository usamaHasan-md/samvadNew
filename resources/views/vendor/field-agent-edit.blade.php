@extends('layout.main')
@section('content')
<div class="row">
    <div class="col-xl-9 mx-auto">
        <div class="card thm-card">
            <div class="card-header thm-card-head bg-gradient-danger d-flex flex-wrap align-items-center justify-content-between">
               <h5 class="card-title thm-card-title ms-0">
                   Update Field Agent<i class="fa fa-pencil ms-2"></i>
               </h5>
               <a href="{{route('vendor.dashboard')}}" class="btn btn-light btn-sm text-primary">Back<i class="fa fa-arrow-left ms-1"></i></a>
            </div>
            <div class="card-body thm-card-body">
                <div class="rounded">
            
                    <form action="{{route('fieldagent.edit',$fieldagentEdit->id)}}" method="POST"> <!-- Added form tag -->
                        @csrf
                        <div class="row mb-3">
                            <label for="inputEnterYourName" class="col-sm-3 col-form-label">Name:</label>
                            <div class="col-sm-9">
                                <input type="text" name="name" value="{{ old('name', $fieldagentEdit->name) }}" class="form-control" id="inputEnterYourName" placeholder="Enter User Name" required>
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="inputPhoneNo2" class="col-sm-3 col-form-label">Contact</label>
                            <div class="col-sm-9">
                                <input type="text" name="number" value="{{ old('number', $fieldagentEdit->number) }}" class="form-control" id="inputPhoneNo2" placeholder="Phone No" required>
                                @error('number')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="inputcConfirm_password2" class="col-sm-3 col-form-label">Password</label>
                            <div class="col-sm-9">
                               <div class="position-relative">
							    <input type="password" name="confirmation_password" value="{{ old('confirmation_password', $fieldagentEdit->confirmation_password) }}" class="form-control" id="password2" placeholder="Confirm password" required>
								<i class="fa fa-eye toggle-password position-absolute top-50 end-0 translate-middle-y me-3" data-target="password2" style="cursor: pointer;"></i>
                                @error('confirmation_password')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
							   </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputPhoneNo2" class="col-sm-3 col-form-label">Confirm Password</label>
                            <div class="col-sm-9">
                              <div class="position-relative">
							    <input type="password" name="password" value="{{ old('confirmation_password', $fieldagentEdit->confirmation_password) }}" class="form-control" id="confirmation_password" placeholder="password" required>
								<i class="fa fa-eye toggle-password position-absolute top-50 end-0 translate-middle-y me-3" data-target="confirmation_password" style="cursor: pointer;"></i>
                                @error('password')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
							  </div>
                            </div>
                        </div>

                        <div class="row">
                            <label class="col-sm-3 col-form-label"></label>
                            <div class="col-sm-9">
                                <button type="submit" class="btn btn-primary px-5">Update</button>
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