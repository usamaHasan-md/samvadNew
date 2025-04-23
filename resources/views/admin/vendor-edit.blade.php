@extends('layout.main')
@section('content')
<div class="row">
    <div class="col-xl-9 mx-auto">
        <!--<h6 class="mb-0 text-uppercase">Vendor Edit</h6>-->
        <!--<hr />-->
        <div class="card thm-card">
            <div class="card-header thm-card-head bg-gradient-danger d-flex flex-wrap align-items-center justify-content-between">
                <h5 class="card-title thm-card-title ms-0">
                    Vendor Update<i class="fa fa-pencil ms-2"></i>
                </h5>
                <a href="{{route('list.vendor')}}" class="btn btn-light btn-sm text-primary">Back<i class="fa fa-arrow-left ms-1"></i></a>
            </div>
            <div class="card-body thm-card-body">
                <div class=" rounded">
                    <!--<div class="card-title d-flex align-items-center">-->
                    <!--    <h5 class="mb-0">Vendor Update</h5>-->
                    <!--</div>-->
                    <!--<hr />-->
                    <form action="{{route('vendor.update',$vendor->id)}}" method="POST"> <!-- Added form tag -->
                        @csrf
                        @method('PUT')
                        <div class="row mb-3">
                            <label for="inputEnterYourName" class="col-sm-3 col-form-label">User Name</label>
                            <div class="col-sm-9">
                                <input type="text" name="name" value="{{ old('name', $vendor->name) }}" class="form-control" id="inputEnterYourName" placeholder="Enter User Name" required>
                                @error('name')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="inputEmailAddress2" class="col-sm-3 col-form-label">Email Address</label>
                            <div class="col-sm-9">
                                <input type="email" name="email" value="{{ old('email', $vendor->email) }}" class="form-control" id="inputEmailAddress2" placeholder="Email Address" required>
                                @error('email')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="inputPhoneNo2" class="col-sm-3 col-form-label">Contact</label>
                            <div class="col-sm-9">
                                <input type="text" name="contact" value="{{ old('contact', $vendor->contact) }}" class="form-control" id="inputPhoneNo2" placeholder="Phone No" required>
                                @error('contact')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="inputPhoneNo2" class="col-sm-3 col-form-label">City:</label>
                            <div class="col-sm-9">
                                <select name="city" class="form-control" required>
                                    <option value="" disabled {{ old('city', $vendor->city) ? '' : 'selected' }}>Select City</option>
                                    <option value="Raipur">Raipur</option>
                                    <option value="Bilaspur">Bilaspur</option>
                                    <option value="Durg">Durg</option>
                                    <option value="Bhilai">Bhilai</option>
                                    <option value="Korba">Korba</option>
                                    <option value="Raigarh">Raigarh</option>
                                    <option value="Jagdalpur">Jagdalpur</option>
                                    <option value="Ambikapur">Ambikapur</option>
                                    <option value="Rajnandgaon">Rajnandgaon</option>
                                    <option value="Dhamtari">Dhamtari</option>
                                    <option value="Mahasamund">Mahasamund</option>
                                    <option value="Kanker">Kanker</option>
                                    <option value="Kawardha">Kawardha</option>
                                    <option value="Janjgir-Champa">Janjgir-Champa</option>
                                    <option value="Bemetara">Bemetara</option>
                                    <option value="Mungeli">Mungeli</option>
                                    <option value="Sukma">Sukma</option>
                                    <option value="Bijapur">Bijapur</option>
                                    <option value="Narayanpur">Narayanpur</option>
                                    <option value="Balod">Balod</option>
                                    <option value="Baloda Bazar">Baloda Bazar</option>
                                    <option value="Chirmiri">Chirmiri</option>
                                    <option value="Jashpur Nagar">Jashpur Nagar</option>
                                    <option value="Kondagaon">Kondagaon</option>
                                    <option value="Saraipali">Saraipali</option>
                                </select>
                                @error('city')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror       
                            </div>
                        </div>


                        {{-- <div class="row mb-3">
                            <label for="inputPhoneNo2" class="col-sm-3 col-form-label">Contact</label>
                            <div class="col-sm-9">
                                <input type="text" name="city" value="{{ old('city', $vendor->city) }}" class="form-control" id="inputPhoneNo2" placeholder="City" required>
                                @error('contact')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div> --}}

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
@endsection