@extends('layouts.admin.default')
@push('styles')
<style>
    .related-table th { white-space: nowrap; font-size: .8rem; }
    .related-table td { vertical-align: middle; }
    .btn-remove-row { cursor: pointer; }
</style>
@endpush
@section('content')
<div class="container-fluid">

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('organizer.business.package.update', $package->id) }}" method="POST">
        @csrf
        @method('PATCH')

        {{-- ── BASIC INFO ──────────────────────────────────────────── --}}
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Edit Package — {{ $package->name }}</h5>
                <a href="{{ route('organizer.business.packages') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Package Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                            name="name" value="{{ old('name', $package->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3 col-md-6">
                        <label class="form-label">Slug <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('slug') is-invalid @enderror"
                            name="slug" value="{{ old('slug', $package->slug) }}" required>
                        @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class="form-label">Package Code</label>
                        <input type="text" class="form-control" name="package_code" value="{{ old('package_code', $package->package_code) }}">
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class="form-label">Category <span class="text-danger">*</span></label>
                        <select class="form-select @error('category_id') is-invalid @enderror" name="category_id" required>
                            <option value="">Choose category</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id', $package->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3 col-md-4">
                        <label class="form-label">Status <span class="text-danger">*</span></label>
                        <select class="form-select" name="status" required>
                            @foreach (['draft', 'active', 'inactive'] as $s)
                                <option value="{{ $s }}" {{ old('status', $package->status) == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3 col-md-12">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" id="descriptionEditor" rows="3">{{ old('description', $package->description) }}</textarea>
                    </div>
                    <div class="mb-3 col-md-12">
                        <label class="form-label">Terms &amp; Conditions</label>
                        <textarea class="form-control" name="tnc" id="tncEditor" rows="4">{{ old('tnc', $package->tnc) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── PRICING ─────────────────────────────────────────────── --}}
        @php $disc = $package->discounts->first(); @endphp
        <div class="card mb-3">
            <div class="card-header"><h6 class="card-title mb-0">Pricing &amp; Deposit</h6></div>
            <div class="card-body">
                <div class="row">
                    <div class="mb-3 col-md-3">
                        <label class="form-label">Base Price (RM) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control" name="base_price" value="{{ old('base_price', $package->base_price) }}" required>
                    </div>
                    <div class="mb-3 col-md-3">
                        <label class="form-label">Final Price (RM) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control" name="final_price" value="{{ old('final_price', $package->final_price) }}" required>
                    </div>
                    <div class="mb-3 col-md-2">
                        <label class="form-label">Discount %</label>
                        <input type="number" step="1" min="0" max="100" class="form-control" name="discount_percentage" value="{{ old('discount_percentage', $package->discount_percentage) }}">
                    </div>
                    <div class="mb-3 col-md-2">
                        <label class="form-label">Deposit %</label>
                        <input type="number" step="0.01" min="0" class="form-control" name="deposit_percentage" value="{{ old('deposit_percentage', $package->deposit_percentage) }}">
                    </div>
                    <div class="mb-3 col-md-2">
                        <label class="form-label">Deposit Fixed (RM)</label>
                        <input type="number" step="0.01" min="0" class="form-control" name="deposit_fixed" value="{{ old('deposit_fixed', $package->deposit_fixed) }}">
                    </div>
                    <div class="mb-3 col-md-2">
                        <label class="form-label">Service Charge %</label>
                        <input type="number" step="0.01" min="0" class="form-control" name="service_charge_percentage" value="{{ old('service_charge_percentage', $package->service_charge_percentage) }}">
                    </div>
                    <div class="mb-3 col-md-2">
                        <label class="form-label">Service Charge Fixed (RM)</label>
                        <input type="number" step="0.01" min="0" class="form-control" name="service_charge_fixed" value="{{ old('service_charge_fixed', $package->service_charge_fixed) }}">
                    </div>
                    <div class="mb-3 col-md-3">
                        <label class="form-label">Last Paid Date</label>
                        <input type="date" class="form-control" name="last_paid_date"
                            value="{{ old('last_paid_date', $package->last_paid_date ? \Carbon\Carbon::parse($package->last_paid_date)->format('Y-m-d') : '') }}">
                    </div>
                </div>
                <p class="fw-semibold mb-2 small text-muted">Discount Schedule (Optional)</p>
                <div class="row">
                    <div class="mb-3 col-md-3">
                        <label class="form-label">Type</label>
                        <select name="discount[type]" class="form-select">
                            <option value="percentage" {{ old('discount.type', $disc->type ?? '') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                            <option value="fixed" {{ old('discount.type', $disc->type ?? '') == 'fixed' ? 'selected' : '' }}>Fixed (RM)</option>
                        </select>
                    </div>
                    <div class="mb-3 col-md-3">
                        <label class="form-label">Amount</label>
                        <input type="number" step="0.01" name="discount[amount]" class="form-control" value="{{ old('discount.amount', $disc->amount ?? '') }}">
                    </div>
                    <div class="mb-3 col-md-3">
                        <label class="form-label">Starts At</label>
                        <input type="datetime-local" name="discount[starts_at]" class="form-control"
                            value="{{ old('discount.starts_at', $disc && $disc->starts_at ? \Carbon\Carbon::parse($disc->starts_at)->format('Y-m-d\TH:i') : '') }}">
                    </div>
                    <div class="mb-3 col-md-3">
                        <label class="form-label">Ends At</label>
                        <input type="datetime-local" name="discount[ends_at]" class="form-control"
                            value="{{ old('discount.ends_at', $disc && $disc->ends_at ? \Carbon\Carbon::parse($disc->ends_at)->format('Y-m-d\TH:i') : '') }}">
                    </div>
                </div>
            </div>
        </div>

        {{-- ── SCHEDULING ───────────────────────────────────────────── --}}
        <div class="card mb-3">
            <div class="card-header"><h6 class="card-title mb-0">Scheduling &amp; Slots</h6></div>
            <div class="card-body">
                <div class="row">
                    <div class="mb-3 col-md-3">
                        <label class="form-label">Duration (min)</label>
                        <input type="number" min="0" class="form-control" name="duration_minutes" value="{{ old('duration_minutes', $package->duration_minutes) }}">
                    </div>
                    <div class="mb-3 col-md-3">
                        <label class="form-label">Rest / Buffer (min)</label>
                        <input type="number" min="0" class="form-control" name="rest_minutes" value="{{ old('rest_minutes', $package->rest_minutes) }}">
                    </div>
                    <div class="mb-3 col-md-3">
                        <label class="form-label">Slot Quantity</label>
                        <input type="number" min="1" class="form-control" name="package_slot_quantity" value="{{ old('package_slot_quantity', $package->package_slot_quantity) }}">
                    </div>
                    <div class="mb-3 col-md-3">
                        <label class="form-label">Max Year Offset</label>
                        <input type="number" min="0" class="form-control" name="max_booking_year_offset" value="{{ old('max_booking_year_offset', $package->max_booking_year_offset) }}">
                    </div>
                    <div class="mb-3 col-md-3">
                        <label class="form-label">Valid From</label>
                        <input type="datetime-local" class="form-control" name="valid_from"
                            value="{{ old('valid_from', $package->valid_from ? \Carbon\Carbon::parse($package->valid_from)->format('Y-m-d\TH:i') : '') }}">
                    </div>
                    <div class="mb-3 col-md-3">
                        <label class="form-label">Valid Until</label>
                        <input type="datetime-local" class="form-control" name="valid_until"
                            value="{{ old('valid_until', $package->valid_until ? \Carbon\Carbon::parse($package->valid_until)->format('Y-m-d\TH:i') : '') }}">
                    </div>
                    <div class="mb-3 col-md-2">
                        <label class="form-label">Order By <small class="text-muted fw-normal">(lower first)</small></label>
                        <input type="number" min="0" class="form-control" name="order_by" value="{{ old('order_by', $package->order_by) }}">
                    </div>
                    <div class="mb-3 col-md-4 d-flex align-items-end">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" name="is_manual" value="1"
                                id="isManual" {{ old('is_manual', $package->is_manual) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isManual">Manual booking only</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── PACKAGE ITEMS ────────────────────────────────────────── --}}
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0">Package Items</h6>
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addItem()"><i class="fas fa-plus me-1"></i> Add Row</button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm related-table mb-0">
                        <thead class="table">
                            <tr>
                                <th style="width:22%">Title</th>
                                <th style="width:22%">Description</th>
                                <th style="width:8%">Qty</th>
                                <th style="width:12%">Unit Price</th>
                                <th style="width:8%">Order <small class="text-muted fw-normal">(lower first)</small></th>
                                <th style="width:8%" class="text-center">On Card</th>
                                <th style="width:8%" class="text-center">Optional</th>
                                <th style="width:5%"></th>
                            </tr>
                        </thead>
                        <tbody id="items-wrapper">
                            @forelse ($package->items as $i => $item)
                            <tr class="item-row">
                                <td><input type="text" name="items[{{ $i }}][title]" class="form-control form-control-sm" value="{{ $item->title }}"></td>
                                <td><input type="text" name="items[{{ $i }}][description]" class="form-control form-control-sm" value="{{ $item->description }}"></td>
                                <td><input type="number" name="items[{{ $i }}][quantity]" class="form-control form-control-sm" value="{{ $item->quantity }}"></td>
                                <td><input type="number" step="0.01" name="items[{{ $i }}][unit_price]" class="form-control form-control-sm" value="{{ $item->unit_price }}"></td>
                                <td><input type="number" name="items[{{ $i }}][sort_order]" class="form-control form-control-sm" value="{{ $item->sort_order }}"></td>
                                <td class="text-center"><input type="checkbox" name="items[{{ $i }}][show_on_card]" value="1" class="form-check-input" {{ $item->show_on_card ? 'checked' : '' }}></td>
                                <td class="text-center"><input type="checkbox" name="items[{{ $i }}][is_optional]" value="1" class="form-check-input" {{ $item->is_optional ? 'checked' : '' }}></td>
                                <td><i class="fas fa-times text-danger btn-remove-row" onclick="removeRow(this)"></i></td>
                            </tr>
                            @empty
                            <tr class="item-row">
                                <td><input type="text" name="items[0][title]" class="form-control form-control-sm" placeholder="Title"></td>
                                <td><input type="text" name="items[0][description]" class="form-control form-control-sm" placeholder="Description"></td>
                                <td><input type="number" name="items[0][quantity]" class="form-control form-control-sm" value="1"></td>
                                <td><input type="number" step="0.01" name="items[0][unit_price]" class="form-control form-control-sm" placeholder="0.00"></td>
                                <td><input type="number" name="items[0][sort_order]" class="form-control form-control-sm" value="0"></td>
                                <td class="text-center"><input type="checkbox" name="items[0][show_on_card]" value="1" class="form-check-input"></td>
                                <td class="text-center"><input type="checkbox" name="items[0][is_optional]" value="1" class="form-check-input"></td>
                                <td></td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ── PACKAGE ADDONS ───────────────────────────────────────── --}}
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0">Package Addons</h6>
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addAddon()"><i class="fas fa-plus me-1"></i> Add Row</button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm related-table mb-0">
                        <thead class="table">
                            <tr>
                                <th style="width:13%">Name</th>
                                <th style="width:9%">Hint</th>
                                <th style="width:11%">Description</th>
                                <th style="width:7%">Price (RM)</th>
                                <th style="width:6%">Time (min)</th>
                                <th style="width:5%" class="text-center">Is Time</th>
                                <th style="width:5%" class="text-center">Is Qty</th>
                                <th style="width:5%" class="text-center">Required</th>
                                <th style="width:9%">Special From</th>
                                <th style="width:9%">Special To</th>
                                <th style="width:6%" class="text-center">Active</th>
                                <th style="width:4%"></th>
                            </tr>
                        </thead>
                        <tbody id="addons-wrapper">
                            @forelse ($package->addons as $i => $addon)
                            <tr class="addon-row {{ $addon->is_active ? '' : 'table-secondary' }}">
                                <input type="hidden" name="addons[{{ $i }}][id]" value="{{ $addon->id }}">
                                <td><input type="text" name="addons[{{ $i }}][name]" class="form-control form-control-sm" value="{{ $addon->name }}"></td>
                                <td><input type="text" name="addons[{{ $i }}][hint]" class="form-control form-control-sm" value="{{ $addon->hint }}"></td>
                                <td><input type="text" name="addons[{{ $i }}][description]" class="form-control form-control-sm" value="{{ $addon->description }}"></td>
                                <td><input type="number" step="0.01" name="addons[{{ $i }}][price]" class="form-control form-control-sm" value="{{ $addon->price }}"></td>
                                <td><input type="number" name="addons[{{ $i }}][time_minutes]" class="form-control form-control-sm" value="{{ $addon->time_minutes }}"></td>
                                <td class="text-center"><input type="checkbox" name="addons[{{ $i }}][is_time]" value="1" class="form-check-input" {{ $addon->is_time ? 'checked' : '' }}></td>
                                <td class="text-center"><input type="checkbox" name="addons[{{ $i }}][is_qty]" value="1" class="form-check-input" {{ $addon->is_qty ? 'checked' : '' }}></td>
                                <td class="text-center"><input type="checkbox" name="addons[{{ $i }}][is_required]" value="1" class="form-check-input" {{ $addon->is_required ? 'checked' : '' }}></td>
                                <td><input type="date" name="addons[{{ $i }}][special_date_start]" class="form-control form-control-sm" value="{{ $addon->special_date_start ? $addon->special_date_start->format('Y-m-d') : '' }}"></td>
                                <td><input type="date" name="addons[{{ $i }}][special_date_end]" class="form-control form-control-sm" value="{{ $addon->special_date_end ? $addon->special_date_end->format('Y-m-d') : '' }}"></td>
                                <td class="text-center"><input type="checkbox" name="addons[{{ $i }}][is_active]" value="1" class="form-check-input" {{ $addon->is_active ? 'checked' : '' }} title="Toggle to show/hide this addon for new bookings"></td>
                                <td><i class="fas fa-times text-danger btn-remove-row" onclick="removeRow(this)"></i></td>
                            </tr>
                            @empty
                            <tr class="addon-row">
                                <td><input type="text" name="addons[0][name]" class="form-control form-control-sm" placeholder="Addon Name"></td>
                                <td><input type="text" name="addons[0][hint]" class="form-control form-control-sm" placeholder="Hint"></td>
                                <td><input type="text" name="addons[0][description]" class="form-control form-control-sm" placeholder="Description"></td>
                                <td><input type="number" step="0.01" name="addons[0][price]" class="form-control form-control-sm" placeholder="0.00"></td>
                                <td><input type="number" name="addons[0][time_minutes]" class="form-control form-control-sm"></td>
                                <td class="text-center"><input type="checkbox" name="addons[0][is_time]" value="1" class="form-check-input"></td>
                                <td class="text-center"><input type="checkbox" name="addons[0][is_qty]" value="1" class="form-check-input"></td>
                                <td class="text-center"><input type="checkbox" name="addons[0][is_required]" value="1" class="form-check-input"></td>
                                <td><input type="date" name="addons[0][special_date_start]" class="form-control form-control-sm"></td>
                                <td><input type="date" name="addons[0][special_date_end]" class="form-control form-control-sm"></td>
                                <td class="text-center"><input type="checkbox" name="addons[0][is_active]" value="1" class="form-check-input" checked></td>
                                <td></td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ── PACKAGE IMAGES ───────────────────────────────────────── --}}
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0">Package Images</h6>
                <div class="d-flex gap-2 align-items-center">
                    {{-- Quick upload --}}
                    <label class="btn btn-sm btn-outline-success mb-0">
                        <i class="fas fa-upload me-1"></i> Upload
                        <input type="file" id="quickImageUpload" accept="image/*" style="display:none" onchange="uploadPackageImage(this)">
                    </label>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addImage()"><i class="fas fa-plus me-1"></i> Add Row</button>
                </div>
            </div>
            <div id="upload-progress" class="d-none px-3 pt-2">
                <div class="progress" style="height:4px"><div class="progress-bar progress-bar-striped progress-bar-animated w-100"></div></div>
            </div>
            <div id="image-previews" class="d-flex flex-wrap gap-2 px-3 pt-2">
                @foreach($package->images as $img)
                <div class="position-relative" style="width:80px">
                    <img src="{{ asset('storage/uploads/'.$package->organizer_id.'/packages/'.$package->id.'/'.$img->url) }}"
                         style="width:80px;height:60px;object-fit:cover;border-radius:4px;border:1px solid #dee2e6">
                    @if($img->is_cover)<span class="badge bg-primary position-absolute top-0 start-0" style="font-size:9px">Cover</span>@endif
                </div>
                @endforeach
            </div>
            <div class="card-body p-0 pt-2">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm related-table mb-0">
                        <thead class="table">
                            <tr>
                                <th style="width:40%">Filename / URL</th>
                                <th style="width:30%">Alt Text</th>
                                <th style="width:10%" class="text-center">Cover</th>
                                <th style="width:10%">Sort <small class="text-muted fw-normal">(lower first)</small></th>
                                <th style="width:5%"></th>
                            </tr>
                        </thead>
                        <tbody id="images-wrapper">
                            @forelse ($package->images as $i => $image)
                            <tr class="image-row">
                                <td><input type="text" name="images[{{ $i }}][url]" class="form-control form-control-sm" value="{{ $image->url }}"></td>
                                <td><input type="text" name="images[{{ $i }}][alt_text]" class="form-control form-control-sm" value="{{ $image->alt_text }}"></td>
                                <td class="text-center"><input type="checkbox" name="images[{{ $i }}][is_cover]" value="1" class="form-check-input" {{ $image->is_cover ? 'checked' : '' }}></td>
                                <td><input type="number" name="images[{{ $i }}][sort_order]" class="form-control form-control-sm" value="{{ $image->sort_order }}"></td>
                                <td><i class="fas fa-times text-danger btn-remove-row" onclick="removeRow(this)"></i></td>
                            </tr>
                            @empty
                            <tr class="image-row">
                                <td><input type="text" name="images[0][url]" class="form-control form-control-sm" placeholder="image.jpg or URL"></td>
                                <td><input type="text" name="images[0][alt_text]" class="form-control form-control-sm" placeholder="Alt text"></td>
                                <td class="text-center"><input type="checkbox" name="images[0][is_cover]" value="1" class="form-check-input"></td>
                                <td><input type="number" name="images[0][sort_order]" class="form-control form-control-sm" value="0"></td>
                                <td></td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ── BOOKING FORM FIELDS ──────────────────────────────────── --}}
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0">Booking Form Fields</h6>
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="addField()"><i class="fas fa-plus me-1"></i> Add Row</button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm related-table mb-0">
                        <thead class="table">
                            <tr>
                                <th style="width:22%">Label</th>
                                <th style="width:18%">Key</th>
                                <th style="width:12%">Type</th>
                                <th style="width:28%">Options (JSON for select)</th>
                                <th style="width:10%" class="text-center">Required</th>
                                <th style="width:5%"></th>
                            </tr>
                        </thead>
                        <tbody id="fields-wrapper">
                            @forelse ($package->formFields as $i => $field)
                            <tr class="field-row">
                                <td><input type="text" name="form_fields[{{ $i }}][field_label]" class="form-control form-control-sm" value="{{ $field->field_label }}"></td>
                                <td><input type="text" name="form_fields[{{ $i }}][field_key]" class="form-control form-control-sm" value="{{ $field->field_key }}"></td>
                                <td>
                                    <select name="form_fields[{{ $i }}][field_type]" class="form-select form-select-sm">
                                        @foreach (['text','textarea','select','checkbox','number','date'] as $t)
                                            <option value="{{ $t }}" {{ $field->field_type == $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="text" name="form_fields[{{ $i }}][options]" class="form-control form-control-sm"
                                    value="{{ $field->options ? json_encode($field->options) : '' }}" placeholder='["Option A","Option B"]'></td>
                                <td class="text-center"><input type="checkbox" name="form_fields[{{ $i }}][is_required]" value="1" class="form-check-input" {{ $field->is_required ? 'checked' : '' }}></td>
                                <td><i class="fas fa-times text-danger btn-remove-row" onclick="removeRow(this)"></i></td>
                            </tr>
                            @empty
                            <tr class="field-row">
                                <td><input type="text" name="form_fields[0][field_label]" class="form-control form-control-sm" placeholder="e.g. Full Name"></td>
                                <td><input type="text" name="form_fields[0][field_key]" class="form-control form-control-sm" placeholder="full_name"></td>
                                <td>
                                    <select name="form_fields[0][field_type]" class="form-select form-select-sm">
                                        <option value="text">Text</option>
                                        <option value="textarea">Textarea</option>
                                        <option value="select">Select</option>
                                        <option value="checkbox">Checkbox</option>
                                        <option value="number">Number</option>
                                        <option value="date">Date</option>
                                    </select>
                                </td>
                                <td><input type="text" name="form_fields[0][options]" class="form-control form-control-sm" placeholder='["Option A","Option B"]'></td>
                                <td class="text-center"><input type="checkbox" name="form_fields[0][is_required]" value="1" class="form-check-input"></td>
                                <td></td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ── SUBMIT ────────────────────────────────────────────────── --}}
        <div class="d-flex gap-2 mb-4">
            <button type="submit" class="btn btn-primary px-4"><i class="fas fa-save me-1"></i> Save Changes</button>
            <a href="{{ route('organizer.business.packages') }}" class="btn btn-outline-secondary">Cancel</a>
            <button type="button" class="btn btn-outline-danger ms-auto btn-delete-pkg">
                <i class="fas fa-trash me-1"></i> Delete Package
            </button>
        </div>

    </form>

    {{-- Delete form MUST be outside the main form to avoid nested-form bug --}}
    <form action="{{ route('organizer.business.package.destroy', $package->id) }}" method="POST"
        id="deletePackageForm">
        @csrf
        @method('DELETE')
    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.tiny.cloud/1/{{ \App\Models\AppSetting::get('tinymce_api_key', 'no-api-key') }}/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#descriptionEditor, #tncEditor', height: 250, menubar: false,
        plugins: 'advlist autolink lists link charmap preview anchor code fullscreen',
        toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright | bullist numlist | link code',
        content_style: "body { font-family:Helvetica,Arial,sans-serif; font-size:14px }"
    });

    let itemIdx  = {{ $package->items->count() ?: 1 }};
    let addonIdx = {{ $package->addons->count() ?: 1 }};
    let imageIdx = {{ $package->images->count() ?: 1 }};
    let fieldIdx = {{ $package->formFields->count() ?: 1 }};

    function removeRow(btn) { btn.closest('tr').remove(); }

    // ── Image Upload ──────────────────────────────────────────────────────────
    function uploadPackageImage(input) {
        if (!input.files.length) return;
        const file = input.files[0];
        const formData = new FormData();
        formData.append('image', file);
        formData.append('_token', '{{ csrf_token() }}');

        document.getElementById('upload-progress').classList.remove('d-none');

        fetch('{{ route("organizer.business.package.upload-image", $package->id) }}', {
            method: 'POST',
            body: formData,
        })
        .then(r => r.json())
        .then(data => {
            document.getElementById('upload-progress').classList.add('d-none');
            if (data.success) {
                // Add to table
                const i = imageIdx++;
                document.getElementById('images-wrapper').insertAdjacentHTML('beforeend', `
                    <tr class="image-row">
                        <td><input type="text" name="images[${i}][url]" class="form-control form-control-sm" value="${data.filename}"></td>
                        <td><input type="text" name="images[${i}][alt_text]" class="form-control form-control-sm"></td>
                        <td class="text-center"><input type="checkbox" name="images[${i}][is_cover]" value="1" class="form-check-input"></td>
                        <td><input type="number" name="images[${i}][sort_order]" class="form-control form-control-sm" value="${i}"></td>
                        <td><i class="fas fa-times text-danger btn-remove-row" onclick="removeRow(this)"></i></td>
                    </tr>
                `);
                // Add preview
                document.getElementById('image-previews').insertAdjacentHTML('beforeend', `
                    <div style="width:80px"><img src="${data.url}" style="width:80px;height:60px;object-fit:cover;border-radius:4px;border:1px solid #dee2e6"></div>
                `);
            } else {
                Swal.fire({ icon: 'error', title: 'Upload Failed', text: 'Please try again.' });
            }
        })
        .catch(() => {
            document.getElementById('upload-progress').classList.add('d-none');
            Swal.fire({ icon: 'error', title: 'Upload Error', text: 'An unexpected error occurred.' });
        });

        input.value = ''; // reset input
    }

    function addItem() {
        const i = itemIdx++;
        document.getElementById('items-wrapper').insertAdjacentHTML('beforeend', `
        <tr class="item-row">
            <td><input type="text" name="items[${i}][title]" class="form-control form-control-sm" placeholder="Title"></td>
            <td><input type="text" name="items[${i}][description]" class="form-control form-control-sm" placeholder="Description"></td>
            <td><input type="number" name="items[${i}][quantity]" class="form-control form-control-sm" value="1"></td>
            <td><input type="number" step="0.01" name="items[${i}][unit_price]" class="form-control form-control-sm" placeholder="0.00"></td>
            <td><input type="number" name="items[${i}][sort_order]" class="form-control form-control-sm" value="0"></td>
            <td class="text-center"><input type="checkbox" name="items[${i}][show_on_card]" value="1" class="form-check-input"></td>
            <td class="text-center"><input type="checkbox" name="items[${i}][is_optional]" value="1" class="form-check-input"></td>
            <td><i class="fas fa-times text-danger btn-remove-row" onclick="removeRow(this)"></i></td>
        </tr>`);
    }

    function addAddon() {
        const i = addonIdx++;
        document.getElementById('addons-wrapper').insertAdjacentHTML('beforeend', `
        <tr class="addon-row">
            <td><input type="text" name="addons[${i}][name]" class="form-control form-control-sm" placeholder="Addon Name"></td>
            <td><input type="text" name="addons[${i}][hint]" class="form-control form-control-sm" placeholder="Hint"></td>
            <td><input type="text" name="addons[${i}][description]" class="form-control form-control-sm" placeholder="Description"></td>
            <td><input type="number" step="0.01" name="addons[${i}][price]" class="form-control form-control-sm" placeholder="0.00"></td>
            <td><input type="number" name="addons[${i}][time_minutes]" class="form-control form-control-sm"></td>
            <td class="text-center"><input type="checkbox" name="addons[${i}][is_time]" value="1" class="form-check-input"></td>
            <td class="text-center"><input type="checkbox" name="addons[${i}][is_qty]" value="1" class="form-check-input"></td>
            <td class="text-center"><input type="checkbox" name="addons[${i}][is_required]" value="1" class="form-check-input"></td>
            <td><input type="date" name="addons[${i}][special_date_start]" class="form-control form-control-sm"></td>
            <td><input type="date" name="addons[${i}][special_date_end]" class="form-control form-control-sm"></td>
            <td class="text-center"><input type="checkbox" name="addons[${i}][is_active]" value="1" class="form-check-input" checked></td>
            <td><i class="fas fa-times text-danger btn-remove-row" onclick="removeRow(this)"></i></td>
        </tr>`);
    }

    function addImage() {
        const i = imageIdx++;
        document.getElementById('images-wrapper').insertAdjacentHTML('beforeend', `
        <tr class="image-row">
            <td><input type="text" name="images[${i}][url]" class="form-control form-control-sm" placeholder="image.jpg or URL"></td>
            <td><input type="text" name="images[${i}][alt_text]" class="form-control form-control-sm" placeholder="Alt text"></td>
            <td class="text-center"><input type="checkbox" name="images[${i}][is_cover]" value="1" class="form-check-input"></td>
            <td><input type="number" name="images[${i}][sort_order]" class="form-control form-control-sm" value="0"></td>
            <td><i class="fas fa-times text-danger btn-remove-row" onclick="removeRow(this)"></i></td>
        </tr>`);
    }

    function addField() {
        const i = fieldIdx++;
        document.getElementById('fields-wrapper').insertAdjacentHTML('beforeend', `
        <tr class="field-row">
            <td><input type="text" name="form_fields[${i}][field_label]" class="form-control form-control-sm" placeholder="e.g. Full Name"></td>
            <td><input type="text" name="form_fields[${i}][field_key]" class="form-control form-control-sm" placeholder="full_name"></td>
            <td>
                <select name="form_fields[${i}][field_type]" class="form-select form-select-sm">
                    <option value="text">Text</option>
                    <option value="textarea">Textarea</option>
                    <option value="select">Select</option>
                    <option value="checkbox">Checkbox</option>
                    <option value="number">Number</option>
                    <option value="date">Date</option>
                </select>
            </td>
            <td><input type="text" name="form_fields[${i}][options]" class="form-control form-control-sm" placeholder='["Option A","Option B"]'></td>
            <td class="text-center"><input type="checkbox" name="form_fields[${i}][is_required]" value="1" class="form-check-input"></td>
            <td><i class="fas fa-times text-danger btn-remove-row" onclick="removeRow(this)"></i></td>
        </tr>`);
    }

    document.querySelector('.btn-delete-pkg').addEventListener('click', function () {
        Swal.fire({
            title: 'Delete this package?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) document.getElementById('deletePackageForm').submit();
        });
    });
</script>
@endpush
