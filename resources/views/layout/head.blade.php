<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <link href="{{asset('public/assets/plugins/select2/css/select2.min.css')}}" rel="stylesheet" />
    <link href="{{asset('public/assets/plugins/select2/css/select2-bootstrap4.css')}}" rel="stylesheet" />
    <!-- Favicon -->
    <!--<link rel="icon" href="{{asset('public/assets/images/favicon-32x32.png')}}" type="image/png" />-->
    <link rel="icon" href="{{asset('public/assets/images/icons/samvad-fav.jpeg')}}" type="image/png" />
    <!-- Plugins -->
    <link href="{{asset('public/assets/plugins/simplebar/css/simplebar.css')}}" rel="stylesheet" />
    <link href="{{asset('public/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css')}}" rel="stylesheet" />
    <link href="{{asset('public/assets/plugins/vectormap/jquery-jvectormap-2.0.2.css')}}" rel="stylesheet" />
    <link href="{{asset('public/assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" />

 <!-- select 2 library  -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <!-- Bootstrap CSS -->
    <link href="{{ asset('public/assets/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/assets/css/bootstrap-extended.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/assets/css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/assets/css/custom.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/assets/css/icons.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('public/cdn.jsdelivr.net/npm/bootstrap-icons%401.5.0/font/bootstrap-icons.css')}}">

    <!-- map css  -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <!-- owl css  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
    <!--bootstarp select -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta2/dist/css/bootstrap-select.min.css">
    <!--fancybox image pop up -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" />
    <!-- Loader -->
    <link href="{{ asset('public/assets/css/pace.min.css') }}" rel="stylesheet" />

    <!-- Theme Styles -->
    <link href="{{ asset('public/assets/css/dark-theme.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/assets/css/light-theme.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/assets/css/semi-dark.css') }}" rel="stylesheet" />
    <link href="{{ asset('public/assets/css/header-colors.css') }}" rel="stylesheet" />
    <script src="//unpkg.com/alpinejs" defer></script>
    <title>
             @if(auth('admin')->check())
              Samvad | {{ auth('admin')->user()->name }} | Panel
             @elseif(auth('vendor')->check())
             Samvad | {{ auth('vendor')->user()->name }} | Panel
             @elseif(auth('fieldagent')->check())
             Samvad | {{ auth('fieldagent')->user()->name }} | Panel
             @else
             Welcome, Guest
             @endif
    </title>
    
</head>


