@extends('layout.main')
@section('content')
<div class="container">
    <h2>Image Verification List</h2>

    @foreach($images as $image)
        <div class="card mb-3">
            <div class="card-body">
                <img src="{{ asset($image->image_path) }}" alt="Uploaded Image" width="300"><br>
                <strong>Field Agent ID:</strong> {{ $image->fieldagent_id }}<br>
                <strong>Latitude:</strong> {{ $image->latitude }}<br>
                <strong>Longitude:</strong> {{ $image->longtitude }}<br>
                <strong>Date:</strong> {{ $image->date }}<br>

                <form action="{{ route('vendor.image.verify', $image->id) }}" method="POST" class="mt-3">
                    @csrf
                    <div class="form-group">
                        <label>Verification Status:</label><br>
                        <label><input type="radio" name="is_verified" value="1" required> Verify</label>
                        <label class="ml-3"><input type="radio" name="is_verified" value="0"> Reject</label>
                    </div>
                    <div class="form-group mt-2">
                        <label for="vendor_remarks">Remarks:</label>
                        <textarea name="vendor_remarks" class="form-control" rows="2"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success mt-2">Submit</button>
                </form>
            </div>
        </div>
    @endforeach
</div>
@endsection
