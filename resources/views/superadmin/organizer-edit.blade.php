@extends('superadmin.layout')
@section('title', 'Edit Organizer')
@section('breadcrumb_items')
    <li class="breadcrumb-item"><a href="{{ route('superadmin.organizers') }}">Organizers</a></li>
    <li class="breadcrumb-item"><a href="{{ route('superadmin.organizer.detail', $organizer->id) }}">{{ $organizer->name }}</a></li>
@endsection
@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('superadmin.organizer.detail', $organizer->id) }}" class="btn btn-sm btn-outline-secondary">
        <i class="material-symbols-outlined" style="font-size:16px;vertical-align:middle">arrow_back</i>
    </a>
    <h4 class="fw-bold mb-0">Edit: {{ $organizer->name }}</h4>
</div>

<form method="POST" action="{{ route('superadmin.organizer.update', $organizer->id) }}">
    @csrf
    @method('PATCH')

    <div class="row g-4">

        {{-- ── Account ─────────────────────────────────────────────────── --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header"><h6 class="mb-0">Account</h6></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                                   value="{{ old('username', $organizer->user->username ?? '') }}" required>
                            @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">New Password <span class="text-muted small">(leave blank to keep current)</span></label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Profile ─────────────────────────────────────────────────── --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header"><h6 class="mb-0">Profile</h6></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name', $organizer->name) }}" required>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Slug <span class="text-danger">*</span></label>
                            <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror"
                                   value="{{ old('slug', $organizer->slug) }}" required>
                            @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email', $organizer->email) }}">
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" name="phone" class="form-control"
                                   value="{{ old('phone', $organizer->phone) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Website</label>
                            <input type="text" name="website" class="form-control"
                                   value="{{ old('website', $organizer->website) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Type</label>
                            <input type="text" name="type" class="form-control"
                                   value="{{ old('type', $organizer->type) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Category</label>
                            <input type="text" name="category" class="form-control"
                                   value="{{ old('category', $organizer->category) }}">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3">{{ old('description', $organizer->description) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Address ─────────────────────────────────────────────────── --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header"><h6 class="mb-0">Address</h6></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Office Name</label>
                            <input type="text" name="office_name" class="form-control"
                                   value="{{ old('office_name', $organizer->office_name) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Address Line 1</label>
                            <input type="text" name="address_line1" class="form-control"
                                   value="{{ old('address_line1', $organizer->address_line1) }}">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Address Line 2</label>
                            <input type="text" name="address_line2" class="form-control"
                                   value="{{ old('address_line2', $organizer->address_line2) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">City</label>
                            <input type="text" name="city" class="form-control"
                                   value="{{ old('city', $organizer->city) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">State</label>
                            <input type="text" name="state" class="form-control"
                                   value="{{ old('state', $organizer->state) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Postal Code</label>
                            <input type="text" name="postal_code" class="form-control"
                                   value="{{ old('postal_code', $organizer->postal_code) }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Country</label>
                            <input type="text" name="country" class="form-control"
                                   value="{{ old('country', $organizer->country) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Latitude</label>
                            <input type="text" name="latitude" class="form-control"
                                   value="{{ old('latitude', $organizer->latitude) }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Longitude</label>
                            <input type="text" name="longitude" class="form-control"
                                   value="{{ old('longitude', $organizer->longitude) }}">
                        </div>
                        <div class="col-md-4 d-flex flex-column gap-2 justify-content-end">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_gmaps_verified" value="1" id="is_gmaps_verified"
                                       {{ old('is_gmaps_verified', $organizer->is_gmaps_verified) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_gmaps_verified">GMaps Verified</label>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="google_map_show" value="1" id="google_map_show"
                                       {{ old('google_map_show', $organizer->google_map_show) ? 'checked' : '' }}>
                                <label class="form-check-label" for="google_map_show">Show Google Map</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Messaging ───────────────────────────────────────────────── --}}
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header"><h6 class="mb-0">WhatsApp / Messaging</h6></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Fonnte Token</label>
                        <input type="text" name="fonnte_token" class="form-control"
                               value="{{ old('fonnte_token', $organizer->fonnte_token) }}">
                    </div>
                    <div class="row g-3">
                        <div class="col-6">
                            <label class="form-label">Quiet Start</label>
                            <input type="time" name="reminder_quiet_start" class="form-control"
                                   value="{{ old('reminder_quiet_start', $organizer->reminder_quiet_start) }}">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Quiet End</label>
                            <input type="time" name="reminder_quiet_end" class="form-control"
                                   value="{{ old('reminder_quiet_end', $organizer->reminder_quiet_end) }}">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Wallet ──────────────────────────────────────────────────── --}}
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-header"><h6 class="mb-0">Wallet</h6></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Balance</label>
                        <input type="number" step="0.01" name="wallet_balance" class="form-control"
                               value="{{ old('wallet_balance', $organizer->wallet_balance) }}">
                    </div>
                    <div class="mb-0">
                        <label class="form-label">Currency</label>
                        <input type="text" name="wallet_currency" class="form-control" maxlength="10"
                               value="{{ old('wallet_currency', $organizer->wallet_currency) }}" placeholder="MYR">
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Settings ────────────────────────────────────────────────── --}}
        <div class="col-12">
            <div class="card">
                <div class="card-header"><h6 class="mb-0">Settings</h6></div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Visibility</label>
                            <select name="visibility" class="form-select">
                                <option value="public" {{ old('visibility', $organizer->visibility) === 'public' ? 'selected' : '' }}>Public</option>
                                <option value="private" {{ old('visibility', $organizer->visibility) === 'private' ? 'selected' : '' }}>Private</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Booking Flow</label>
                            <select name="what_flow" class="form-select">
                                <option value="1" {{ old('what_flow', $organizer->what_flow) == 1 ? 'selected' : '' }}>1 — Default (packages + slots)</option>
                                <option value="2" {{ old('what_flow', $organizer->what_flow) == 2 ? 'selected' : '' }}>2 — Slot as Package</option>
                                <option value="3" {{ old('what_flow', $organizer->what_flow) == 3 ? 'selected' : '' }}>3 — Simple Booking</option>
                                <option value="4" {{ old('what_flow', $organizer->what_flow) == 4 ? 'selected' : '' }}>4 — No Slot Shown</option>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
                                       {{ old('is_active', $organizer->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>{{-- row --}}

    <div class="d-flex gap-2 mt-4">
        <button type="submit" class="btn btn-primary">Save Changes</button>
        <a href="{{ route('superadmin.organizer.detail', $organizer->id) }}" class="btn btn-outline-secondary">Cancel</a>
    </div>

</form>

@endsection
