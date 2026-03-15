<div class="d-flex align-items-center gap-3">
    <div class="form-check form-switch mb-0" onclick="event.stopPropagation()">
        <input class="form-check-input" type="checkbox" role="switch"
            name="sections[{{ $sec }}][visible]" value="1"
            id="vis_{{ $sec }}"
            {{ ($sc['visible'] ?? true) !== false ? 'checked' : '' }}>
        <label class="form-check-label small" for="vis_{{ $sec }}">Visible</label>
    </div>
    <i class="fas fa-chevron-down text-muted small"></i>
</div>
