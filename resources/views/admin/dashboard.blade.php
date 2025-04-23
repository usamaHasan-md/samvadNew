@extends('layout.main')
@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 px-0">
            <div class="card radius-10 mb-3 dashboard-title-card">
                <div class="card-body text-center px-lg-5 px-3">
                    <div class="dashboard-title">
                        <!--<h1 class="animated-title">-->
                        <!--    <img src="{{asset('public/assets/images/icons/samvad-logo.png')}}" alt="dashboard image">-->
                        <!--     <span>S</span><span>a</span><span>m</span><span>v</span><span>a</span><span>d</span> -->
                        <!--</h1>-->
                        <h1 class="animated-title">Admin Management Dashboard</h1>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
<div class="row row-cols-1 row-cols-lg-2 row-cols-xl-2 row-cols-xxl-4">
    <div class="col-md-3">
        <a href="{{route('admin.ongoingCampaigns')}}">
        <div class="card radius-10">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary">Current Ongoing Campaign</p>
                        <h4 class="my-1 text-dark">{{$totalCurCamp}}</h4>
                        <!--<p class="mb-0 font-13 text-success"><i class="bi bi-caret-up-fill"></i> 5% from last week</p>-->
                    </div>
                    <div class="widget-icon-large bg-gradient-purple text-white ms-auto"><i class="fa fa-bullhorn"></i>
                    </div>
                </div>
            </div>
        </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{route('admin.previousCampaigns')}}">
        <div class="card radius-10">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary">Completed Campaign</p>
                        <h4 class="my-1 text-dark">{{ $totalPrevCamp }}</h4>
                        <!--<p class="mb-0 font-13 text-success"><i class="bi bi-caret-up-fill"></i> 4.6 from last week</p>-->
                    </div>
                    <div class="widget-icon-large bg-gradient-success text-white ms-auto"><i class="fa fa-bullhorn"></i>
                    </div>
                </div>
            </div>
        </div>
        </a>
    </div>
    <div class="col-md-3">
        <a href="{{route('list.vendor')}}">
        <div class="card radius-10">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary">Total Vendors</p>
                        <h4 class="my-1 text-dark">{{ $totalVendors }}</h4>
                        <!--<p class="mb-0 font-13 text-danger"><i class="bi bi-caret-down-fill"></i> 2.7 from last week</p>-->
                    </div>
                    <div class="widget-icon-large bg-gradient-danger text-white ms-auto"><i class="bi bi-people-fill"></i>
                    </div>
                </div>
            </div>
        </div>
        </a>
    </div>
    <div class="col-md-3">
        
        <div class="card radius-10">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                        <p class="mb-0 text-secondary">Total Assigned Vendor</p>
                        <h4 class="my-1 text-dark">{{$totalassignvendor}}</h4>
                        <!--<p class="mb-0 font-13 text-success"><i class="bi bi-caret-up-fill"></i> 12.2% from last week</p>-->
                    </div>
                    <div class="widget-icon-large bg-gradient-info text-white ms-auto"><i class="fa fa-user-plus"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><!--end row-->
