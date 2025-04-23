<!doctype html>
<html lang="en" class="minimal-theme">
@include('layout.head')

<body>
    <!--start wrapper-->
    <div class="wrapper">
        <!--start top header-->
        <header class="top-header">
            @include('layout.header')
        </header>
        <!--end top header-->
        <!--start sidebar -->
        <aside class="sidebar-wrapper">
            @include('layout.sidebar')
        </aside>
        <!--end sidebar -->
        <!--start content-->
        <main class="page-content">
            @yield('content')
        </main>
        <!--end page main-->
        @include('layout.footer')
    </div>
    <script>
        $(".nav-toggle-icon").on("click", function() {


            $(".wrapper").toggleClass("toggled")


        })
        $(".iconmenu .nav-link").on("click", function() {
            let w = $(window).width();
            console.log(w)
            if (w >= 1199) {
                $(".wrapper").removeClass("toggled")
            }
        })
        $(".mobile-toggle-icon").on("click", function() {
            $(".wrapper").addClass("toggled")
        })
    </script>
    <!--end wrapper-->
</body>

</html>
