@if(session('success'))
    <div style="color:green">{{ session('success') }}</div>
@endif

{{-- Display Package Images --}}
@if(session('package_images'))
    <h4>Package Images</h4>
    <div class="d-flex gap-2 mb-3">
        @foreach(session('package_images') as $img)
            <img src="{{ $img }}" class="d-block" style="height:160px; object-fit:cover;">
        @endforeach
    </div>
@endif

{{-- Display Slot Images --}}
@if(session('slot_images'))
    <h4>Slot Images</h4>
    <div class="d-flex gap-2 mb-3">
        @foreach(session('slot_images') as $img)
            <img src="{{ $img }}" class="d-block" style="height:160px; object-fit:cover;">
        @endforeach
    </div>
@endif

<form action="{{ route('superadmin.upload-image') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <label>Organizer Id:</label>
    <input name="organizer_id" type="number" required>

    <label>Package Id:</label>
    <input name="package_id" type="number" required>

    <label>Type:</label>
    <select name="type" required>
        <option value="package">Package</option>
        <option value="slot">Slot</option>
    </select>

    <label>File Name (Optional):</label>
    <input type="text" name="filename" placeholder="seri-lebaran-0">

    <label>Gambar:</label>
    <input type="file" name="image" required>

    <button type="submit">Upload</button>
</form>