<!-- <div class="container mt-2"> -->
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card campaign-card  thm-card border-light">
                <div class="card-header bg-gradient-info thm-card-head border-none">
                    <h5 class="thm-card-title blinking-title">
                        Currently Ongoing Campaigns
                        <span class="running-badge my-auto">Running</span>
                    </h5>
                </div>
                
                <div class="card-body thm-card-body">
                    <div class="owl-carousel owl-theme running-Campaign-slider">
                        @foreach($ongoingcampaigns as $ongoing)
                        @php
                        $images = json_decode($ongoing->images);
                        @endphp
                        <a href="{{ route('VendorCampaignView.admin', ['campaign_id' => $ongoing->id]) }}">
                        <div class="item Campaign-slider-img">
                            <img src="{{ asset('public/'. $images[0]) }}" class="img-fluid">
                            <div class="campaign-caption">{{$ongoing->campaign_name}}</div>
                        </div>
                        </a>
                        @endforeach
                        <!--<div class="item Campaign-slider-img">-->
                        <!--    <img src="{{ asset('public/assets/images/compaign/swachh2.jfif') }}" class="img-fluid">-->
                        <!--    <div class="campaign-caption">Swachh Bharat Campaign</div>-->
                        <!--</div>-->
                        <!--<div class="item Campaign-slider-img">-->
                        <!--    <img src="{{ asset('public/assets/images/compaign/ayushman.jpg') }}" class="img-fluid">-->
                        <!--    <div class="campaign-caption">Ayushman Yojana</div>-->
                        <!--</div>-->
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card thm-card campaign-card border-light">
                <div class="card-header thm-card-head bg-gradient-danger d-flex align-items-center justify-content-between">
                    <h5 class="thm-card-title card-title ms-0">
                        Previous Complete Campaigns
                    </h5>
                    <div class="scroll-controls">
                        <button class="btn btn-sm btn-warning shadow-none" id="toggleScrollBtn">⏸</button>
                    </div>
                </div>

                <div class="card-body scroll-list">
                    <div id="scrollContainer" class="scroll-content">
                        <div class="scroll-list-group" id="scrollItems">
                            @foreach($campaigns as $camp)
                            <a href="{{ route('VendorCampaignView.admin', ['campaign_id' => $camp->id]) }}">
                                <div class="scrolling-list-group-item text-dark">
                                  {{$camp->campaign_name}}
                                   <div class="campaign-date"><span class="fw-bold">From</span> 01 Jan - <span class="fw-bold">To</span> 10 Jan</div>
                                </div>
                            </a>
                            @endforeach

                            <div class="scrolling-list-group-item">
                                Republic Day Blood Donation Camp
                                <div class="campaign-date"><span class="fw-bold">From</span> 11 Jan - <span class="fw-bold">To</span> 20 Jan</div>
                                
                            </div>

                            <div class="scrolling-list-group-item">
                                Digital Literacy Week
                                <div class="campaign-date"><span class="fw-bold">From</span> 01 Feb - <span class="fw-bold">To</span> 15 Feb</div>
                               
                            </div>

                            <div class="scrolling-list-group-item">
                                Clean Rivers Campaign
                                <div class="campaign-date"><span class="fw-bold">From</span> 20 Feb - <span class="fw-bold">To</span> 05 Mar</div>
                                
                            </div>

                            <div class="scrolling-list-group-item">
                                Women's Health & Wellness Fair
                                <div class="campaign-date"><span class="fw-bold">From</span> 10 Mar - <span class="fw-bold">To</span> 30 Mar</div>
                                
                            </div>

                        </div>

                        <!-- Duplicate the list for infinite loop effect -->
                        <div class="scroll-list-group" id="scrollItemsClone">
                            <!-- Will be cloned by JS -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- </div> -->
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card thm-card border-light">
            <div class="card-header thm-card-head bg-gradient-danger d-flex align-items-center justify-content-between">
                <h5 class="thm-card-title card-title ms-0">Category Wise Record</h5>
            </div>
            <div class="card-body" style="height:17rem; overflow-y:auto;">
                <div class="table-responsive">
                    <table class="thm-tbl table align-middle mb-auto">
                        <thead>
                            <tr>
                                <th>S.no</th>
                                <th>Category</th>
                                <th>Sub Category</th>
                                <th>Ongoing</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $serialNumber = 1; @endphp
                            @foreach ($categoryWise as $category => $subcategories)
                                @foreach ($subcategories as $key => $subcategory)
                                    @php
                                        $query = http_build_query([
                                            'filter' => [
                                                'category' => [$subcategory->category_id],
                                                'sub_category' => [$subcategory->sub_category_id],
                                            ]
                                        ]);
                                        $url = route('report.campaignWise').'?'.$query;
                                    @endphp
                                    <tr onclick="window.location.href='{{ $url }}'" style="cursor: pointer;">
                                        @if ($key === 0)
                                            <td rowspan="{{ count($subcategories) }}">{{ $serialNumber++ }}</td>
                                            <td rowspan="{{ count($subcategories) }}">{{ $category }}</td>
                                        @endif
                                        <td>{{ $subcategory->sub_category }}</td>
                                        <td>{{ $subcategory->total }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                            <tr>
                                <th colspan="3">Total</th>
                                <td>{{ $categoryWise->flatten(1)->sum('total') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>



    <div class="col-md-6">
        <div class="card thm-card border-light">
            <div class="card-header thm-card-head bg-gradient-danger d-flex align-items-center justify-content-between">
                <h5 class="thm-card-title card-title ms-0">
                    District Wise Record
                </h5>
            </div>
            <div class="card-body" style="height:17rem; overflow-y:auto;">
                <div class="table-responsive">
                    <table class="thm-tbl table align-middle">
                        <thead>
                            <tr>
                                <th>S.no</th>
                                <th>District</th>
                                <th>Count of District</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $serialNumber = 1; @endphp
                            @foreach ($districtWise->groupBy('district') as $district => $records)
                                @foreach ($records as $key => $row)
                                    @php
                                        $query = http_build_query([
                                            'filter' => [
                                                'district' => [$row->district],
                                                // add 'sub_district' if needed
                                            ]
                                        ]);
                                        $url = route('report.campaignWise') . '?' . $query;
                                    @endphp
                                    <tr onclick="window.location.href='{{ $url }}'" style="cursor: pointer;">
                                        @if ($key === 0)
                                            <td rowspan="{{ count($records) }}">{{ $serialNumber++ }}</td>
                                            <td rowspan="{{ count($records) }}">{{ $district }}</td>
                                        @endif
                                        {{-- Display other level like sub_district if present --}}
                                        <td>{{ $row->total }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                            <tr>
                                <th colspan="2">Total</th>
                                <td>{{ $districtWise->sum('total') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

  <div class="card thm-card border-light">
        <div class="thm-card-head card-header bg-gradient-purple ">
            <h5 class="card-title thm-card-title">Map wise Location of Campaign <i class="fa fa-map ms-2"></i></h5>
        </div>
        <div class="card-body thm-card-body">
            <button id="centerBtn"><i class="fa fa-dot-circle-o"></i></button>
            <div id="map"></div>
            <div class="scaleofmap">
                <h4>Hoardings Running in Chhattisgarh</h4>
                <table class="thm-tbl table-sm table align-middle text-center mb-0">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <!--<th style="text-align: center;">Icon</th>-->
                            <th class="count-cell">Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Light Hoarding Premium</td>
                            <!--<td style="text-align: center;"><i class="fa fa-lightbulb-o light-premium"></i></td>-->
                            <td class="count-cell" id="light-premium-count">{{$lighthoarding}}</td>
                        </tr>
                        <tr>
                            <td>Non-Light Hoarding Premium</td>
                            <!--<td style="text-align: center;"><i class="fa fa-ban nonlight-premium"></i></td>-->
                            <td class="count-cell" id="nonlight-premium-count">{{$nonlighthoarding}}</td>
                        </tr>
                        <tr>
                            <td>Light Unipole</td>
                            <!--<td style="text-align: center;"><i class="fa fa-arrows-v light-unipole"></i></td>-->
                            <td class="count-cell" id="light-unipole-count">{{$lightunipole}}</td>
                        </tr>
                        <tr>
                            <td>Non-Light Unipole</td>
                            <!--<td style="text-align: center;"><i class="fa fa-arrows-h nonlight-unipole"></i></td>-->
                            <td class="count-cell" id="nonlight-unipole-count">{{$nonlightunipole}}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


<script src="https://cdn.jsdelivr.net/npm/@turf/turf@6/turf.min.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        const original = document.getElementById("scrollItems");
        const clone = document.getElementById("scrollItemsClone");
        const scrollContainer = document.getElementById("scrollContainer");
        const outerScrollBox = document.querySelector(".scroll-list");
        const toggleBtn = document.getElementById("toggleScrollBtn");

        // Clone content for seamless looping
        clone.innerHTML = original.innerHTML;

        let isPaused = false;
        let position = 0;
        let speed = 0.5; // pixels per frame
        let scrollHeight = original.offsetHeight;
        let scrollTimeout;

        // Animation loop
        function animateScroll() {
            if (!isPaused && !userScrolling) {
                position += speed;
                if (position >= scrollHeight) {
                    position = 0;
                }
                scrollContainer.style.transform = `translateY(-${position}px)`;
            }
            requestAnimationFrame(animateScroll);
        }

        // Play/Pause toggle
        toggleBtn.addEventListener("click", () => {
            isPaused = !isPaused;
            toggleBtn.innerHTML = isPaused ? "▶" : "⏸";
        });

        // Manual scroll detection
        let userScrolling = false;

        outerScrollBox.addEventListener("scroll", () => {
            userScrolling = true;
            isPaused = true;

            // Reset transform to allow manual scroll (for smooth experience)
            scrollContainer.style.transform = `translateY(-${outerScrollBox.scrollTop}px)`;

            clearTimeout(scrollTimeout);
            scrollTimeout = setTimeout(() => {
                userScrolling = false;
                isPaused = false;
                position = outerScrollBox.scrollTop;
            }, 3000);
        });

        // Start scrolling
        requestAnimationFrame(animateScroll);
    </script>

    <script>
        const chhattisgarhCenter = [21.25, 82.0];
        const map = L.map('map', {
            scrollWheelZoom: false,
            zoomControl: true
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18
        }).addTo(map);

        // Load Chhattisgarh GeoJSON and fit it
        fetch("{{ asset('public/assets/geoJSON/CHHATTISGARH_STATE.geojson') }}")
            .then(res => res.json())
            .then(data => {
                const cgLayer = L.geoJSON(data, {
                    style: {
                        color: "#ff6600",
                        weight: 2,
                        fillColor: "#ffffff",
                        fillOpacity: 0
                    }
                }).addTo(map);

                // Zoom in by fitting and then increasing zoom
                map.fitBounds(cgLayer.getBounds(), {
                    padding: [20, 20]
                });

                // Then zoom in slightly
                setTimeout(() => {
                    map.setZoom(map.getZoom() + 1); // You can tweak the +1 value
                }, 500);

                // Optional: mask outside CG (need turf.js)
                const world = turf.polygon([
                    [
                        [-180, -90],
                        [180, -90],
                        [180, 90],
                        [-180, 90],
                        [-180, -90]
                    ]
                ]);
                const cgPolygon = data.features[0];
                const mask = turf.difference(world, cgPolygon);

                L.geoJSON(mask, {
                    style: {
                        fillColor: "#8ab0e9",
                        fillOpacity: 1,
                        stroke: false
                    }
                }).addTo(map);
            });


        const vendors = [{
                name: "Vendor - Raipur LED",
                lat: 21.2514,
                lng: 81.6296
            },
            {
                name: "Vendor - Bilaspur Banner",
                lat: 22.0796,
                lng: 82.1409
            },
            {
                name: "AIRPORT CHOWK ROAD NO. 8, FCNG BRIDGE",
                lat:21.164993,
                lng:81.775307
            },
            {
                name:"BUS STAND, FACING GATE",
                lat:22.0085,
                lng:81.2315
            },
            {
                name:"MAIN MARKET, PALI ROAD, FCNG CHOWK",
                lat:22.4735,
                lng:82.3187
            },
            {
                name:"CHHUIKHADAN, MAIN MARKET, PANCHAYAT, FCNG MAIN ROAD",
                lat:21.52316,
                lng:80.99788
            },
            {
                name: "DEVBHOG ROAD, NEAR MAHINDRA TRACTOR SHOWROOM, FCNG CITY",
                lat: 20.6333,
                lng: 82.0833,
            },
            {
              name: "MAIN MARKET, MARWAHI, FCNG MARKET",
              lat: 22.4167,
              lng: 81.8833
            }
        ];

        vendors.forEach((vendor, index) => {
            const marker = L.marker([vendor.lat, vendor.lng]).addTo(map);

            marker.on('mouseover', function() {
                // Dummy campaign data (you can replace with dynamic data later)
                const currentCampaign = {
                    name: "Swachh Bharat Abhiyan",
                    startDate: "2025-04-01",
                    endDate: "2025-04-30"
                };

                const pastCampaigns = [{
                        name: "Digital India",
                        startDate: "2025-03-01",
                        endDate: "2025-03-31"
                    },
                    {
                        name: "Beti Bachao",
                        startDate: "2025-02-01",
                        endDate: "2025-02-28"
                    }
                ];

                let popupHTML = `
        <div class="popup-content" style="min-width: 320px;">
            <strong style="font-size: 16px; display: block; margin-bottom: 8px;"></strong>

            <table style="width:100%; border-collapse: collapse; font-size: 13px;">

                <thead>
                     <tr style="font-weight: bold;" class="bg-gradient-info text-white">
                        <th colspan="3" style="text-align:left; padding: 5px;">
                        Hoarding info
                        </th>
                     </th>
                     <tr class="bg-gradient-danger text-white">
                        <th style="padding: 4px; border: 1px solid #ccc;">Hoarding ID</th>
                        <th style="padding: 4px; border: 1px solid #ccc;">Category</th>
                        <th style="padding: 4px; border: 1px solid #ccc;">Sub-Category</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding: 4px; border: 1px solid #ccc;">H030001811</td>
                        <td style="padding: 4px; border: 1px solid #ccc;">Unipole</td>
                        <td style="padding: 4px; border: 1px solid #ccc;">Light Pole</td>
                    </tr>
                </tbody>
                <thead class="bg-gradient-success">
                    
                    <tr class="bg-gradient-danger text-white">
                        <th style="padding: 4px; border: 1px solid #ccc;">Name</th>
                        <th style="padding: 4px; border: 1px solid #ccc;">Start</th>
                        <th style="padding: 4px; border: 1px solid #ccc;">End</th>
                    </tr>
                    <tr style="font-weight: bold;" class="bg-gradient-success text-white">
                        <th colspan="3" style="text-align:left; padding: 5px;" >Current Ongoing Campaign</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding: 4px; border: 1px solid #ccc;">${currentCampaign.name}</td>
                        <td style="padding: 4px; border: 1px solid #ccc;">${currentCampaign.startDate}</td>
                        <td style="padding: 4px; border: 1px solid #ccc;">${currentCampaign.endDate}</td>
                    </tr>
                </tbody>
                <thead>
                    <tr style=" font-weight: bold;" class="bg-gradient-success text-white">
                        <th colspan="3" style="text-align:left; padding: 5px;">Previous Campaigns</th>
                    </tr>
                </thead>
                <tbody>
                    ${pastCampaigns.map(c => `
                        <tr>
                            <td style="padding: 4px; border: 1px solid #ccc;">${c.name}</td>
                            <td style="padding: 4px; border: 1px solid #ccc;">${c.startDate}</td>
                            <td style="padding: 4px; border: 1px solid #ccc;">${c.endDate}</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        </div>
    `;

                marker.bindPopup(popupHTML).openPopup();
            });

        });

        const centerButton = document.getElementById("centerBtn");
        centerButton.addEventListener("click", () => {
            map.setView(chhattisgarhCenter, 7); // or desired zoom
        });
    </script>

@endsection