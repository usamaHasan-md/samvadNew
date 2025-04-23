@extends('layout.main')

@section('content')
<div class="card thm-card">
    <div class="card-header thm-card-head bg-gradient-danger d-flex flex-wrap align-items-center justify-content-between">
        <h5 class="card-title thm-card-title ms-0">
            Campaign List<i class="fa fa-list ms-2"></i>
        </h5>
       <a href="{{route('vendor.dashboard')}}" class="btn btn-light btn-sm text-primary">Back<i class="fa fa-arrow-left ms-1"></i></a>
    </div>
    <div class="card-body">
        <!--<div class="d-flex align-items-center">-->
            <!--<h4 class="mb-3">Compaign History</h4>-->
            @php
            $sr=1;
            @endphp
        <!--</div>-->
        <div class="table-responsive mt-3">
            <table class="table align-middle thm-tbl text-center" id="example">
                <thead class="table-secondary">
                    <tr>
                        <th>Sr.</th>
                        <th>Campaign Name</th>
                        <!--<th>Assigned Field Agents</th>-->
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($campaignFieldagents as $campaignFieldagent)
                    <tr>
                        <td>{{ $sr++ }}</td>
                        <td>{{ $campaignFieldagent->campaign_name }}</td>
                        <!--<td>{{ $campaignFieldagent->fieldagent_names }}</td>-->
                        <td>04-04-2025</td>
                        <td>15-04-2025</td>
                        <td>
                            <a href="{{ route('FieldAgentCampaign.View', ['campaign_id' => $campaignFieldagent->campaign_id]) }}" class="btn btn-primary">View Campaign</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
