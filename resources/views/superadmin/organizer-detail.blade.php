@extends('superadmin.layout')
@section('title', $organizer->name)
@section('breadcrumb_items')
    <li class="breadcrumb-item"><a href="{{ route('superadmin.organizers') }}">Organizers</a></li>
@endsection
@section('content')

<div class="d-flex align-items-center gap-3 mb-4">
    <a href="{{ route('superadmin.organizers') }}" class="btn btn-sm btn-outline-secondary">
        <i class="material-symbols-outlined" style="font-size:16px;vertical-align:middle">arrow_back</i>
    </a>
    <h4 class="fw-bold mb-0">{{ $organizer->name }}</h4>
    <div class="ms-auto d-flex gap-2 flex-wrap">
        <a href="{{ route('superadmin.organizer.edit', $organizer->id) }}" class="btn btn-sm btn-outline-primary">
            <i class="material-symbols-outlined" style="font-size:16px;vertical-align:middle">edit</i>
            Edit
        </a>
        @if($organizer->phone)
        @php
            $waPhone = preg_replace('/\D/', '', $organizer->phone);
            if (str_starts_with($waPhone, '0')) $waPhone = '60' . substr($waPhone, 1);
            $waMsg = urlencode("Hi {$organizer->name}! 👋 Saya dari SistemKami — platform pengurusan tempahan & pakej perniagaan anda secara online.\n\nKami ingin memperkenalkan SistemKami kepada anda dan menjemput anda untuk sesi demo percuma melalui Google Meet.\n\nAdakah anda berminat? Bila masa yang sesuai untuk anda? 🙏");
        @endphp
        <a href="https://api.whatsapp.com/send?phone=+{{ $waPhone }}&text={{ $waMsg }}" target="_blank" class="btn btn-sm btn-success">
            <i class="fab fa-whatsapp me-1"></i>WhatsApp
        </a>
        @endif
        <a href="{{ route('superadmin.impersonate', $organizer->id) }}" target="_blank" class="btn btn-warning btn-sm"
           data-confirm="Login as {{ $organizer->name }}?">
            <i class="fas fa-user-secret me-1"></i>Login As
        </a>
        <form method="POST" action="{{ route('superadmin.organizer.destroy', $organizer->id) }}"
              data-confirm="Archive {{ $organizer->name }}? They can be restored later.">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger">
                <i class="material-symbols-outlined" style="font-size:16px;vertical-align:middle">archive</i>
                Archive
            </button>
        </form>
    </div>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <div class="fs-3 fw-bold text-primary">{{ $bookingStats['total'] }}</div>
                <div class="text-muted small">Total Bookings</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <div class="fs-3 fw-bold text-success">{{ $bookingStats['confirmed'] }}</div>
                <div class="text-muted small">Confirmed</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <div class="fs-3 fw-bold text-warning">{{ $bookingStats['pending'] }}</div>
                <div class="text-muted small">Pending</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <div class="fs-3 fw-bold text-info">RM {{ number_format($bookingStats['revenue'], 2) }}</div>
                <div class="text-muted small">Revenue</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    {{-- Info --}}
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header"><h6 class="mb-0">Organizer Info</h6></div>
            <div class="card-body small">
                <table class="table table-sm table-borderless mb-0">
                    <tr><th class="text-muted w-40">ID</th><td>{{ $organizer->id }}</td></tr>
                    <tr><th class="text-muted">Slug</th><td>{{ $organizer->slug }}</td></tr>
                    <tr><th class="text-muted">Email</th><td>{{ $organizer->email ?? '-' }}</td></tr>
                    <tr><th class="text-muted">Phone</th><td>{{ $organizer->phone ?? '-' }}</td></tr>
                    <tr><th class="text-muted">City</th><td>{{ $organizer->city ?? '-' }}</td></tr>
                    <tr><th class="text-muted">State</th><td>{{ $organizer->state ?? '-' }}</td></tr>
                    <tr><th class="text-muted">Status</th><td>
                        <span class="badge bg-{{ $organizer->is_active ? 'success' : 'secondary' }}">
                            {{ $organizer->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td></tr>
                    <tr><th class="text-muted">Wallet</th><td>RM {{ number_format($organizer->wallet_balance, 2) }}</td></tr>
                    @if($organizer->user)
                    <tr><th class="text-muted">User</th><td>{{ $organizer->user->username }}</td></tr>
                    @endif
                    <tr><th class="text-muted">Created</th><td>{{ $organizer->created_at->format('d M Y') }}</td></tr>
                </table>
            </div>
        </div>
    </div>

    {{-- Packages --}}
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><h6 class="mb-0">Packages ({{ $organizer->packages->count() }})</h6></div>
            <div class="card-body p-0">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Name</th><th>Status</th><th>Base Price</th><th>Bookings</th></tr>
                    </thead>
                    <tbody>
                        @forelse($organizer->packages as $pkg)
                        <tr>
                            <td>{{ $pkg->name }}</td>
                            <td><span class="badge bg-{{ $pkg->status === 'active' ? 'success' : 'secondary' }}">{{ $pkg->status }}</span></td>
                            <td>RM {{ number_format($pkg->base_price, 2) }}</td>
                            <td>{{ $pkg->bookings_count ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted py-2">No packages.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Recent Bookings --}}
<div class="card mt-3">
    <div class="card-header"><h6 class="mb-0">Recent Bookings</h6></div>
    <div class="card-body p-0">
        <table class="table table-sm table-hover mb-0">
            <thead class="table-light">
                <tr><th>Code</th><th>Participant</th><th>Package</th><th>Amount</th><th>Status</th><th>Date</th></tr>
            </thead>
            <tbody>
                @forelse($recentBookings as $bk)
                <tr>
                    <td class="font-monospace small">{{ $bk->booking_code }}</td>
                    <td>{{ $bk->participant->name ?? '-' }}</td>
                    <td>{{ $bk->package->name ?? '-' }}</td>
                    <td>RM {{ number_format($bk->final_price, 2) }}</td>
                    <td><span class="badge bg-{{ $bk->status === 'confirmed' ? 'success' : ($bk->status === 'pending' ? 'warning' : 'secondary') }}">{{ $bk->status }}</span></td>
                    <td>{{ $bk->created_at->format('d M Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-2">No bookings.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
