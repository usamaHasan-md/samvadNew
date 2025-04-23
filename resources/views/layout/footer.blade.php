<!--<footer class=" py-3">-->
<!--    <div class="container text-center">-->
        
<!--            Designed & Developed by -->
<!--            <a href="https://riveyrainfotech.com/" class="text-muted text-decoration-underline fw-bold">-->
<!--                Riveyra Infotech Pvt. Ltd.-->
<!--            </a>-->
<!--    </div>-->
<!--</footer>-->
<div class="overlay nav-toggle-icon"></div>
<!--end overlay-->
<!--Start Back To Top Button-->
<a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
<!--End Back To Top Button-->
<!--start switcher-->
<div class="switcher-body">
    <!-- <button class="btn btn-primary btn-switcher shadow-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling"><i class="bi bi-paint-bucket me-0"></i></button> -->
    <div class="offcanvas offcanvas-end shadow border-start-0 p-2" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="offcanvasScrolling">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title" id="offcanvasScrollingLabel">Theme Customizer</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <h6 class="mb-0">Theme Variation</h6>
            <hr>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="LightTheme" value="option1">
                <label class="form-check-label" for="LightTheme">Light</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="DarkTheme" value="option2">
                <label class="form-check-label" for="DarkTheme">Dark</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="SemiDarkTheme" value="option3">
                <label class="form-check-label" for="SemiDarkTheme">Semi Dark</label>
            </div>
            <hr>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="MinimalTheme" value="option3" checked>
                <label class="form-check-label" for="MinimalTheme">Minimal Theme</label>
            </div>
            <hr />
            <h6 class="mb-0">Header Colors</h6>
            <hr />
            <div class="header-colors-indigators">
                <div class="row row-cols-auto g-3">
                    <div class="col">
                        <div class="indigator headercolor1" id="headercolor1"></div>
                    </div>
                    <div class="col">
                        <div class="indigator headercolor2" id="headercolor2"></div>
                    </div>
                    <div class="col">
                        <div class="indigator headercolor3" id="headercolor3"></div>
                    </div>
                    <div class="col">
                        <div class="indigator headercolor4" id="headercolor4"></div>
                    </div>
                    <div class="col">
                        <div class="indigator headercolor5" id="headercolor5"></div>
                    </div>
                    <div class="col">
                        <div class="indigator headercolor6" id="headercolor6"></div>
                    </div>
                    <div class="col">
                        <div class="indigator headercolor7" id="headercolor7"></div>
                    </div>
                    <div class="col">
                        <div class="indigator headercolor8" id="headercolor8"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end switcher-->
</div>
<!--end wrapper-->
<!-- Bootstrap bundle JS -->
<script src="{{asset('public/assets/js/bootstrap.bundle.min.js')}}"></script>
<!--plugins-->
<script src="{{asset('public/assets/js/jquery.min.js')}}"></script>
<script src="{{asset('public/assets/plugins/simplebar/js/simplebar.min.js')}}"></script>
<script src="{{asset('public/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js')}}"></script>
<script src="{{asset('public/assets/js/pace.min.js')}}"></script>
<script src="{{asset('public/assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js')}}"></script>
<script src="{{asset('public/assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
<script src="{{asset('public/assets/plugins/apexcharts-bundle/js/apexcharts.min.js')}}"></script>
<script src="{{asset('public/assets/plugins/chartjs/chart.min.js')}}"></script>
<!--app-->
<script src="{{asset('public/assets/js/app.js')}}"></script>
<script src="{{asset('public/assets/js/index.js')}}"></script>
<script src="{{asset('public/assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('public/assets/plugins/datatable/js/dataTables.bootstrap5.min.js')}}"></script>
<script src="{{asset('public/assets/js/table-datatable.js')}}"></script>
<script src="{{asset('public/assets/js/app.js')}}"></script>
<!-- Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<!--<script src="{{asset('public/assets/plugins/select2/js/select2.min.js')}}"></script>-->
<!--<script src="{{asset('public/assets/js/form-select2.js')}}"></script>-->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/js/bootstrap-select.min.js"></script>
<!--fancy box image pop up -->
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.umd.js"></script>
<script>
    $(document).ready(function () {
        // Only run if there's any contact section on page
        if ($('.contact-section').length) {

            // Fetch states and populate .state
            $('.contact-section').each(function () {
                const stateSelect = $(this).find('.state');
                const citySelect = $(this).find('.city');

                // Load states for each .state dropdown
                fetch("https://countriesnow.space/api/v0.1/countries/states", {
                    method: "POST",
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ country: "India" })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.data && data.data.states) {
                        data.data.states.forEach(state => {
                            const isSelected = state.name === "Chhattisgarh" ? "selected" : "";
                            stateSelect.append(`<option value="${state.name}" ${isSelected}>${state.name}</option>`);
                        });

                        // Trigger change to load cities for default selected state
                        stateSelect.trigger('change');
                    }
                });

                // On state change, load cities
                stateSelect.on('change', function () {
                    const selectedState = $(this).val();
                    citySelect.html('<option disabled selected>Loading...</option>');

                    fetch("https://countriesnow.space/api/v0.1/countries/state/cities", {
                        method: "POST",
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ country: "India", state: selectedState })
                    })
                    .then(response => response.json())
                    .then(data => {
                        citySelect.empty().append('<option disabled selected>Select City</option>');
                        if (data.data) {
                            data.data.forEach(city => {
                                citySelect.append(`<option value="${city}">${city}</option>`);
                            });
                        }
                    });
                });
            });
        }
    });
    
     // compaign slider image owl

    $('.running-Campaign-slider').owlCarousel({
        loop: true,
        margin: 10,
        nav: false,
        items: 1,
        autoplay: true,
        autoplayTimeout: 3000,
        autoplayHoverPause: true
    });
   
    // select2 js 
    $(document).ready(function() {
            $('.multiSelect').select2({
                placeholder: "--Select Here--",
                 closeOnSelect: false
            });

            // When "Select All" is selected, select all other options
            $('.multiSelect').on('change', function() {
                if ($(this).val().includes('all')) {
                    // Select all options
                    var allValues = $('.multiSelect option').not(':first').map(function() {
                        return $(this).val();
                    }).get();
                    $(this).val(allValues).trigger('change');
                }
            });
        });
        
        
        $('.selectpicker').on('changed.bs.select', function () {
    var selected = $(this).val() || [];
    var all = $(this).find('option').map(function () {
        return this.value;
    }).get();

    if (selected.includes('all')) {
        // If everything else is already selected → deselect all
        if (selected.length === all.length) {
            $(this).selectpicker('deselectAll');
        } else {
            // Otherwise, select all except 'all' option
            $(this).selectpicker('val', all.filter(val => val !== 'all'));
        }
    }
});

