@extends('superadmin.layout')
@section('title', 'Organizers')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Organizers</h4>
    <a href="{{ route('superadmin.organizer.create') }}" class="btn btn-primary btn-sm">
        <i class="material-symbols-outlined" style="font-size:16px;vertical-align:middle">add</i>
        Create Organizer
    </a>
</div>

<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" class="d-flex gap-2">
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Search name or email..." value="{{ request('search') }}">
            <button class="btn btn-sm btn-primary">Search</button>
            @if(request('search'))
                <a href="{{ route('superadmin.organizers') }}" class="btn btn-sm btn-outline-secondary">Clear</a>
            @endif
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>No.</th>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Packages</th>
                    <th>Bookings</th>
                    <th>Revenue</th>
                    <th>Wallet</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($organizers as $i => $org)
                <tr>
                    <td class="text-muted small">{{ $organizers->firstItem() + $i }}</td>
                    <td class="text-muted small font-monospace">#{{ $org->id }}</td>
                    <td>
                        <strong>{{ $org->name }}</strong>
                        @if($org->user)<div class="text-muted small">{{ $org->user->username }}</div>@endif
                    </td>
                    <td>{{ $org->email ?? '-' }}</td>
                    <td>{{ $org->phone ?? '-' }}</td>
                    <td>{{ $org->packages_count }}</td>
                    <td>{{ $org->bookings_count }}</td>
                    <td class="small">RM {{ number_format($org->revenue ?? 0, 2) }}</td>
                    <td class="small">RM {{ number_format($org->wallet_balance ?? 0, 2) }}</td>
                    <td>
                        <span class="badge bg-{{ $org->is_active ? 'success' : 'secondary' }}">
                            {{ $org->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>
                        @php
                            $waPhone = preg_replace('/\D/', '', $org->phone ?? '');
                            if (str_starts_with($waPhone, '0')) $waPhone = '60' . substr($waPhone, 1);
                            $waMsg = urlencode("Hi {$org->name}! 👋 Saya dari SistemKami — platform pengurusan tempahan & pakej perniagaan anda secara online.\n\nKami ingin memperkenalkan SistemKami kepada anda dan menjemput anda untuk sesi demo percuma melalui Google Meet.\n\nAdakah anda berminat? Bila masa yang sesuai untuk anda? 🙏");
                        @endphp
                        <div class="dropdown">
                            <button class="btn btn-primary btn-sm dropdown-toggle" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Actions
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="{{ route('superadmin.organizer.detail', $org->id) }}" class="dropdown-item">
                                        <i class="material-symbols-outlined me-1" style="font-size:15px;vertical-align:middle">visibility</i> Detail
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('superadmin.organizer.edit', $org->id) }}" class="dropdown-item">
                                        <i class="fas fa-edit me-1"></i> Edit
                                    </a>
                                </li>
                                @if($org->phone)
                                <li>
                                    <a href="https://api.whatsapp.com/send?phone=+{{ $waPhone }}&text={{ $waMsg }}" target="_blank" class="dropdown-item text-success">
                                        <i class="fab fa-whatsapp me-1"></i> WhatsApp
                                    </a>
                                </li>
                                @endif
                                <li>
                                    <a href="{{ route('superadmin.impersonate', $org->id) }}" target="_blank" class="dropdown-item"
                                       data-confirm="Login as {{ $org->name }}?">
                                        <i class="fas fa-user-secret me-1"></i> Login As
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('superadmin.organizer.destroy', $org->id) }}"
                                          data-confirm="Archive {{ $org->name }}? They can be restored later.">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="material-symbols-outlined me-1" style="font-size:15px;vertical-align:middle">archive</i> Archive
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="11" class="text-center text-muted py-4">No organizers found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($organizers->hasPages())
    <div class="card-footer">
        {{ $organizers->links() }}
    </div>
    @endif
</div>

@endsection
