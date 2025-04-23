 @extends('layout.main')
 <style>
     .switch {
         position: relative;
         display: inline-block;
         width: 50px;
         height: 24px;
     }

     .switch input {
         opacity: 0;
         width: 0;
         height: 0;
     }

     .slider {
         position: absolute;
         cursor: pointer;
         top: 0;
         left: 0;
         right: 0;
         bottom: 0;
         background-color: #fb3007;
         transition: .4s;
         border-radius: 24px;
     }

     .slider:before {
         position: absolute;
         content: "";
         height: 18px;
         width: 18px;
         left: 4px;
         bottom: 3px;
         background-color: white;
         transition: .4s;
         border-radius: 50%;
     }

     input:checked+.slider {
         background-color: #28a745;
     }

     input:checked+.slider:before {
         transform: translateX(26px);
     }
 </style>
 @section('content')

 @if(session('success_delete'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success_delete') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('delete_error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('delete_error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
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
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif


 <div class="card thm-card">
     <div class="card-header thm-card-head bg-gradient-danger d-flex align-items-center justify-content-between flex-wrap">
       <h5 class="thm-card-title card-title ms-0">Vendor List<i class="fa fa-list ms-2"></i></h5>
       <a href="{{route('admin.dashboard')}}" class="btn btn-sm btn-light text-primary">Back<i class="fa fa-arrow-left ms-1"></i></a>
     </div>
     <div class="card-body thm-card-body">
         <div class="d-flex align-items-center">
             <!--<h5 class="mb-0">Vendor List</h5>-->
             {{-- ------For delete---- --}}

             
         </div>
         <div class="table-responsive mt-3">
             <table class="table align-middle thm-tbl" id="example">
                 <thead class="table-secondary">
                     <tr>
                         <th>Sr.No.</th>
                         <th>Name</th>
                         <th>Mobile Number</th>
                         <th>Password</th>
                         <th>City</th>
                         <th>Status</th>
                         <th>Actions</th>
                     </tr>
                 </thead>
                 <tbody>
                     @php $key = 1; @endphp
                     @foreach($vendors as $vendor)
                     @if($vendor)
                     <tr>
                         <td>{{ $key }}</td>
                         <td>{{ $vendor->name }}</td>
                         <td>{{ $vendor->contact }}</td>
                         <td>{{ $vendor->plain_password }}</td>
                         <td>{{ $vendor->city }}</td>
                         <td>
                             <form action="{{ route('vendor.toggleStatus', $vendor->id) }}" class="my-auto" method="POST">
                                 @csrf
                                 @method('PUT')

                                 <label class="switch">
                                     <input type="checkbox" name="status" onchange="this.form.submit()" {{ $vendor->status == 1 ? 'checked' : '' }}>
                                     <span class="slider round"></span>
                                 </label>
                             </form>

                         </td>
                         <td>
                             <div class="table-actions d-flex align-items-center justify-content-center fs-6">
                                 <a href="{{ route('vendor.view', $vendor->id ?? 0) }}" class="btn btn-secondary me-2" data-bs-toggle="tooltip" data-bs-placement="bottom" title="View">
                                     <i class="bi bi-eye-fill mx-auto"></i>
                                 </a>
                                 <!--<a href="{{ route('vendor.edit', $vendor->id ?? 0) }}" class="btn btn-secondary me-2" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Edit">-->
                                 <!--    <i class="bi bi-pencil-fill mx-auto"></i>-->
                                 <!--</a>-->

                                 <form action="{{ route('vendor.destroy', $vendor->id ?? 0) }}" method="POST" class="my-auto" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                     @csrf
                                     @method('DELETE')
                                     <button type="submit" class="btn btn-danger" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Delete">
                                         <i class="bi bi-trash-fill mx-auto"></i>
                                     </button>
                                 </form>
                             </div>
                         </td>
                     </tr>
                     @endif
                     @php $key++ @endphp
                     @endforeach
                 </tbody>
             </table>
         </div>
     </div>
 </div>

 @endsection