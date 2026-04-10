@extends('layouts.admin.default')
@push('styles')
<style>
    .group-header { background: #f0f4ff; }
    .group-badge  { font-size: .7rem; letter-spacing: .04em; }
    .collapse-icon { transition: transform .2s; display: inline-block; }
    .status-toggle { min-width: 80px; font-size: .75rem; }
    .section-title {
        font-size: .7rem;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: #6c757d;
        padding: .5rem 1rem;
        background: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }
</style>
@endpush
@section('content')
<div class="container-fluid">

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- ── FILTERS ─────────────────────────────────────────────────── --}}
    <div class="card mb-3">
        <div class="card-body py-2">
            <form method="GET" id="filterForm" class="row g-2 align-items-center">
                <div class="col-auto">
                    <select name="status" onchange="this.form.submit()" class="form-select form-select-sm">
                        <option value="">All Status</option>
                        <option value="active"   {{ request('status') == 'active'   ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="draft"    {{ request('status') == 'draft'    ? 'selected' : '' }}>Draft</option>
                    </select>
                </div>
                <div class="col-auto">
                    <select name="category_search" onchange="this.form.submit()" class="form-select form-select-sm">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->name }}"
                                {{ request('category_search') == $category->name ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-auto">
                    <input type="text" name="search" value="{{ request('search') }}"
                        class="form-control form-control-sm" placeholder="Search package..." style="min-width:180px">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                    <a href="{{ route(Route::currentRouteName()) }}" class="btn btn-sm btn-outline-secondary">Clear</a>
                </div>
                <div class="col-auto ms-auto">
                    <a href="{{ route('organizer.business.package.create') }}?is_group=1" class="btn btn-sm btn-outline-primary me-1">
                        <i class="fas fa-layer-group me-1"></i> New Group
                    </a>
                    <a href="{{ route('organizer.business.package.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus me-1"></i> New Package
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════ --}}
    {{-- SECTION 1 — PACKAGE GROUPS                                    --}}
    {{-- ══════════════════════════════════════════════════════════════ --}}
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-layer-group text-primary me-2"></i> Package Groups</h5>
            <span class="badge bg-primary">{{ $groups->count() }} group(s)</span>
        </div>

        @if ($groups->isEmpty())
            <div class="card-body text-center text-muted py-4">
                No groups yet.
                <a href="{{ route('organizer.business.package.create') }}?is_group=1">Create one</a>.
            </div>
        @else
            @foreach ($groups as $group)
                @php $childCount = $group->children->count(); @endphp

                {{-- Group header row --}}
                <div class="section-title d-flex justify-content-between align-items-center">
                    <div>
                        <button type="button" class="btn btn-link p-0 me-2 text-dark text-decoration-none collapse-toggle"
                            data-bs-toggle="collapse" data-bs-target="#group-{{ $group->id }}">
                            <i class="fas fa-chevron-down collapse-icon" id="icon-{{ $group->id }}"></i>
                        </button>
                        <i class="fas fa-layer-group text-primary me-1"></i>
                        <strong>{{ $group->name }}</strong>
                        <span class="ms-2 text-muted fw-normal">{{ $childCount }} package(s)</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        {{-- Group status toggle --}}
                        <button type="button"
                            class="btn btn-sm status-toggle {{ $group->status === 'active' ? 'btn-success' : ($group->status === 'draft' ? 'btn-warning' : 'btn-secondary') }}"
                            data-id="{{ $group->id }}"
                            data-url="{{ route('organizer.business.package.toggle-status', $group->id) }}"
                            onclick="toggleStatus(this)">
                            {{ ucfirst($group->status) }}
                        </button>
                        <a href="{{ route('organizer.business.package.edit', $group->id) }}"
                            class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-edit me-1"></i> Edit
                        </a>
                        <form action="{{ route('organizer.business.package.destroy', $group->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button type="button" class="btn btn-sm btn-outline-danger btn-delete" data-name="{{ $group->name }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Children table --}}
                <div class="collapse show" id="group-{{ $group->id }}">
                    @if ($childCount > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:40px">#</th>
                                        <th style="width:60px">Image</th>
                                        <th style="width:50px">Order</th>
                                        <th style="width:120px">Category</th>
                                        <th>Package Name</th>
                                        <th style="width:110px">Base Price</th>
                                        <th style="width:110px">Final Price</th>
                                        <th style="width:90px">Deposit</th>
                                        <th style="width:110px">Status</th>
                                        <th style="width:120px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($group->children as $ci => $child)
                                        @php $cover = $child->images->firstWhere('is_cover', true) ?? $child->images->first(); @endphp
                                        <tr>
                                            <td class="text-muted">{{ $ci + 1 }}</td>
                                            <td>
                                                @if ($cover)
                                                    <img src="{{ asset('storage/uploads/' . $authUser->id . '/packages/' . $child->id . '/' . $cover->url) }}"
                                                        style="width:44px;height:44px;object-fit:cover;border-radius:5px;">
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
                                            </td>
                                            <td>{{ $child->order_by }}</td>
                                            <td>{{ $child->category->name ?? '—' }}</td>
                                            <td>
                                                {{ $child->name }}
                                                @if ($child->package_code)
                                                    <span class="text-muted small ms-1">({{ $child->package_code }})</span>
                                                @endif
                                            </td>
                                            <td>RM {{ number_format($child->base_price, 2) }}</td>
                                            <td>RM {{ number_format($child->final_price, 2) }}</td>
                                            <td>
                                                @if ($child->deposit_percentage)
                                                    {{ (int) $child->deposit_percentage }}%
                                                @elseif ($child->deposit_fixed)
                                                    RM{{ (int) $child->deposit_fixed }}
                                                @else
                                                    —
                                                @endif
                                            </td>
                                            <td>
                                                <button type="button"
                                                    class="btn btn-sm status-toggle {{ $child->status === 'active' ? 'btn-success' : ($child->status === 'draft' ? 'btn-warning' : 'btn-secondary') }}"
                                                    data-id="{{ $child->id }}"
                                                    data-url="{{ route('organizer.business.package.toggle-status', $child->id) }}"
                                                    onclick="toggleStatus(this)">
                                                    {{ ucfirst($child->status) }}
                                                </button>
                                            </td>
                                            <td>
                                                <a href="{{ route('organizer.business.package.edit', $child->id) }}"
                                                    class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('organizer.business.package.destroy', $child->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-outline-danger btn-delete"
                                                        data-name="{{ $child->name }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-muted small px-4 py-2">No packages in this group yet.</div>
                    @endif
                </div>
            @endforeach
        @endif
    </div>

    {{-- ══════════════════════════════════════════════════════════════ --}}
    {{-- SECTION 2 — UNGROUPED PACKAGES                                --}}
    {{-- ══════════════════════════════════════════════════════════════ --}}
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fas fa-box text-secondary me-2"></i> Ungrouped Packages</h5>
            <span class="badge bg-secondary">{{ $standalone->total() }} package(s)</span>
        </div>

        @if ($standalone->isEmpty())
            <div class="card-body text-center text-muted py-4">
                No standalone packages.
                <a href="{{ route('organizer.business.package.create') }}">Create one</a>.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:40px">#</th>
                            <th style="width:60px">Image</th>
                            <th style="width:50px">Order</th>
                            <th style="width:130px">Category</th>
                            <th>Package Name</th>
                            <th style="width:110px">Base Price</th>
                            <th style="width:70px">Disc%</th>
                            <th style="width:110px">Final Price</th>
                            <th style="width:90px">Deposit</th>
                            <th style="width:110px">Status</th>
                            <th style="width:120px">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($standalone as $index => $package)
                            @php $cover = $package->images->firstWhere('is_cover', true) ?? $package->images->first(); @endphp
                            <tr>
                                <td class="text-muted">{{ $standalone->firstItem() + $index }}</td>
                                <td>
                                    @if ($cover)
                                        <img src="{{ asset('storage/uploads/' . $authUser->id . '/packages/' . $package->id . '/' . $cover->url) }}"
                                            style="width:48px;height:48px;object-fit:cover;border-radius:6px;">
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>{{ $package->order_by }}</td>
                                <td>{{ $package->category->name ?? '—' }}</td>
                                <td>
                                    {{ $package->name }}
                                    @if ($package->package_code)
                                        <span class="text-muted small ms-1">({{ $package->package_code }})</span>
                                    @endif
                                </td>
                                <td>RM {{ number_format($package->base_price, 2) }}</td>
                                <td>{{ $package->discount_percentage ? $package->discount_percentage . '%' : '—' }}</td>
                                <td>RM {{ number_format($package->final_price, 2) }}</td>
                                <td>
                                    @if ($package->deposit_percentage)
                                        {{ (int) $package->deposit_percentage }}%
                                    @elseif ($package->deposit_fixed)
                                        RM{{ (int) $package->deposit_fixed }}
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>
                                    <button type="button"
                                        class="btn btn-sm status-toggle {{ $package->status === 'active' ? 'btn-success' : ($package->status === 'draft' ? 'btn-warning' : 'btn-secondary') }}"
                                        data-id="{{ $package->id }}"
                                        data-url="{{ route('organizer.business.package.toggle-status', $package->id) }}"
                                        onclick="toggleStatus(this)">
                                        {{ ucfirst($package->status) }}
                                    </button>
                                </td>
                                <td>
                                    <a href="{{ route('organizer.business.package.edit', $package->id) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('organizer.business.package.destroy', $package->id) }}"
                                        method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete"
                                            data-name="{{ $package->name }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="card-footer">
                {{ $standalone->withQueryString()->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script>
    // ── Status toggle (AJAX) ──────────────────────────────────────────
    const CSRF = '{{ csrf_token() }}';

    function toggleStatus(btn) {
        btn.disabled = true;
        const url = btn.dataset.url;

        fetch(url, {
            method: 'PATCH',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
        })
        .then(r => r.json())
        .then(data => {
            btn.textContent = data.label;
            btn.className = 'btn btn-sm status-toggle ' + (
                data.status === 'active'   ? 'btn-success'   :
                data.status === 'draft'    ? 'btn-warning'   : 'btn-secondary'
            );
            btn.disabled = false;
        })
        .catch(() => { btn.disabled = false; });
    }

    // ── Collapse chevron ──────────────────────────────────────────────
    document.querySelectorAll('.collapse-toggle').forEach(function (btn) {
        const target = document.querySelector(btn.getAttribute('data-bs-target'));
        if (!target) return;
        const icon = btn.querySelector('.collapse-icon');
        target.addEventListener('hide.bs.collapse', function () { icon.style.transform = 'rotate(-90deg)'; });
        target.addEventListener('show.bs.collapse', function () { icon.style.transform = 'rotate(0deg)'; });
    });

    // ── Delete confirmation ───────────────────────────────────────────
    document.querySelectorAll('.btn-delete').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const name = this.dataset.name;
            const form = this.closest('form');
            Swal.fire({
                title: 'Are you sure?',
                text: `Delete "${name}"? This cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!'
            }).then(result => { if (result.isConfirmed) form.submit(); });
        });
    });
</script>
@endpush
