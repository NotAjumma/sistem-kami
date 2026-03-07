@extends('layouts.admin.default')

@push('styles')
<style>
    .slot-thumb {
        width: 72px;
        height: 72px;
        object-fit: cover;
        border-radius: 6px;
        border: 2px solid transparent;
    }
    .slot-thumb.is-cover {
        border-color: #4caf50;
    }
    .img-wrap {
        position: relative;
        display: inline-block;
        margin: 4px;
    }
    .img-wrap .btn-del-img {
        position: absolute;
        top: -6px;
        right: -6px;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        padding: 0;
        font-size: 10px;
        line-height: 20px;
        text-align: center;
    }
    .cover-badge {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(76,175,80,0.85);
        color: #fff;
        font-size: 9px;
        text-align: center;
        border-radius: 0 0 4px 4px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Create Form --}}
    <div class="card mb-4">
        <div class="card-header d-flex align-items-center">
            <i class="material-symbols-outlined me-2">add_circle</i>
            <h5 class="card-title mb-0">Add Time Slot</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('organizer.business.time-slots.store') }}" method="POST">
                @csrf
                <div class="row g-3">

                    <div class="col-md-3">
                        <label class="form-label small fw-semibold">Slot Name <span class="text-danger">*</span></label>
                        <input type="text" name="slot_name"
                            class="form-control form-control-sm @error('slot_name') is-invalid @enderror"
                            value="{{ old('slot_name') }}" placeholder="e.g. Morning, Slot A" required>
                        @error('slot_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    @if ($authUser->what_flow == 2)
                    <div class="col-md-2">
                        <label class="form-label small fw-semibold">Slot Price (RM)</label>
                        <input type="number" name="slot_price" class="form-control form-control-sm"
                            value="{{ old('slot_price', 0) }}" min="0" step="0.01">
                    </div>

                    <div class="col-md-1">
                        <label class="form-label small fw-semibold">Max Pax</label>
                        <input type="number" name="pax" class="form-control form-control-sm"
                            value="{{ old('pax') }}" min="1" placeholder="—">
                    </div>
                    @endif

                    <div class="col-md-1 d-flex align-items-end">
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="checkbox" name="is_full_day" id="create_is_full_day"
                                value="1" {{ old('is_full_day') ? 'checked' : '' }}
                                onchange="toggleCreateTimeFields(this)">
                            <label class="form-check-label small" for="create_is_full_day">Full Day</label>
                        </div>
                    </div>

                    <div class="create-time-field col-md-2" id="create_start_time_col">
                        <label class="form-label small fw-semibold">Start Time</label>
                        <input type="time" name="start_time" class="form-control form-control-sm"
                            value="{{ old('start_time') }}">
                    </div>

                    <div class="create-time-field col-md-2" id="create_end_time_col">
                        <label class="form-label small fw-semibold">End Time</label>
                        <input type="time" name="end_time" class="form-control form-control-sm"
                            value="{{ old('end_time') }}">
                    </div>

                    @if ($authUser->what_flow == 2)
                    <div class="create-time-field col-md-1">
                        <label class="form-label small fw-semibold">Duration (min)</label>
                        <input type="number" name="duration_minutes" class="form-control form-control-sm"
                            value="{{ old('duration_minutes') }}" min="1">
                    </div>

                    <div class="create-time-field col-md-1">
                        <label class="form-label small fw-semibold">Rest (min)</label>
                        <input type="number" name="rest_minutes" class="form-control form-control-sm"
                            value="{{ old('rest_minutes', 0) }}" min="0">
                    </div>
                    @endif

                    <div class="col-md-3 d-flex align-items-end gap-3">
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="checkbox" name="is_multiple" id="create_is_multiple"
                                value="1" {{ old('is_multiple') ? 'checked' : '' }}>
                            <label class="form-check-label small" for="create_is_multiple">Multiple Bookings</label>
                        </div>
                        <div class="form-check mb-1">
                            <input class="form-check-input" type="checkbox" name="is_active" id="create_is_active"
                                value="1" checked>
                            <label class="form-check-label small" for="create_is_active">Active</label>
                        </div>
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-sm w-100">
                            <i class="fas fa-plus me-1"></i> Add Slot
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

    {{-- Time Slots List --}}
    <div class="card">
        <div class="card-header d-flex align-items-center">
            <i class="material-symbols-outlined me-2">schedule</i>
            <h5 class="card-title mb-0">Time Slots ({{ $timeSlots->count() }})</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table">
                        <tr>
                            <th>#</th>
                            <th>Slot Name</th>
                            <th>Type</th>
                            <th>Time</th>
                            @if ($authUser->what_flow == 2)
                            <th>Duration</th>
                            <th>Rest</th>
                            <th>Pax</th>
                            <th>Price</th>
                            @endif
                            <th>Multiple</th>
                            <th>Active</th>
                            <th>Images</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($timeSlots as $i => $slot)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td class="fw-semibold">{{ $slot->slot_name }}</td>
                            <td>
                                @if ($slot->is_full_day)
                                    <span class="badge bg-info text-dark">Full Day</span>
                                @else
                                    <span class="badge bg-secondary">Timed</span>
                                @endif
                            </td>
                            <td>
                                @if (!$slot->is_full_day && $slot->start_time)
                                    {{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }}
                                    @if ($slot->end_time)
                                        &ndash; {{ \Carbon\Carbon::parse($slot->end_time)->format('h:i A') }}
                                    @endif
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            @if ($authUser->what_flow == 2)
                            <td>{{ $slot->duration_minutes ? $slot->duration_minutes . ' min' : '—' }}</td>
                            <td>{{ $slot->rest_minutes ? $slot->rest_minutes . ' min' : '—' }}</td>
                            <td>{{ $slot->pax ?? '—' }}</td>
                            <td>{{ $slot->slot_price > 0 ? 'RM ' . number_format($slot->slot_price, 2) : '—' }}</td>
                            @endif
                            <td>
                                <span class="badge {{ $slot->is_multiple ? 'bg-success' : 'bg-light text-dark' }}">
                                    {{ $slot->is_multiple ? 'Yes' : 'No' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $slot->is_active ? 'bg-success' : 'bg-danger' }}">
                                    {{ $slot->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                @if ($slot->images->count())
                                    @php $cover = $slot->images->firstWhere('is_cover', true) ?? $slot->images->first(); @endphp
                                    <img src="{{ asset('storage/uploads/' . $authUser->id . '/slots/' . $slot->id . '/' . $cover->url) }}"
                                        class="slot-thumb" title="{{ $slot->images->count() }} image(s)">
                                    @if ($slot->images->count() > 1)
                                        <span class="badge bg-secondary ms-1">+{{ $slot->images->count() - 1 }}</span>
                                    @endif
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td class="position-relative">
                                <div class="dropdown">
                                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false" data-bs-display="static">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <button class="dropdown-item"
                                                onclick="openEditModal({{ $slot->id }}, @json($slot->toArray()))">
                                                <i class="fas fa-edit me-1"></i> Edit
                                            </button>
                                        </li>
                                        <li>
                                            <form action="{{ route('organizer.business.time-slots.destroy', $slot->id) }}"
                                                method="POST" class="d-inline" id="del-slot-{{ $slot->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="dropdown-item text-danger btn-del-slot"
                                                    data-slot-id="{{ $slot->id }}"
                                                    data-slot-name="{{ $slot->slot_name }}">
                                                    <i class="fas fa-trash me-1"></i> Delete
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="99" class="text-center text-muted py-4">
                                No time slots yet. Add one above.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

{{-- Edit Modal --}}
<div class="modal fade" id="editSlotModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editSlotForm" method="POST">
                @csrf
                @method('PATCH')

                <div class="modal-header">
                    <h5 class="modal-title">Edit Time Slot</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">

                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Slot Name <span class="text-danger">*</span></label>
                            <input type="text" name="slot_name" id="edit_slot_name" class="form-control form-control-sm" required>
                        </div>

                        @if ($authUser->what_flow == 2)
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Slot Price (RM)</label>
                            <input type="number" name="slot_price" id="edit_slot_price" class="form-control form-control-sm" min="0" step="0.01">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label small fw-semibold">Max Pax</label>
                            <input type="number" name="pax" id="edit_pax" class="form-control form-control-sm" min="1">
                        </div>
                        @endif

                        <div class="col-md-3 d-flex align-items-end">
                            <div class="form-check mb-1">
                                <input class="form-check-input" type="checkbox" name="is_full_day" id="edit_is_full_day"
                                    value="1" onchange="toggleEditTimeFields(this)">
                                <label class="form-check-label small" for="edit_is_full_day">Full Day</label>
                            </div>
                        </div>

                        <div class="edit-time-field col-md-3">
                            <label class="form-label small fw-semibold">Start Time</label>
                            <input type="time" name="start_time" id="edit_start_time" class="form-control form-control-sm">
                        </div>

                        <div class="edit-time-field col-md-3">
                            <label class="form-label small fw-semibold">End Time</label>
                            <input type="time" name="end_time" id="edit_end_time" class="form-control form-control-sm">
                        </div>

                        @if ($authUser->what_flow == 2)
                        <div class="edit-time-field col-md-3">
                            <label class="form-label small fw-semibold">Duration (min)</label>
                            <input type="number" name="duration_minutes" id="edit_duration_minutes" class="form-control form-control-sm" min="1">
                        </div>

                        <div class="edit-time-field col-md-3">
                            <label class="form-label small fw-semibold">Rest (min)</label>
                            <input type="number" name="rest_minutes" id="edit_rest_minutes" class="form-control form-control-sm" min="0">
                        </div>
                        @endif

                        <div class="col-12 d-flex gap-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_multiple" id="edit_is_multiple" value="1">
                                <label class="form-check-label small" for="edit_is_multiple">Allow Multiple Bookings</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" id="edit_is_active" value="1">
                                <label class="form-check-label small" for="edit_is_active">Active</label>
                            </div>
                        </div>

                        {{-- Images Section --}}
                        <div class="col-12">
                            <hr class="my-2">
                            <label class="form-label small fw-semibold d-block mb-2">
                                <i class="fas fa-images me-1"></i> Slot Images
                                <span class="text-muted fw-normal">(first image = cover)</span>
                            </label>

                            <div id="edit_images_preview" class="d-flex flex-wrap mb-2"></div>

                            <label class="btn btn-outline-secondary btn-sm" id="upload_img_label">
                                <i class="fas fa-upload me-1"></i> Upload Image
                                <input type="file" id="slot_image_input" accept="image/*" class="d-none" multiple>
                            </label>
                            <span id="upload_img_status" class="ms-2 small text-muted"></span>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentSlotId = null;

    function toggleCreateTimeFields(checkbox) {
        const show = !checkbox.checked;
        document.querySelectorAll('.create-time-field').forEach(el => {
            el.style.display = show ? '' : 'none';
        });
    }

    function toggleEditTimeFields(checkbox) {
        const show = !checkbox.checked;
        document.querySelectorAll('.edit-time-field').forEach(el => {
            el.style.display = show ? '' : 'none';
        });
    }

    function openEditModal(id, slot) {
        currentSlotId = id;
        const form = document.getElementById('editSlotForm');
        form.action = `/organizer/business/time-slots/${id}`;

        document.getElementById('edit_slot_name').value = slot.slot_name ?? '';

        const priceEl = document.getElementById('edit_slot_price');
        if (priceEl) priceEl.value = slot.slot_price ?? 0;

        const paxEl = document.getElementById('edit_pax');
        if (paxEl) paxEl.value = slot.pax ?? '';

        document.getElementById('edit_start_time').value       = slot.start_time ? slot.start_time.substring(0, 5) : '';
        document.getElementById('edit_end_time').value         = slot.end_time ? slot.end_time.substring(0, 5) : '';

        const durEl = document.getElementById('edit_duration_minutes');
        if (durEl) durEl.value = slot.duration_minutes ?? '';

        const restEl = document.getElementById('edit_rest_minutes');
        if (restEl) restEl.value = slot.rest_minutes ?? 0;

        document.getElementById('edit_is_full_day').checked  = !!slot.is_full_day;
        document.getElementById('edit_is_multiple').checked  = !!slot.is_multiple;
        document.getElementById('edit_is_active').checked    = !!slot.is_active;

        toggleEditTimeFields(document.getElementById('edit_is_full_day'));

        // Render existing images
        renderImages(slot.images ?? []);

        new bootstrap.Modal(document.getElementById('editSlotModal')).show();
    }

    function renderImages(images) {
        const preview = document.getElementById('edit_images_preview');
        preview.innerHTML = '';
        images.forEach(img => {
            const wrap = document.createElement('div');
            wrap.className = 'img-wrap';
            wrap.dataset.imageId = img.id;

            const src = `/storage/uploads/{{ $authUser->id }}/slots/${currentSlotId}/${img.url}`;
            wrap.innerHTML = `
                <img src="${src}" class="slot-thumb ${img.is_cover ? 'is-cover' : ''}" title="${img.is_cover ? 'Cover' : ''}">
                ${img.is_cover ? '<div class="cover-badge">Cover</div>' : ''}
                <button type="button" class="btn btn-danger btn-del-img" onclick="deleteImage(${img.id}, this)">
                    <i class="fas fa-times"></i>
                </button>`;
            preview.appendChild(wrap);
        });
    }

    function deleteImage(imageId, btn) {
        Swal.fire({
            title: 'Remove this image?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e55353',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, remove!',
            cancelButtonText: 'Cancel'
        }).then(function (result) {
            if (!result.isConfirmed) return;
            btn.disabled = true;

            fetch(`/organizer/business/time-slots/${currentSlotId}/images/${imageId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    const wrap = btn.closest('.img-wrap');
                    wrap.remove();
                }
            });
        });
    }

    // Upload slot images via AJAX
    document.getElementById('slot_image_input').addEventListener('change', function () {
        const files = Array.from(this.files);
        const status = document.getElementById('upload_img_status');

        files.forEach(file => {
            const formData = new FormData();
            formData.append('image', file);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

            status.textContent = 'Uploading...';

            fetch(`/organizer/business/time-slots/${currentSlotId}/images`, {
                method: 'POST',
                body: formData,
                headers: { 'Accept': 'application/json' },
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    status.textContent = '';
                    // Append new image to preview
                    const preview = document.getElementById('edit_images_preview');
                    const wrap = document.createElement('div');
                    wrap.className = 'img-wrap';
                    wrap.dataset.imageId = data.image_id;
                    wrap.innerHTML = `
                        <img src="${data.url}" class="slot-thumb ${data.is_cover ? 'is-cover' : ''}" title="${data.is_cover ? 'Cover' : ''}">
                        ${data.is_cover ? '<div class="cover-badge">Cover</div>' : ''}
                        <button type="button" class="btn btn-danger btn-del-img" onclick="deleteImage(${data.image_id}, this)">
                            <i class="fas fa-times"></i>
                        </button>`;
                    preview.appendChild(wrap);
                } else {
                    status.textContent = 'Upload failed.';
                }
            })
            .catch(() => { status.textContent = 'Upload error.'; });
        });

        this.value = ''; // reset input for re-upload
    });

    // Init create form — show time fields by default (full day unchecked)
    toggleCreateTimeFields(document.getElementById('create_is_full_day'));

    // Delete slot with SweetAlert confirm
    document.querySelectorAll('.btn-del-slot').forEach(btn => {
        btn.addEventListener('click', function () {
            const slotId   = this.dataset.slotId;
            const slotName = this.dataset.slotName;
            Swal.fire({
                title: 'Delete Time Slot?',
                text: `Delete "${slotName}"? This cannot be undone.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
            }).then(result => {
                if (result.isConfirmed) {
                    document.getElementById(`del-slot-${slotId}`).submit();
                }
            });
        });
    });
</script>
@endpush
