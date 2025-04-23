<!doctype html>
<html lang="en" class="minimal-theme">
<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="{{asset('public/assets/images/icons/samvad-fav.jpeg')}}" type="image/png" />
<!-- Bootstrap CSS -->
<link href="{{ asset('public/assets/css/bootstrap.min.css') }}" rel="stylesheet" />
<link href="{{ asset('public/assets/css/bootstrap-extended.css') }}" rel="stylesheet" />
<link href="{{ asset('public/assets/css/style.css') }}" rel="stylesheet" />
<link href="{{ asset('public/assets/css/icons.css') }}" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{asset('public/cdn.jsdelivr.net/npm/bootstrap-icons%401.5.0/font/bootstrap-icons.css')}}">
<!-- Loader -->
<link href="{{ asset('public/assets/css/pace.min.css') }}" rel="stylesheet" />
<script src="//unpkg.com/alpinejs" defer></script>
<title>Samvad | Login</title>
 <style>
    .js-error {
      font-weight: 500;
      color: red;
    }
  </style>
</head>
<body>
    <!--start wrapper-->
    <div class="wrapper">
        <!--start content-->
        <main class="authentication-content">
            <div class="container-fluid">
                <div class="authentication-card">
                    <div class="card shadow rounded-0 overflow-hidden">
                        <div class="row g-0">
                            <div class="col-lg-6 bg-login d-flex align-items-center justify-content-center">
                                <img src="{{asset('public/assets/images/error/login-img.jpg')}}" class="img-fluid" alt="samvad logo">
                            </div>
                            <div class="col-lg-6">
                                <div class="card-body p-4 p-sm-5">
                    <!--@if(session('success'))-->
                    <!--<p style="color:green">{{ session('success') }}</p>-->
                    <!-- @endif-->
                    <!-- @if($errors->any())-->
                    <!--     <p style="color:red">{{ $errors->first() }}</p>-->
                    <!-- @endif-->
                    <form class="form-body vldt-form" action="{{route('logedin.admin')}}" method="post">
                        @csrf
                      <div class="text-center">
                       <img src="{{('public/assets/images/icons/samvad-logo.png')}}" style="height:80px;" class="mx-auto mb-3">
                      </div>
                      <!--<div class="login-separater text-center mb-4" style="color: rgb(55, 55, 251)"><h3>Samvad Login</h3>-->
                        <hr>
                      <!--</div>-->
                        <div class="row g-3">
                          <div class="col-12">
                            <label for="inputEmailAddress" class="form-label">Email Address</label>
                            <div class="ms-auto position-relative">
                              <div class="position-absolute top-50 translate-middle-y search-icon px-3"><i class="bi bi-envelope-fill"></i></div>
                              <input type="email" name="email" class="form-control radius-30 ps-5 vldt requd" id="inputEmailAddress" placeholder="Email Address">
                            </div>
                            <span class="js-error"></span>
                          </div>
                          <div class="col-12">
                            <label for="inputChoosePassword" class="form-label">Enter Password</label>
                            <div class="ms-auto position-relative">
                              <div class="position-absolute top-50 translate-middle-y search-icon px-3"><i class="bi bi-lock-fill"></i></div>
                              <input type="password" name="password" class="form-control radius-30 ps-5 vldt requd" id="inputChoosePassword" placeholder="Enter Password">
                              <div class="position-absolute top-50  px-3 translate-middle-y" style="right:0;"><i class="bi bi-eye-fill toggle-password" data-target="inputChoosePassword"></i></div>
                            </div>
                            <span class="js-error"></span>
                          </div>
                          <!--<div class="col-6">-->
                          <!--  <div class="form-check form-switch">-->
                          <!--    <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked="">-->
                          <!--    <label class="form-check-label" for="flexSwitchCheckChecked">Remember Me</label>-->
                          <!--  </div>-->
                          <!--</div>-->
                          </div>
                          <div class="col-12 mt-3">
                            <div class="d-grid">
                              <button type="submit" class="btn btn-primary radius-30">Sign In</button>
                            </div>
                          </div>
                         
                        </div>
                    </form>
                 </div>
                </div>
              </div>
            </div>
          </div>
        </div>
       </main>
        
       <!--end page main-->

  </div>
  <!--end wrapper-->


  <!--plugins-->
  <script src="{{asset('public/assets/js/pace.min.js')}}"></script>
  <script src="{{asset('public/assets/js/jquery.min.js')}}"></script>
  <script src="{{asset('public/assets/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{asset('public/assets/js/app.js')}}"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
      const loginForm = document.querySelector('.vldt-form');

      if (loginForm) {
        const validateField = (input) => {
          // Find the closest .col-12 wrapper
          const wrapper = input.closest('.col-12');
          const errorSpan = wrapper ? wrapper.querySelector('.js-error') : null;

          if (errorSpan) errorSpan.textContent = ''; // Clear previous error

          const value = input.value.trim();
          let errorText = '';

          // Required validation
          if (input.classList.contains('requd') && value === '') {
            errorText = 'This field is required';
          }

          // Email format validation
          if (input.name === 'email' && value !== '') {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
              errorText = 'Enter a valid email address';
            }
          }

          if (errorSpan && errorText) {
            errorSpan.textContent = errorText;
            return false;
          }

          return true;
        };

        // Form submit
        loginForm.addEventListener('submit', function(e) {
          let isValid = true;

          loginForm.querySelectorAll('.vldt').forEach(function(input) {
            if (!validateField(input)) {
              isValid = false;
            }
          });

          if (!isValid) {
            e.preventDefault();
          }
        });

        // On input: live validation
        loginForm.querySelectorAll('.vldt').forEach(function(input) {
          input.addEventListener('input', function() {
            validateField(input);
          });
        });
      }
    });
  </script>
  
   
  <script>
       document.querySelectorAll('.toggle-password').forEach(function(icon) {
    icon.addEventListener('click', function () {
      const input = this.closest('.position-relative').querySelector('input');
      const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
      input.setAttribute('type', type);
      this.classList.toggle('bi-eye-fill');
      this.classList.toggle('bi-eye-slash-fill');
    });
  });
    </script>
@if (session('successMessage'))
<div x-data="{ show: true }" x-init="setTimeout(() => show = false, 10000)" x-show="show"
    class="alert alert-success border-0 bg-light-success alert-dismissible fade show"
    style="position: fixed; top: 15%; left: 50%; transform: translate(-50%, -50%); z-index: 999999999999999; margin-top: -25px; width: 30%; text-align: center;">
    <div class="text-success">
        <p>{{ session('successMessage') }}</p>
    </div>
</div>
@endif
@if (session('dangerMessage'))
<div x-data="{ show: true }" x-init="setTimeout(() => show = false, 10000)" x-show="show"
    class="alert alert-danger border-0 bg-light-danger alert-dismissible fade show"
    style="position: fixed; top: 15%; left: 50%; transform: translate(-50%, -50%); z-index: 999999999999999; margin-top: -25px; width: 30%; text-align: center;">
    <div class="text-danger">
        <p>{{ session('dangerMessage') }}</p>
    </div>
</div>
@endif
@if (session('warningMessage'))
<div x-data="{ show: true }" x-init="setTimeout(() => show = false, 10000)" x-show="show"
    class="alert alert-warning border-0 bg-light-warning alert-dismissible fade show"
    style="position: fixed; top: 15%; left: 50%; transform: translate(-50%, -50%); z-index: 999999999999999; margin-top: -25px; width: 30%; text-align: center;">
    <div class="text-warning">
        <p>{{ session('warningMessage') }}</p>
    </div>
</div>
@endif

</body>
</html>
