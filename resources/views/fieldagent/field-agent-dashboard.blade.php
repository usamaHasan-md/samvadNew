@extends('layout.main')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 px-0">
            <div class="card radius-10 mb-3 dashboard-title-card">
                <div class="card-body text-center px-lg-5 px-3">
                    <div class="dashboard-title">
                        <!-- <h1 aria-label="Samvad Admin Dashboard">Samvad</h1> -->
                        <h1 class="animated-title">
                            <img src="{{asset('public/assets/images/icons/samvad-logo.png')}}" alt="dashboard image">
                            <!--<span>S</span><span>a</span><span>m</span><span>v</span><span>a</span><span>d</span>-->
                        </h1>
                        <span class="dashboard-subtitle">Field Agent Dashboard</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <!--<div class="row row-cols-1 row-cols-lg-2 row-cols-xl-2 row-cols-xxl-4">-->
    <!--    <div class="col">-->
    <!--        <div class="card radius-10">-->
    <!--            <div class="card-body">-->
    <!--                <div class="d-flex align-items-center">-->
    <!--                    <div>-->
    <!--                        <p class="mb-0 text-secondary">Total Orders</p>-->
    <!--                        <h4 class="my-1">4805</h4>-->
    <!--                        <p class="mb-0 font-13 text-success"><i class="bi bi-caret-up-fill"></i> 5% from last week</p>-->
    <!--                    </div>-->
    <!--                    <div class="widget-icon-large bg-gradient-purple text-white ms-auto"><i class="bi bi-basket2-fill"></i>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--    <div class="col">-->
    <!--        <div class="card radius-10">-->
    <!--            <div class="card-body">-->
    <!--                <div class="d-flex align-items-center">-->
    <!--                    <div>-->
    <!--                        <p class="mb-0 text-secondary">Total Revenue</p>-->
    <!--                        <h4 class="my-1">$24K</h4>-->
    <!--                        <p class="mb-0 font-13 text-success"><i class="bi bi-caret-up-fill"></i> 4.6 from last week</p>-->
    <!--                    </div>-->
    <!--                    <div class="widget-icon-large bg-gradient-success text-white ms-auto"><i class="bi bi-currency-exchange"></i>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--    <div class="col">-->
    <!--        <div class="card radius-10">-->
    <!--            <div class="card-body">-->
    <!--                <div class="d-flex align-items-center">-->
    <!--                    <div>-->
    <!--                        <p class="mb-0 text-secondary">Total Customers</p>-->
    <!--                        <h4 class="my-1">5.8K</h4>-->
    <!--                        <p class="mb-0 font-13 text-danger"><i class="bi bi-caret-down-fill"></i> 2.7 from last week</p>-->
    <!--                    </div>-->
    <!--                    <div class="widget-icon-large bg-gradient-danger text-white ms-auto"><i class="bi bi-people-fill"></i>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--    <div class="col">-->
    <!--        <div class="card radius-10">-->
    <!--            <div class="card-body">-->
    <!--                <div class="d-flex align-items-center">-->
    <!--                    <div>-->
    <!--                        <p class="mb-0 text-secondary">Bounce Rate</p>-->
    <!--                        <h4 class="my-1">38.15%</h4>-->
    <!--                        <p class="mb-0 font-13 text-success"><i class="bi bi-caret-up-fill"></i> 12.2% from last week</p>-->
    <!--                    </div>-->
    <!--                    <div class="widget-icon-large bg-gradient-info text-white ms-auto"><i class="bi bi-bar-chart-line-fill"></i>-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</div><!--end row-->


    
@endsection