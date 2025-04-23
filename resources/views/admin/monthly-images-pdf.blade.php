    <!DOCTYPE html>
<html>
<head>
    <title>Monthly Images Report</title>
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid black; padding: 10px; text-align: center; }
        img { max-width: 100px; height: auto; }
    .thm-tbl thead, .thm-tbl tbody{
    text-align:center;
}
.thm-tbl thead th{
  border:1px solid #fff;
     background-color: #0D6EFD;
  vertical-align: middle;
  color:#fff;
  text-align:center;
}
.thm-tbl tbody td{
    border:1px solid #0d6dfd5c;
    text-align:center;
}
.thm-tbl tbody th{
    border:1px solid #0d6dfd5c;
    text-align:center;
}

.select-img-preview{
    padding:3px;
    object-fit:cover;
    border:1px dashed #0D6EFD;
    border-radius:2px;
    margin-top:10px;
    margin-right:10px;
    box-shadow:1px 1px 2px grey;
}
    </style>
</head>
<body>
    <h2  style="color:#0D6EFD; text-align:center;">Monthly Images Report - {{ now()->format('F Y') }}</h2>
    <table class="thm-tbl">
        <thead>
            <tr>
                <th>Field Agent</th>
                <th>Image</th>
                <th>Latitude</th>
                <th>Longtitude</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($imageuploadcamps as $imageuploadcamp)
            <tr>
                <td>{{ $imageuploadcamp->fieldagent->name }}</td>
                <td>
                    @if(!empty($imageuploadcamp->image))
                        <img src="{{ public_path('uploads/' . basename($imageuploadcamp->image)) }}" alt="Uploaded Image" class="select-img-preview">
                    @else
                        No Image
                    @endif
                </td>
                <td>{{ $imageuploadcamp->latitude }}</td>
                <td>{{ $imageuploadcamp->longtitude }}</td>
                <td>{{ $imageuploadcamp->created_at->format('d-m-Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

