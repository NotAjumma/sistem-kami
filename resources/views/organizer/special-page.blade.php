@extends('layouts.admin.default')

@push('styles')
{{-- Preload all selectable fonts so they're ready the moment user picks one --}}
<link href="https://fonts.googleapis.com/css2?family=Imperial+Script&family=Great+Vibes&family=Playfair+Display:ital,wght@1,400&family=Cormorant+Garamond:ital,wght@1,400&family=Dancing+Script&family=Cinzel&family=Josefin+Sans:wght@300;400;600&family=Poppins:wght@300;400;500&family=Lato:wght@300;400;700&family=Montserrat:wght@300;400;500;600&family=Raleway:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
    :root { --sp-accent: {{ $cfg['accent_color'] ?? '#14b9d5' }}; }
    .card { height: auto; }
    .section-card { border-left: 3px solid var(--sp-accent); }

    /* ── Preview panel ── */
    .sp-prev { position: relative; overflow: hidden; border-bottom: 1px dashed #e0e0e0; font-family: 'Poppins', sans-serif; }
    .sp-prev-overlay {
        display: none; position: absolute; inset: 0;
        background: rgba(0,0,0,0.55); align-items: center; justify-content: center;
        font-family: 'Josefin Sans', sans-serif; font-size: 13px; font-weight: 600;
        letter-spacing: 4px; color: #fff; text-transform: uppercase; z-index: 10;
    }
    .sp-prev.is-hidden .sp-prev-overlay { display: flex; }

    /* Hero */
    .prev-hero { height: 180px; background-size: cover; background-position: center; display: flex; align-items: center; justify-content: center; position: relative; }
    .prev-hero::before { content: ''; position: absolute; inset: 0; background: rgba(10,18,30,0.45); }
    .prev-hero-inner { position: relative; text-align: center; padding: 0 16px; }
    .prev-hero-title { font-family: 'Imperial Script', cursive; font-size: 48px; color: #fff; line-height: 1; }
    .prev-hero-slogan { font-family: 'Josefin Sans', sans-serif; font-size: 10px; letter-spacing: 3px; text-transform: uppercase; color: rgba(255,255,255,0.8); margin-top: 8px; }

    /* Two-column */
    .prev-row { display: flex; gap: 20px; padding: 20px; align-items: center; }
    .prev-row-rev { flex-direction: row-reverse; }
    .prev-text { flex: 0 0 44%; }
    .prev-imgs { flex: 1; }
    .prev-eyebrow { font-family: 'Josefin Sans', sans-serif; font-size: 10px; letter-spacing: 2px; text-transform: uppercase; color: #bbb; margin-bottom: 5px; }
    .prev-heading { font-family: 'Imperial Script', cursive; font-size: 30px; color: #222; line-height: 1.2; margin-bottom: 8px; }
    .prev-body { font-size: 11px; color: #888; line-height: 1.7; margin-bottom: 6px; }
    .prev-link { font-family: 'Josefin Sans', sans-serif; font-size: 10px; font-weight: 600; letter-spacing: 1.5px; text-transform: uppercase; color: #222; border-bottom: 1px solid #222; display: inline-block; }
    .prev-btn { font-family: 'Josefin Sans', sans-serif; font-size: 10px; font-weight: 600; letter-spacing: 1.5px; text-transform: uppercase; color: #fff; background: var(--sp-accent); padding: 7px 14px; display: inline-block; }

    /* Image grids */
    .prev-img-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 3px; }
    .prev-img-grid img:first-child { grid-column: 1/-1; height: 90px; }
    .prev-img-grid img { height: 65px; width: 100%; object-fit: cover; display: block; }
    .prev-img-mosaic { display: grid; grid-template-columns: 1fr 1fr; gap: 3px; }
    .prev-img-mosaic img:nth-child(1) { grid-column: 1/-1; height: 90px; }
    .prev-img-mosaic img:nth-child(2), .prev-img-mosaic img:nth-child(3) { height: 65px; }
    .prev-img-mosaic img { width: 100%; object-fit: cover; display: block; }
    .prev-img-single { width: 100%; height: 160px; object-fit: cover; display: block; }

    /* Location */
    .prev-location { background: #f9f7f4; }
    .prev-location-item { display: flex; gap: 6px; font-size: 11px; color: #888; margin-bottom: 5px; align-items: flex-start; }
    .prev-hours { background: #fff; border-left: 2px solid var(--sp-accent); padding: 8px 10px; margin-top: 8px; }
    .prev-hours h6 { font-family: 'Josefin Sans', sans-serif; font-size: 10px; letter-spacing: 1.5px; text-transform: uppercase; color: #222; margin-bottom: 4px; }
    .prev-hours p { font-size: 11px; color: #999; margin: 0; }

    /* Venue (bg overlay) */
    .prev-venue { background-size: cover; background-position: center; }
    .prev-venue-inner { background: rgba(243,237,229,0.90); }
    .prev-venue-name { font-family: 'Imperial Script', cursive; font-size: 28px; color: #2a1f12; margin-bottom: 6px; }
    .prev-venue-desc { font-size: 11px; color: #5a4f42; line-height: 1.7; }

    /* Wedding intro */
    .prev-intro { padding: 20px; background: #fff; }
    .prev-intro p { font-size: 11px; color: #555; line-height: 1.7; margin-bottom: 6px; }

    /* Inline image upload */
    .sp-inline-upload { margin-top: 12px; padding-top: 12px; border-top: 1px solid #f0f0f0; }
    .sp-inline-thumb { width: 72px; height: 52px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd; flex-shrink: 0; }

    .section-header { display: flex; align-items: center; justify-content: space-between; cursor: pointer; user-select: none; }
</style>
@endpush

@section('content')
@php
    $spImgs   = $organizer->special_page_images ?? [];
    $imgUrl   = fn($slot, $fb) => asset('storage/' . ($spImgs[$slot] ?? $fb));
    $secCfgOf = fn($sec) => $cfg['sections'][$sec] ?? [];
    $valOf    = fn($sec, $k, $fb) => $cfg['sections'][$sec][$k] ?? $fb;
    $isVis    = fn($sec) => ($cfg['sections'][$sec]['visible'] ?? true) !== false;
@endphp

<div class="container-fluid">
<div class="row justify-content-center">
<div class="col-lg-10">

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0"><i class="fas fa-paint-brush me-2 text-info"></i>Profile Page Customizer</h4>
        <a href="{{ url('/' . str_replace('_', '-', $organizer->special_page)) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
            <i class="fas fa-external-link-alt me-1"></i> Preview
        </a>
    </div>

    <form action="{{ route('organizer.business.special-page.update') }}" method="POST" id="spForm">
    @csrf

    {{-- ── Global ──────────────────────────────────────────────────── --}}
    @php
    $headingFonts = ['Imperial Script','Great Vibes','Playfair Display','Cormorant Garamond','Dancing Script','Cinzel'];
    $bodyFonts    = ['Poppins','Lato','Montserrat','Raleway'];
    $curHeading   = $cfg['heading_font'] ?? 'Imperial Script';
    $curBody      = $cfg['body_font']    ?? 'Poppins';
    @endphp
    <div class="card mb-4">
        <div class="card-header"><h6 class="mb-0"><i class="fas fa-palette me-2"></i>Global Settings</h6></div>
        <div class="card-body">
            <div class="row g-4">
                <div class="col-md-3">
                    <label class="form-label fw-semibold small">Page Visibility</label>
                    @php $curVis = $cfg['visibility'] ?? 'public'; @endphp
                    <div class="d-flex gap-3 mt-1">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="visibility" value="public" id="vis_public"
                                {{ $curVis === 'public' ? 'checked' : '' }}>
                            <label class="form-check-label small" for="vis_public">
                                <i class="fas fa-globe me-1 text-success"></i>Public
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="visibility" value="private" id="vis_private"
                                {{ $curVis === 'private' ? 'checked' : '' }}>
                            <label class="form-check-label small" for="vis_private">
                                <i class="fas fa-lock me-1 text-warning"></i>Private
                            </label>
                        </div>
                    </div>
                    <div class="form-text">Private = only you can view when logged in.</div>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold small">Accent Color</label>
                    <div class="d-flex align-items-center gap-2">
                        <input type="color" name="accent_color" id="accentColor"
                            value="{{ $cfg['accent_color'] ?? '#14b9d5' }}"
                            class="form-control form-control-color" style="width:50px;height:38px;">
                        <code id="accentHex" class="small">{{ $cfg['accent_color'] ?? '#14b9d5' }}</code>
                    </div>
                    <div class="form-text">Buttons, links, highlights.</div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Heading / Script Font</label>
                    <select name="heading_font" id="headingFont" class="form-select form-select-sm">
                        @foreach($headingFonts as $f)
                        <option value="{{ $f }}" {{ $curHeading === $f ? 'selected' : '' }}>{{ $f }}</option>
                        @endforeach
                    </select>
                    <div class="form-text prev-heading-sample" style="font-size:20px;margin-top:6px;">{{ $organizer->name }}</div>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-semibold small">Body Font</label>
                    <select name="body_font" id="bodyFont" class="form-select form-select-sm">
                        @foreach($bodyFonts as $f)
                        <option value="{{ $f }}" {{ $curBody === $f ? 'selected' : '' }}>{{ $f }}</option>
                        @endforeach
                    </select>
                    <div class="form-text prev-body-sample" style="margin-top:6px;">The quick brown fox jumps over the lazy dog</div>
                </div>
            </div>
        </div>
    </div>

    <h6 class="text-muted text-uppercase small fw-bold mb-2 mt-3">Home Page</h6>

    {{-- ── HERO ──────────────────────────────────────────────────── --}}
    @php $sec = 'hero'; $sc = $secCfgOf($sec); @endphp
    <div class="card mb-3 section-card">
        <div class="card-header section-header" data-bs-toggle="collapse" data-bs-target="#edit_{{ $sec }}">
            <span><i class="fas fa-image me-2 text-secondary"></i>Hero Section</span>
            @include('organizer._sp-vis-toggle', ['sec' => $sec, 'sc' => $sc])
        </div>
        <div class="sp-prev {{ !$isVis($sec) ? 'is-hidden' : '' }}" id="prev_panel_{{ $sec }}">
            <div class="sp-prev-overlay">Section Hidden</div>
            <div class="prev-hero" data-sp-bg-slot="hero" style="background-image:url('{{ $imgUrl('hero','lady_d_touch/hero-main.jpg') }}')">
                <div class="prev-hero-inner">
                    <div class="prev-hero-title">{{ $organizer->name }}</div>
                    <div class="prev-hero-slogan" id="spv-{{ $sec }}-slogan" data-sp-fallback="Crafting Dreams, Celebrating Love">{{ $valOf($sec,'slogan','Crafting Dreams, Celebrating Love') }}</div>
                </div>
            </div>
        </div>
        <div class="collapse {{ $isVis($sec) ? 'show' : '' }}" id="edit_{{ $sec }}">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label small fw-semibold">Tagline / Slogan</label>
                        <input type="text" class="form-control form-control-sm"
                            name="sections[{{ $sec }}][slogan]" value="{{ $sc['slogan'] ?? '' }}"
                            placeholder="Crafting Dreams, Celebrating Love"
                            data-sp-sec="{{ $sec }}" data-sp-field="slogan">
                    </div>
                </div>
                @include('organizer._sp-img-upload', ['slot'=>'hero','label'=>'Hero Background Image','fallback'=>'lady_d_touch/hero-main.jpg','spImgs'=>$spImgs,'uploadContext'=>$sec])
            </div>
        </div>
    </div>

    {{-- ── ABOUT ─────────────────────────────────────────────────── --}}
    @php $sec = 'about'; $sc = $secCfgOf($sec); @endphp
    <div class="card mb-3 section-card">
        <div class="card-header section-header" data-bs-toggle="collapse" data-bs-target="#edit_{{ $sec }}">
            <span><i class="fas fa-book-open me-2 text-secondary"></i>About / Story Section</span>
            @include('organizer._sp-vis-toggle', ['sec' => $sec, 'sc' => $sc])
        </div>
        <div class="sp-prev {{ !$isVis($sec) ? 'is-hidden' : '' }}" id="prev_panel_{{ $sec }}">
            <div class="sp-prev-overlay">Section Hidden</div>
            <div class="prev-row">
                <div class="prev-text">
                    <p class="prev-body" id="spv-{{ $sec }}-p1" data-sp-fallback="{{ $organizer->name }} is a beautiful wedding venue…">{{ $valOf($sec,'p1',$organizer->name.' is a beautiful wedding venue…') }}</p>
                    <p class="prev-body" id="spv-{{ $sec }}-p2" data-sp-fallback="The idea originated from a dream…">{{ $valOf($sec,'p2','The idea originated from a dream…') }}</p>
                    <p class="prev-body" id="spv-{{ $sec }}-p3" data-sp-fallback="Today, {{ $organizer->name }} is available…">{{ $valOf($sec,'p3','Today, '.$organizer->name.' is available…') }}</p>
                    <span class="prev-link" id="spv-{{ $sec }}-link_text" data-sp-fallback="→ Read Full Story">{{ $valOf($sec,'link_text','→ Read Full Story') }}</span>
                </div>
                <div class="prev-imgs">
                    <div class="prev-img-grid">
                        <img data-sp-img-slot="venue_dewan"    src="{{ $imgUrl('venue_dewan','Pelamin_DSDusun_2024-1-1024x618.jpeg') }}" alt="">
                        <img data-sp-img-slot="venue_dataran"  src="{{ $imgUrl('venue_dataran','DataranSriDusun-1024x682.jpg') }}" alt="">
                        <img data-sp-img-slot="venue_laman"    src="{{ $imgUrl('venue_laman','LamanDusun-1024x768.jpg') }}" alt="">
                    </div>
                </div>
            </div>
        </div>
        <div class="collapse {{ $isVis($sec) ? 'show' : '' }}" id="edit_{{ $sec }}">
            <div class="card-body">
                <div class="row g-3">
                    @foreach([['p1','Paragraph 1'],['p2','Paragraph 2'],['p3','Paragraph 3'],['link_text','Link Text']] as [$k,$lbl])
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">{{ $lbl }}</label>
                        <input type="text" class="form-control form-control-sm"
                            name="sections[{{ $sec }}][{{ $k }}]" value="{{ $sc[$k] ?? '' }}"
                            placeholder="{{ $lbl }}"
                            data-sp-sec="{{ $sec }}" data-sp-field="{{ $k }}">
                    </div>
                    @endforeach
                </div>
                {{-- Note: venue images are uploaded from their respective Venue sections below --}}
                <p class="text-muted small mt-3 mb-0"><i class="fas fa-info-circle me-1"></i>Venue images are managed in the Wedding Page → Venue sections below.</p>
            </div>
        </div>
    </div>

    {{-- ── GALLERY ───────────────────────────────────────────────── --}}
    @php $sec = 'gallery'; $sc = $secCfgOf($sec); @endphp
    <div class="card mb-3 section-card">
        <div class="card-header section-header" data-bs-toggle="collapse" data-bs-target="#edit_{{ $sec }}">
            <span><i class="fas fa-images me-2 text-secondary"></i>Gallery Section</span>
            @include('organizer._sp-vis-toggle', ['sec' => $sec, 'sc' => $sc])
        </div>
        <div class="sp-prev {{ !$isVis($sec) ? 'is-hidden' : '' }}" id="prev_panel_{{ $sec }}" style="background:#f9f7f4;">
            <div class="sp-prev-overlay">Section Hidden</div>
            <div class="prev-row prev-row-rev">
                <div class="prev-text">
                    <div class="prev-eyebrow" id="spv-{{ $sec }}-eyebrow" data-sp-fallback="A picture that worth a">{{ $valOf($sec,'eyebrow','A picture that worth a') }}</div>
                    <div class="prev-heading" id="spv-{{ $sec }}-heading" data-sp-fallback="Thousand Words">{{ $valOf($sec,'heading','Thousand Words') }}</div>
                    <span class="prev-link" id="spv-{{ $sec }}-link_text" data-sp-fallback="View Gallery">{{ $valOf($sec,'link_text','View Gallery') }}</span>
                </div>
                <div class="prev-imgs">
                    <img class="prev-img-single" data-sp-img-slot="gallery" src="{{ $imgUrl('gallery','lady_d_touch/gallery-couple.png') }}" alt="">
                </div>
            </div>
        </div>
        <div class="collapse {{ $isVis($sec) ? 'show' : '' }}" id="edit_{{ $sec }}">
            <div class="card-body">
                <div class="row g-3">
                    @foreach([['eyebrow','Eyebrow Label'],['heading','Heading'],['link_text','Link Text']] as [$k,$lbl])
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">{{ $lbl }}</label>
                        <input type="text" class="form-control form-control-sm"
                            name="sections[{{ $sec }}][{{ $k }}]" value="{{ $sc[$k] ?? '' }}"
                            placeholder="{{ $lbl }}"
                            data-sp-sec="{{ $sec }}" data-sp-field="{{ $k }}">
                    </div>
                    @endforeach
                </div>
                @include('organizer._sp-img-upload', ['slot'=>'gallery','label'=>'Gallery / Couple Image','fallback'=>'lady_d_touch/gallery-couple.png','spImgs'=>$spImgs,'uploadContext'=>$sec])
            </div>
        </div>
    </div>

    {{-- ── VENUES SHOWCASE ───────────────────────────────────────── --}}
    @php $sec = 'venues'; $sc = $secCfgOf($sec); @endphp
    <div class="card mb-3 section-card">
        <div class="card-header section-header" data-bs-toggle="collapse" data-bs-target="#edit_{{ $sec }}">
            <span><i class="fas fa-building me-2 text-secondary"></i>Venue Showcase Section</span>
            @include('organizer._sp-vis-toggle', ['sec' => $sec, 'sc' => $sc])
        </div>
        <div class="sp-prev {{ !$isVis($sec) ? 'is-hidden' : '' }}" id="prev_panel_{{ $sec }}">
            <div class="sp-prev-overlay">Section Hidden</div>
            <div class="prev-row">
                <div class="prev-imgs">
                    <div class="prev-img-mosaic">
                        <img data-sp-img-slot="venue_dataran" src="{{ $imgUrl('venue_dataran','DataranSriDusun-1024x682.jpg') }}" alt="">
                        <img data-sp-img-slot="venue_dewan"   src="{{ $imgUrl('venue_dewan','Pelamin_DSDusun_2024-1-1024x618.jpeg') }}" alt="">
                        <img data-sp-img-slot="venue_laman"   src="{{ $imgUrl('venue_laman','LamanDusun-1024x768.jpg') }}" alt="">
                    </div>
                </div>
                <div class="prev-text">
                    <div class="prev-heading" id="spv-{{ $sec }}-heading" data-sp-fallback="Wedding">{{ $valOf($sec,'heading','Wedding') }}</div>
                    <p class="prev-body" id="spv-{{ $sec }}-p1" data-sp-fallback="Be it a small romantic affair…">{{ $valOf($sec,'p1','Be it a small romantic affair…') }}</p>
                    <p class="prev-body" id="spv-{{ $sec }}-p2" data-sp-fallback="Compliment your event setting…">{{ $valOf($sec,'p2','Compliment your event setting…') }}</p>
                    <span class="prev-btn" id="spv-{{ $sec }}-btn_text" data-sp-fallback="♥ View Wedding Package">{{ $valOf($sec,'btn_text','♥ View Wedding Package') }}</span>
                </div>
            </div>
        </div>
        <div class="collapse {{ $isVis($sec) ? 'show' : '' }}" id="edit_{{ $sec }}">
            <div class="card-body">
                <div class="row g-3">
                    @foreach([['heading','Heading'],['p1','Paragraph 1'],['p2','Paragraph 2'],['btn_text','Button Text']] as [$k,$lbl])
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">{{ $lbl }}</label>
                        <input type="text" class="form-control form-control-sm"
                            name="sections[{{ $sec }}][{{ $k }}]" value="{{ $sc[$k] ?? '' }}"
                            placeholder="{{ $lbl }}"
                            data-sp-sec="{{ $sec }}" data-sp-field="{{ $k }}">
                    </div>
                    @endforeach
                </div>
                <p class="text-muted small mt-3 mb-0"><i class="fas fa-info-circle me-1"></i>Venue images are managed in the Wedding Page → Venue sections below.</p>
            </div>
        </div>
    </div>

    {{-- ── LOCATION ──────────────────────────────────────────────── --}}
    @php $sec = 'location'; $sc = $secCfgOf($sec); @endphp
    <div class="card mb-3 section-card">
        <div class="card-header section-header" data-bs-toggle="collapse" data-bs-target="#edit_{{ $sec }}">
            <span><i class="fas fa-map-marker-alt me-2 text-secondary"></i>Location Section</span>
            @include('organizer._sp-vis-toggle', ['sec' => $sec, 'sc' => $sc])
        </div>
        <div class="sp-prev prev-location {{ !$isVis($sec) ? 'is-hidden' : '' }}" id="prev_panel_{{ $sec }}">
            <div class="sp-prev-overlay">Section Hidden</div>
            <div class="prev-row">
                <div class="prev-text">
                    <div class="prev-heading" id="spv-{{ $sec }}-heading" data-sp-fallback="Our Location">{{ $valOf($sec,'heading','Our Location') }}</div>
                    @if($organizer->address_line1)
                    <div class="prev-location-item">
                        <svg width="12" height="12" fill="none" stroke="var(--sp-accent)" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:2px"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>{{ $organizer->address_line1 }}{{ $organizer->city ? ', '.$organizer->city : '' }}</span>
                    </div>
                    @endif
                    <div class="prev-hours">
                        <h6 id="spv-{{ $sec }}-hours_heading" data-sp-fallback="Opening Hours">{{ $valOf($sec,'hours_heading','Opening Hours') }}</h6>
                        <p id="spv-{{ $sec }}-hours_line1" data-sp-fallback="Sunday – Saturday (8am – 5pm)">{{ $valOf($sec,'hours_line1','Sunday – Saturday (8am – 5pm)') }}</p>
                        <p style="font-size:10px;color:#bbb;" id="spv-{{ $sec }}-hours_note" data-sp-fallback="*Viewing is by appointment only">{{ $valOf($sec,'hours_note','*Viewing is by appointment only') }}</p>
                    </div>
                </div>
                <div class="prev-imgs">
                    <img class="prev-img-single" data-sp-img-slot="map" src="{{ $imgUrl('map','lady_d_touch/map.jpeg') }}" alt="">
                </div>
            </div>
        </div>
        <div class="collapse {{ $isVis($sec) ? 'show' : '' }}" id="edit_{{ $sec }}">
            <div class="card-body">
                <div class="row g-3">
                    @foreach([['heading','Heading'],['hours_heading','Hours Heading'],['hours_line1','Hours Line'],['hours_note','Hours Note']] as [$k,$lbl])
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">{{ $lbl }}</label>
                        <input type="text" class="form-control form-control-sm"
                            name="sections[{{ $sec }}][{{ $k }}]" value="{{ $sc[$k] ?? '' }}"
                            placeholder="{{ $lbl }}"
                            data-sp-sec="{{ $sec }}" data-sp-field="{{ $k }}">
                    </div>
                    @endforeach
                </div>
                @include('organizer._sp-img-upload', ['slot'=>'map','label'=>'Map / Location Image','fallback'=>'lady_d_touch/map.jpeg','spImgs'=>$spImgs,'uploadContext'=>$sec])
            </div>
        </div>
    </div>

    {{-- ══════════════════ WEDDING PAGE ══════════════════════════════ --}}
    <h6 class="text-muted text-uppercase small fw-bold mb-2 mt-4">Wedding Page</h6>

    {{-- ── WEDDING HERO IMAGE ────────────────────────────────────── --}}
    <div class="card mb-3 section-card">
        <div class="card-header"><h6 class="mb-0"><i class="fas fa-camera me-2 text-secondary"></i>Wedding Hero Image</h6></div>
        <div class="sp-prev">
            <div class="prev-hero" data-sp-bg-slot="wedding_hero" style="height:130px;background-image:url('{{ $imgUrl('wedding_hero','Pelamin_wedding_hero.jpg') }}')">
                <div class="prev-hero-inner">
                    <div class="prev-hero-title" style="font-size:36px;">Wedding</div>
                </div>
            </div>
        </div>
        <div class="card-body">
            @include('organizer._sp-img-upload', ['slot'=>'wedding_hero','label'=>'Wedding Hero Background','fallback'=>'Pelamin_wedding_hero.jpg','spImgs'=>$spImgs,'uploadContext'=>'wedding_hero'])
        </div>
    </div>

    {{-- ── WEDDING INTRO ─────────────────────────────────────────── --}}
    @php $sec = 'wedding_intro'; $sc = $secCfgOf($sec); @endphp
    <div class="card mb-3 section-card">
        <div class="card-header section-header" data-bs-toggle="collapse" data-bs-target="#edit_{{ $sec }}">
            <span><i class="fas fa-feather-alt me-2 text-secondary"></i>Wedding Intro Text</span>
            @include('organizer._sp-vis-toggle', ['sec' => $sec, 'sc' => $sc])
        </div>
        <div class="sp-prev {{ !$isVis($sec) ? 'is-hidden' : '' }}" id="prev_panel_{{ $sec }}">
            <div class="sp-prev-overlay">Section Hidden</div>
            <div class="prev-intro">
                <p id="spv-{{ $sec }}-intro1" data-sp-fallback="Be it a small romantic affair…">{{ $valOf($sec,'intro1','Be it a small romantic affair…') }}</p>
                <p id="spv-{{ $sec }}-intro2" data-sp-fallback="Compliment your event setting…">{{ $valOf($sec,'intro2','Compliment your event setting…') }}</p>
                <p id="spv-{{ $sec }}-intro3" data-sp-fallback="At {{ $organizer->name }}, guests have the option…">{{ $valOf($sec,'intro3','At '.$organizer->name.', guests have the option…') }}</p>
                <p id="spv-{{ $sec }}-intro4" data-sp-fallback="The house includes the usage of bridal suite…">{{ $valOf($sec,'intro4','The house includes the usage of bridal suite…') }}</p>
            </div>
        </div>
        <div class="collapse {{ $isVis($sec) ? 'show' : '' }}" id="edit_{{ $sec }}">
            <div class="card-body">
                <div class="row g-3">
                    @foreach([['intro1','Paragraph 1'],['intro2','Paragraph 2'],['intro3','Paragraph 3'],['intro4','Paragraph 4']] as [$k,$lbl])
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">{{ $lbl }}</label>
                        <input type="text" class="form-control form-control-sm"
                            name="sections[{{ $sec }}][{{ $k }}]" value="{{ $sc[$k] ?? '' }}"
                            placeholder="{{ $lbl }}"
                            data-sp-sec="{{ $sec }}" data-sp-field="{{ $k }}">
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Overlay color partial (reused per venue) --}}
    {{-- ── VENUE DEWAN ───────────────────────────────────────────── --}}
    @php $sec = 'venue_dewan'; $sc = $secCfgOf($sec); @endphp
    <div class="card mb-3 section-card">
        <div class="card-header section-header" data-bs-toggle="collapse" data-bs-target="#edit_{{ $sec }}">
            <span><i class="fas fa-hotel me-2 text-secondary"></i>Venue 1 — Dewan</span>
            @include('organizer._sp-vis-toggle', ['sec' => $sec, 'sc' => $sc])
        </div>
        <div class="sp-prev prev-venue {{ !$isVis($sec) ? 'is-hidden' : '' }}" id="prev_panel_{{ $sec }}"
            data-sp-bg-slot="venue_dewan" style="background-image:url('{{ $imgUrl('venue_dewan','Pelamin_DSDusun_2024-1-1024x618.jpeg') }}')">
            <div class="sp-prev-overlay">Section Hidden</div>
            <div class="prev-venue-inner">
                <div class="prev-row">
                    <div class="prev-text">
                        <div class="prev-venue-name" id="spv-{{ $sec }}-name" data-sp-fallback="Dewan Sri Dusun">{{ $valOf($sec,'name','Dewan Sri Dusun') }}</div>
                        <p class="prev-venue-desc" id="spv-{{ $sec }}-desc" data-sp-fallback="Our Dewan Sri Dusun…">{{ $valOf($sec,'desc','Our Dewan Sri Dusun consists of Ruang Kaca…') }}</p>
                    </div>
                    <div class="prev-imgs">
                        <img class="prev-img-single" data-sp-img-slot="venue_dewan" src="{{ $imgUrl('venue_dewan','Pelamin_DSDusun_2024-1-1024x618.jpeg') }}" alt="" style="box-shadow:0 4px 16px rgba(0,0,0,0.2)">
                    </div>
                </div>
            </div>
        </div>
        <div class="collapse {{ $isVis($sec) ? 'show' : '' }}" id="edit_{{ $sec }}">
            <div class="card-body">
                <div class="row g-3">
                    @foreach([['name','Venue Name'],['desc','Description']] as [$k,$lbl])
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">{{ $lbl }}</label>
                        <input type="text" class="form-control form-control-sm"
                            name="sections[{{ $sec }}][{{ $k }}]" value="{{ $sc[$k] ?? '' }}"
                            placeholder="{{ $lbl }}"
                            data-sp-sec="{{ $sec }}" data-sp-field="{{ $k }}">
                    </div>
                    @endforeach
                </div>
                <div class="row g-3 mt-1 align-items-center">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Overlay Color</label>
                        <div class="d-flex align-items-center gap-2">
                            <input type="color" class="form-control form-control-color venue-overlay-color"
                                name="sections[{{ $sec }}][overlay_color]"
                                value="{{ $sc['overlay_color'] ?? '#f3ede5' }}"
                                data-sec="{{ $sec }}" style="width:44px;height:34px;">
                            <span class="form-text mb-0">Background tint</span>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label small fw-semibold">Overlay Opacity <span class="venue-opacity-val text-primary" data-sec="{{ $sec }}">{{ $sc['overlay_opacity'] ?? '0.90' }}</span></label>
                        <input type="range" class="form-range venue-overlay-opacity"
                            name="sections[{{ $sec }}][overlay_opacity]"
                            min="0" max="1" step="0.05"
                            value="{{ $sc['overlay_opacity'] ?? '0.90' }}"
                            data-sec="{{ $sec }}">
                    </div>
                </div>
                @include('organizer._sp-img-upload', ['slot'=>'venue_dewan','label'=>'Dewan Image','fallback'=>'Pelamin_DSDusun_2024-1-1024x618.jpeg','spImgs'=>$spImgs,'uploadContext'=>$sec])
            </div>
        </div>
    </div>

    {{-- ── VENUE DATARAN ─────────────────────────────────────────── --}}
    @php $sec = 'venue_dataran'; $sc = $secCfgOf($sec); @endphp
    <div class="card mb-3 section-card">
        <div class="card-header section-header" data-bs-toggle="collapse" data-bs-target="#edit_{{ $sec }}">
            <span><i class="fas fa-hotel me-2 text-secondary"></i>Venue 2 — Dataran</span>
            @include('organizer._sp-vis-toggle', ['sec' => $sec, 'sc' => $sc])
        </div>
        <div class="sp-prev prev-venue {{ !$isVis($sec) ? 'is-hidden' : '' }}" id="prev_panel_{{ $sec }}"
            data-sp-bg-slot="venue_dataran" style="background-image:url('{{ $imgUrl('venue_dataran','DataranSriDusun-1024x682.jpg') }}')">
            <div class="sp-prev-overlay">Section Hidden</div>
            <div class="prev-venue-inner">
                <div class="prev-row">
                    <div class="prev-text">
                        <div class="prev-venue-name" id="spv-{{ $sec }}-name" data-sp-fallback="Dataran Sri Dusun">{{ $valOf($sec,'name','Dataran Sri Dusun') }}</div>
                        <p class="prev-venue-desc" id="spv-{{ $sec }}-desc" data-sp-fallback="Dataran Sri Dusun is an outdoor space…">{{ $valOf($sec,'desc','Dataran Sri Dusun is an outdoor space…') }}</p>
                    </div>
                    <div class="prev-imgs">
                        <img class="prev-img-single" data-sp-img-slot="venue_dataran" src="{{ $imgUrl('venue_dataran','DataranSriDusun-1024x682.jpg') }}" alt="" style="box-shadow:0 4px 16px rgba(0,0,0,0.2)">
                    </div>
                </div>
            </div>
        </div>
        <div class="collapse {{ $isVis($sec) ? 'show' : '' }}" id="edit_{{ $sec }}">
            <div class="card-body">
                <div class="row g-3">
                    @foreach([['name','Venue Name'],['desc','Description']] as [$k,$lbl])
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">{{ $lbl }}</label>
                        <input type="text" class="form-control form-control-sm"
                            name="sections[{{ $sec }}][{{ $k }}]" value="{{ $sc[$k] ?? '' }}"
                            placeholder="{{ $lbl }}"
                            data-sp-sec="{{ $sec }}" data-sp-field="{{ $k }}">
                    </div>
                    @endforeach
                </div>
                <div class="row g-3 mt-1 align-items-center">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Overlay Color</label>
                        <div class="d-flex align-items-center gap-2">
                            <input type="color" class="form-control form-control-color venue-overlay-color"
                                name="sections[{{ $sec }}][overlay_color]"
                                value="{{ $sc['overlay_color'] ?? '#ebe4da' }}"
                                data-sec="{{ $sec }}" style="width:44px;height:34px;">
                            <span class="form-text mb-0">Background tint</span>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label small fw-semibold">Overlay Opacity <span class="venue-opacity-val text-primary" data-sec="{{ $sec }}">{{ $sc['overlay_opacity'] ?? '0.90' }}</span></label>
                        <input type="range" class="form-range venue-overlay-opacity"
                            name="sections[{{ $sec }}][overlay_opacity]"
                            min="0" max="1" step="0.05"
                            value="{{ $sc['overlay_opacity'] ?? '0.90' }}"
                            data-sec="{{ $sec }}">
                    </div>
                </div>
                @include('organizer._sp-img-upload', ['slot'=>'venue_dataran','label'=>'Dataran Image','fallback'=>'DataranSriDusun-1024x682.jpg','spImgs'=>$spImgs,'uploadContext'=>$sec])
            </div>
        </div>
    </div>

    {{-- ── VENUE LAMAN ───────────────────────────────────────────── --}}
    @php $sec = 'venue_laman'; $sc = $secCfgOf($sec); @endphp
    <div class="card mb-3 section-card">
        <div class="card-header section-header" data-bs-toggle="collapse" data-bs-target="#edit_{{ $sec }}">
            <span><i class="fas fa-hotel me-2 text-secondary"></i>Venue 3 — Laman</span>
            @include('organizer._sp-vis-toggle', ['sec' => $sec, 'sc' => $sc])
        </div>
        <div class="sp-prev prev-venue {{ !$isVis($sec) ? 'is-hidden' : '' }}" id="prev_panel_{{ $sec }}"
            data-sp-bg-slot="venue_laman" style="background-image:url('{{ $imgUrl('venue_laman','LamanDusun-1024x768.jpg') }}')">
            <div class="sp-prev-overlay">Section Hidden</div>
            <div class="prev-venue-inner">
                <div class="prev-row">
                    <div class="prev-text">
                        <div class="prev-venue-name" id="spv-{{ $sec }}-name" data-sp-fallback="Laman Dusun">{{ $valOf($sec,'name','Laman Dusun') }}</div>
                        <p class="prev-venue-desc" id="spv-{{ $sec }}-desc" data-sp-fallback="Our Laman Dusun is perfect…">{{ $valOf($sec,'desc','Our Laman Dusun is perfect for outdoor ceremonies…') }}</p>
                    </div>
                    <div class="prev-imgs">
                        <img class="prev-img-single" data-sp-img-slot="venue_laman" src="{{ $imgUrl('venue_laman','LamanDusun-1024x768.jpg') }}" alt="" style="box-shadow:0 4px 16px rgba(0,0,0,0.2)">
                    </div>
                </div>
            </div>
        </div>
        <div class="collapse {{ $isVis($sec) ? 'show' : '' }}" id="edit_{{ $sec }}">
            <div class="card-body">
                <div class="row g-3">
                    @foreach([['name','Venue Name'],['desc','Description']] as [$k,$lbl])
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">{{ $lbl }}</label>
                        <input type="text" class="form-control form-control-sm"
                            name="sections[{{ $sec }}][{{ $k }}]" value="{{ $sc[$k] ?? '' }}"
                            placeholder="{{ $lbl }}"
                            data-sp-sec="{{ $sec }}" data-sp-field="{{ $k }}">
                    </div>
                    @endforeach
                </div>
                <div class="row g-3 mt-1 align-items-center">
                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Overlay Color</label>
                        <div class="d-flex align-items-center gap-2">
                            <input type="color" class="form-control form-control-color venue-overlay-color"
                                name="sections[{{ $sec }}][overlay_color]"
                                value="{{ $sc['overlay_color'] ?? '#f3ede5' }}"
                                data-sec="{{ $sec }}" style="width:44px;height:34px;">
                            <span class="form-text mb-0">Background tint</span>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label small fw-semibold">Overlay Opacity <span class="venue-opacity-val text-primary" data-sec="{{ $sec }}">{{ $sc['overlay_opacity'] ?? '0.90' }}</span></label>
                        <input type="range" class="form-range venue-overlay-opacity"
                            name="sections[{{ $sec }}][overlay_opacity]"
                            min="0" max="1" step="0.05"
                            value="{{ $sc['overlay_opacity'] ?? '0.90' }}"
                            data-sec="{{ $sec }}">
                    </div>
                </div>
                @include('organizer._sp-img-upload', ['slot'=>'venue_laman','label'=>'Laman Image','fallback'=>'LamanDusun-1024x768.jpg','spImgs'=>$spImgs,'uploadContext'=>$sec])
            </div>
        </div>
    </div>

    <div class="d-flex gap-2 mt-4 mb-5">
        <button type="submit" class="btn btn-primary px-4">
            <i class="fas fa-save me-1"></i> Save Changes
        </button>
    </div>
    </form>

</div>
</div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    // ── Accent color ─────────────────────────────────────────────────
    var accentInput = document.getElementById('accentColor');
    function applyAccent(val) {
        document.documentElement.style.setProperty('--sp-accent', val);
        document.getElementById('accentHex').textContent = val;
    }
    applyAccent(accentInput.value);
    accentInput.addEventListener('input', function () { applyAccent(this.value); });

    // ── Font preview ─────────────────────────────────────────────────
    // All fonts are preloaded — just apply font-family directly.
    var HEADING_TARGETS = '.prev-heading, .prev-hero-title, .prev-venue-name, .prev-heading-sample';
    var BODY_TARGETS    = '.prev-body, .prev-intro p, .prev-venue-desc, .prev-location-item, .prev-hours p, .prev-body-sample';

    function applyHeadingFont(f) {
        document.querySelectorAll(HEADING_TARGETS).forEach(function (el) {
            el.style.fontFamily = "'" + f + "', cursive";
        });
    }
    function applyBodyFont(f) {
        document.querySelectorAll(BODY_TARGETS).forEach(function (el) {
            el.style.fontFamily = "'" + f + "', sans-serif";
        });
    }

    // Apply on page load
    applyHeadingFont(document.getElementById('headingFont').value);
    applyBodyFont(document.getElementById('bodyFont').value);

    // Apply on change
    document.getElementById('headingFont').addEventListener('change', function () { applyHeadingFont(this.value); });
    document.getElementById('bodyFont').addEventListener('change', function () { applyBodyFont(this.value); });

    // ── Venue overlay preview ────────────────────────────────────────
    function hexToRgba(hex, opacity) {
        var r = parseInt(hex.slice(1,3),16), g = parseInt(hex.slice(3,5),16), b = parseInt(hex.slice(5,7),16);
        return 'rgba(' + r + ',' + g + ',' + b + ',' + opacity + ')';
    }
    function updateVenueOverlay(sec) {
        var colorEl   = document.querySelector('.venue-overlay-color[data-sec="' + sec + '"]');
        var opacityEl = document.querySelector('.venue-overlay-opacity[data-sec="' + sec + '"]');
        var inner     = document.querySelector('#prev_panel_' + sec + ' .prev-venue-inner');
        if (!colorEl || !opacityEl || !inner) return;
        inner.style.background = hexToRgba(colorEl.value, opacityEl.value);
        var valEl = document.querySelector('.venue-opacity-val[data-sec="' + sec + '"]');
        if (valEl) valEl.textContent = opacityEl.value;
    }
    document.querySelectorAll('.venue-overlay-color').forEach(function (el) {
        updateVenueOverlay(el.dataset.sec);
        el.addEventListener('input', function () { updateVenueOverlay(this.dataset.sec); });
    });
    document.querySelectorAll('.venue-overlay-opacity').forEach(function (el) {
        el.addEventListener('input', function () { updateVenueOverlay(this.dataset.sec); });
    });

    // ── Real-time text preview ────────────────────────────────────────
    document.querySelectorAll('[data-sp-sec][data-sp-field]').forEach(function (input) {
        var target = document.getElementById('spv-' + input.dataset.spSec + '-' + input.dataset.spField);
        if (!target) return;
        input.addEventListener('input', function () {
            target.textContent = this.value || target.dataset.spFallback || '';
        });
    });

    // ── Visible toggle → preview overlay ─────────────────────────────
    document.querySelectorAll('.form-check-input[name^="sections["]').forEach(function (toggle) {
        var match = toggle.name.match(/sections\[([^\]]+)\]/);
        if (!match) return;
        var panel = document.getElementById('prev_panel_' + match[1]);
        if (!panel) return;
        function syncVis() { panel.classList.toggle('is-hidden', !toggle.checked); }
        syncVis();
        toggle.addEventListener('change', syncVis);
    });

    // ── Update ALL elements sharing the same slot ─────────────────────
    function updateSlotImages(slot, url) {
        // <img data-sp-img-slot="...">
        document.querySelectorAll('[data-sp-img-slot="' + slot + '"]').forEach(function (el) {
            el.src = url;
        });
        // elements with background-image data-sp-bg-slot="..."
        document.querySelectorAll('[data-sp-bg-slot="' + slot + '"]').forEach(function (el) {
            el.style.backgroundImage = 'url("' + url + '")';
        });
    }

    // ── File select → show thumbnail preview immediately ─────────────
    document.querySelectorAll('.sp-img-input').forEach(function (input) {
        input.addEventListener('change', function () {
            var thumb = document.getElementById(this.dataset.thumbId);
            if (!thumb || !this.files[0]) return;
            var reader = new FileReader();
            reader.onload = function (e) { thumb.src = e.target.result; };
            reader.readAsDataURL(this.files[0]);
        });
    });

    // ── Upload button → AJAX upload → update all previews ────────────
    document.querySelectorAll('.sp-upload-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var slot     = this.dataset.slot;
            var input    = document.getElementById(this.dataset.inputId);
            var status   = document.getElementById(this.dataset.statusId);

            if (!input || !input.files.length) {
                if (status) status.innerHTML = '<span class="text-warning">Select an image first.</span>';
                return;
            }

            var fd = new FormData();
            fd.append('slot', slot);
            fd.append('image', input.files[0]);
            fd.append('_token', '{{ csrf_token() }}');

            btn.disabled = true;
            if (status) status.innerHTML = '<span class="text-muted"><i class="fas fa-spinner fa-spin me-1"></i>Uploading…</span>';

            fetch('{{ route("organizer.business.special-page.upload-image") }}', { method: 'POST', body: fd })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    if (data.success) {
                        if (status) status.innerHTML = '<span class="text-success"><i class="fas fa-check me-1"></i>Uploaded.</span>';
                        var url = data.url + '?t=' + Date.now();
                        updateSlotImages(slot, url);
                        // Also update the inline thumb
                        var thumb = document.getElementById(input.dataset.thumbId);
                        if (thumb) thumb.src = url;
                    } else {
                        if (status) status.innerHTML = '<span class="text-danger">Upload failed.</span>';
                    }
                })
                .catch(function () {
                    if (status) status.innerHTML = '<span class="text-danger">Upload error.</span>';
                })
                .finally(function () { btn.disabled = false; });
        });
    });
})();
</script>
@endpush
