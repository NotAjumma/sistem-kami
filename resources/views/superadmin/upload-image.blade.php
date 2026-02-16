@if(session('success'))
    <div style="color:green">{{ session('success') }}</div>
    @if(session('url'))
        <img src="{{ session('url') }}" style="height:260px; object-fit:cover;">
    @endif
@endif

<form action="{{ route('superadmin.upload-image') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <label>Organizer Id:</label>
    <input name="organizer_id" type="number" required>

    <label>Package Id:</label>
    <input name="package_id" type="number" required>

    <label>File Name (Optional):</label>
    <input type="text" name="filename" placeholder="seri-lebaran-0">
    
    <label>Gambar:</label>
    <input type="file" name="image" required>

    <button type="submit">Upload</button>
</form>
