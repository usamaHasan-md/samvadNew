@forelse($filteredData as $key => $data)
    <tr>
        <td>{{ $key + 1 }}</td>
        <td>{{ $data->hoarding_id }}</td>
        <td>{{ $data->campaign_name }}</td>
        <td>{{ $data->category }}</td>
        <td>{{ $data->sub_category }}</td>
        <td>{{ \Carbon\Carbon::parse($data->start_date)->format('d-m-Y') }}</td>
        <td>{{ \Carbon\Carbon::parse($data->end_date)->format('d-m-Y') }}</td>
        <td>{{ $data->state }}</td>
        <td>{{ $data->district }}</td>
        <td>{{ $data->district_area }}</td>
        <td>{{ $data->location_address }}</td>
        <td style="min-width:740px;"> 
            <div class="row">
                @if($data->images->count())
                    @foreach($data->images as $img)
                <div class="col-2 mx-auto">
                    <div class="card shadow text-center p-2 mb-2" style="width:105px;">
                        
                        <a data-fancybox="gallery" class="w-100" href="{{ asset('public/' . $img['image']) }}">
                            
<img src="{{ asset('public/' . $img['image']) }}" style="width:90%; height:60px;" class="mx-auto ">
</a>
                        <!--<small style="font-size:11px;">-->
                        <!--    {{ $img['date'] ?? 'N/A' }} <br>-->
                        <!--    Lat: {{ $img['latitude'] ?? 'N/A' }} <br>-->
                        <!--    Long: {{ $img['longtitude'] ?? 'N/A' }}-->
                        <!--</small>-->
                    </div>
                </div>
                   @endforeach
                @else
                N/A
                @endif
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="12" class="text-center">No data available</td>
    </tr>
@endforelse