</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.vldt-form').forEach(function(form) {

            const validateField = (input) => {
                const errorSpan = input.nextElementSibling;
                let errorMessage = '';

                // Required field check
                if (input.classList.contains('requd')) {
                    if (
                        input.tagName === 'SELECT' &&
                        (input.value === '' || input.selectedIndex === 0)
                    ) {
                        errorMessage = 'Please select an option.';
                    } else if (input.value.trim() === '') {
                        errorMessage = 'This field is required.';
                    }
                }

                // Email validation
                if (input.classList.contains('vldt-email') && input.value.trim() !== '') {
                    const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,}$/;
                    if (!emailPattern.test(input.value.trim())) {
                        errorMessage = 'Please enter a valid email.';
                    }
                }

                // Number validation (exactly 10 digits)
                if (input.classList.contains('vldt-num') && input.value.trim() !== '') {
                    const phonePattern = /^[0-9]{10}$/;
                    if (!phonePattern.test(input.value.trim())) {
                        errorMessage = 'Please enter a valid 10-digit number.';
                    }
                }

                // Password match
                if (input.classList.contains('match-password')) {
                    const passwordInput = form.querySelector('input[name="password"]');
                    if (passwordInput && input.value !== passwordInput.value) {
                        errorMessage = 'Passwords do not match.';
                    }
                }

                // 5-digit PIN password validation
                if (input.classList.contains('pin-password') && input.value.trim() !== '') {
                    const pinPattern = /^\d{5}$/;
                    if (!pinPattern.test(input.value.trim())) {
                        errorMessage = 'Password must be a 5-digit number.';
                    }
                }


                // Show error
                if (errorSpan) {
                    errorSpan.innerText = errorMessage;
                }

                return errorMessage === '';
            };

            // On form submit
            form.addEventListener('submit', function(e) {
                let isValid = true;

                form.querySelectorAll('.vldt').forEach(function(input) {
                    const valid = validateField(input);
                    if (!valid) {
                        isValid = false;
                    }
                });

                if (!isValid) {
                    e.preventDefault(); // Stop form submission
                }
            });

            // Live validation
            form.querySelectorAll('.vldt').forEach(function(input) {
                input.addEventListener('input', function() {
                    validateField(input);
                });

                // Re-validate confirm password when original password is changed
                if (input.classList.contains('match-password')) {
                    const passwordInput = form.querySelector('input[name="password"]');
                    if (passwordInput) {
                        passwordInput.addEventListener('input', function() {
                            validateField(input);
                        });
                    }
                }
            });
        });
    });
</script>

@if (session('successMessage'))
<div x-data="{ show: true }" x-init="setTimeout(() => show = false, 10000)" x-show="show"
    class="alert alert-success border-0 bg-light-success alert-dismissible fade show"
    style="position: fixed; top: 10%; left: 60%; transform: translate(-50%, -50%); z-index: 999999999999999; margin-top: -25px; width: 30%; text-align: center;">
    <div class="text-success">
        <p>{{ session('successMessage') }}</p>
    </div>
</div>
@endif


@if (session('dangerMessage'))
<div x-data="{ show: true }" x-init="setTimeout(() => show = false, 10000)" x-show="show"
    class="alert alert-danger border-0 bg-light-danger alert-dismissible fade show"
    style="position: fixed; top: 10%; left: 60%; transform: translate(-50%, -50%); z-index: 999999999999999; margin-top: -25px; width: 30%; text-align: center;">
    <div class="text-danger">
        <p>{{ session('dangerMessage') }}</p>
    </div>
</div>
@endif

@if (session('warningMessage'))
<div x-data="{ show: true }" x-init="setTimeout(() => show = false, 10000)" x-show="show"
    class="alert alert-warning border-0 bg-light-warning alert-dismissible fade show"
    style="position: fixed; top: 10%; left: 60%; transform: translate(-50%, -50%); z-index: 999999999999999; margin-top: -25px; width: 30%; text-align: center;">
    <div class="text-warning">
        <p>{{ session('warningMessage') }}</p>
    </div>
</div>
@endif