@extends('superadmin.layout')
@section('title', 'Create Organizer')
@section('breadcrumb_items')
    <li class="breadcrumb-item"><a href="{{ route('superadmin.organizers') }}">Organizers</a></li>
@endsection
@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('superadmin.organizers') }}" class="btn btn-sm btn-outline-secondary">
        <i class="material-symbols-outlined" style="font-size:16px;vertical-align:middle">arrow_back</i>
    </a>
    <h4 class="fw-bold mb-0">Create Organizer</h4>
</div>

<div class="card" style="max-width: 640px;">
    <div class="card-body">
        <form method="POST" action="{{ route('superadmin.organizer.store') }}">
            @csrf

            <h6 class="fw-semibold mb-3 text-muted">Account</h6>

            <div class="mb-3">
                <label class="form-label">Username <span class="text-danger">*</span></label>
                <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                       value="{{ old('username') }}" required>
                @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Password <span class="text-danger">*</span></label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <hr class="my-4">
            <h6 class="fw-semibold mb-3 text-muted">Profile</h6>

            <div class="mb-3">
                <label class="form-label">Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email') }}">
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">City</label>
                    <input type="text" name="city" class="form-control" value="{{ old('city') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">State</label>
                    <input type="text" name="state" class="form-control" value="{{ old('state') }}">
                </div>
            </div>

            <div class="mb-4">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
                           {{ old('is_active', '1') ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Active</label>
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Create Organizer</button>
                <a href="{{ route('superadmin.organizers') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

@endsection
