@extends('layout.main')
@section('content')
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

<div class="card thm-card">
     <div class="card-header thm-card-head bg-gradient-danger d-flex flex-wrap align-items-center justify-content-between">
         <h5 class="card-title thm-card-title  ms-0">
             Field Agent List<i class="fa fa-list ms-2"></i>
         </h5>
         <a href="{{route('vendor.dashboard')}}" class="btn btn-light text-primary btn-sm ">Back<i class="fa fa-arrow-left ms-1"></i></a>
     </div>
    <div class="card-body thm-card-body">
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



        <div class="table-responsive mt-3">
            <table class="table align-middle thm-tbl" id="example">
                <thead class="text-center">
                    <tr>
                        <th>Sr.No.</th>
                        <th>Agent Name</th>
                        <th>Contact Number</th>
                        <th>Password</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                @php $key = 1; @endphp
                @foreach($fieldagentlists as $fieldagentlist)
                <tr>
                    <td>{{ $key }}</td>
                    <td>{{ $fieldagentlist->name }}</td>
                    <td>{{ $fieldagentlist->number }}</td>
                    <td>
                        <span id="password-text-{{$fieldagentlist->id}}"  style="display: none;">
                            {{ $fieldagentlist->confirmation_password }}
                        </span>
                        <span id="password-dots-{{$fieldagentlist->id}}">
                            ************
                        </span>
                    
                        <button class="btn btn-sm btn-warning shadow-none" onclick="togglePassword({{$fieldagentlist->id}})" title="Show/Hide Password">
                            <i id="eye-icon-{{$fieldagentlist->id}}" class="fa fa-eye mx-auto"></i>
                        </button>
                    </td>
                    
                    {{-- Status Toggle Switch --}}
                    <td>
                
                    <form action="{{ route('fieldagent.toggleStatus', $fieldagentlist->id) }}" class="my-auto" method="POST">
                        @csrf
                        @method('PUT')
                        <label class="switch">
                            <input type="checkbox" name="status" onchange="this.form.submit()" {{ $fieldagentlist->status == 1 ? 'checked' : '' }}>
                            <span class="slider round"></span>
                        </label>
                    </form>
                    </td>
                
                    {{-- Actions --}}
                    <td>
                        <div class="table-actions d-flex align-items-center justify-content-center fs-6">
                            <a href="{{ route('fieldagent.edit', $fieldagentlist->id) }}" class="btn btn-secondary me-2 btn-sm" data-bs-toggle="tooltip" title="Edit">
                                <i class="bi bi-pencil-fill mx-auto"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @php $key++ @endphp
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
      function togglePassword(id) {
        const textEl = document.getElementById('password-text-' + id);
        const dotsEl = document.getElementById('password-dots-' + id);
        const iconEl = document.getElementById('eye-icon-' + id);

        if (textEl.style.display === 'none') {
            textEl.style.display = 'inline';
            dotsEl.style.display = 'none';
            iconEl.classList.remove('fa-eye');
            iconEl.classList.add('fa-eye-slash');
        } else {
            textEl.style.display = 'none';
            dotsEl.style.display = 'inline';
            iconEl.classList.remove('fa-eye-slash');
            iconEl.classList.add('fa-eye');
        }
    }
</script>
@endsection


