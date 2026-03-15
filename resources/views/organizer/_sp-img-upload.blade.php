{{--
    Inline image upload for a special page slot.
    Required vars: $slot, $label, $fallback, $spImgs, $uploadContext
--}}
@php
    $currentPath = $spImgs[$slot] ?? null;
    $currentUrl  = $currentPath ? asset('storage/'.$currentPath) : asset('storage/'.$fallback);
    $thumbElemId = 'ithumb_'.$slot.'_'.$uploadContext;
    $inputElemId = 'iinput_'.$slot.'_'.$uploadContext;
    $statusElemId = 'istatus_'.$slot.'_'.$uploadContext;
@endphp
<div class="sp-inline-upload">
    <label class="form-label small fw-semibold">{{ $label }}</label>
    <div class="d-flex gap-2 align-items-center">
        <img src="{{ $currentUrl }}"
            id="{{ $thumbElemId }}"
            class="sp-inline-thumb"
            data-sp-img-slot="{{ $slot }}"
            alt="{{ $label }}">
        <div style="flex:1">
            <div class="d-flex gap-1">
                <input type="file" class="form-control form-control-sm sp-img-input"
                    accept="image/*"
                    id="{{ $inputElemId }}"
                    data-slot="{{ $slot }}"
                    data-thumb-id="{{ $thumbElemId }}">
                <button type="button"
                    class="btn btn-sm btn-info text-white sp-upload-btn flex-shrink-0"
                    data-slot="{{ $slot }}"
                    data-input-id="{{ $inputElemId }}"
                    data-status-id="{{ $statusElemId }}">
                    <i class="fas fa-upload"></i>
                </button>
            </div>
            <div class="upload-status small mt-1" id="{{ $statusElemId }}"></div>
        </div>
    </div>
</div>
