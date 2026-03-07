@extends('superadmin.layout')
@section('title', 'Upload Image')
@section('content')

<h4 class="fw-bold mb-4">Upload Image</h4>

<div class="card" style="max-width: 600px;">
    <div class="card-body">

        @if(session('package_images'))
            <h6>Package Images</h6>
            <div class="d-flex flex-wrap gap-2 mb-3">
                @foreach(session('package_images') as $img)
                    <img src="{{ $img }}" style="height:120px; object-fit:cover; border-radius:6px;">
                @endforeach
            </div>
        @endif

        @if(session('slot_images'))
            <h6>Slot Images</h6>
            <div class="d-flex flex-wrap gap-2 mb-3">
                @foreach(session('slot_images') as $img)
                    <img src="{{ $img }}" style="height:120px; object-fit:cover; border-radius:6px;">
                @endforeach
            </div>
        @endif

        <form action="{{ route('superadmin.upload-image.post') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label">Organizer ID</label>
                <input name="organizer_id" type="number" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Package ID</label>
                <input name="package_id" type="number" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Type</label>
                <select name="type" class="form-select" required>
                    <option value="packages">Package</option>
                    <option value="slots">Slot</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">File Name (Optional)</label>
                <input type="text" name="filename" class="form-control" placeholder="seri-lebaran-0">
            </div>
            <div class="mb-3">
                <label class="form-label">Image</label>
                <input type="file" name="image" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>
    </div>
</div>

@endsection
