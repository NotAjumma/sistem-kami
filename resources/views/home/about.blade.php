@extends('home.homeLayout')

@push('styles')
<style>
    /* Fade-in-up animation */
    .fade-in-up {
        opacity: 0;
        transform: translateY(30px);
        transition: opacity 0.6s ease-out, transform 0.6s ease-out;
    }
    .fade-in-up.visible {
        opacity: 1;
        transform: translateY(0);
    }

    /* Stagger delays for children */
    .stagger-1 { transition-delay: 0.1s; }
    .stagger-2 { transition-delay: 0.2s; }
    .stagger-3 { transition-delay: 0.3s; }
    .stagger-4 { transition-delay: 0.4s; }
    .stagger-5 { transition-delay: 0.5s; }
    .stagger-6 { transition-delay: 0.6s; }

    /* Feature card hover */
    .feature-card {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .feature-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 24px rgba(0, 31, 77, 0.12);
    }
    .feature-card .feature-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        transition: transform 0.3s ease;
    }
    .feature-card:hover .feature-icon {
        transform: scale(1.1);
    }

    /* Mission/Vision/Values cards */
    .value-card {
        background: #f8fafc;
        border-radius: 12px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .value-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0, 31, 77, 0.08);
    }

    /* CTA section gradient */
    .cta-section {
        background: linear-gradient(135deg, #001f4d 0%, #003380 100%);
    }

    /* Counter animation */
    .counter-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: #001f4d;
    }
</style>
@endpush

@section('content')
<main class="bg-white">
    <!-- Hero Section -->
    <section style="background: linear-gradient(135deg, #001f4d 0%, #003080 100%); padding: 80px 0 60px;">
        <div class="container text-center">
            <div class="fade-in-up">
                <p class="fw-bold text-uppercase mb-2" style="letter-spacing: 0.1em; font-size: 0.85rem; color: rgba(255,255,255,0.55);">{{ __('about.hero_label') }}</p>
                <h1 class="fw-bold mb-3" style="font-size: clamp(1.8rem, 4vw, 2.8rem); color: #fff !important;">{{ __('about.hero_heading') }}</h1>
                <p class="lead mb-0" style="max-width: 700px; margin: 0 auto; font-family: 'Poppins', sans-serif; color: rgba(255,255,255,0.7);">
                    {{ __('about.hero_sub') }}
                </p>
            </div>
        </div>
    </section>

    <!-- Company Info Section -->
    <section class="py-5 bg-white">
        <div class="container" style="max-width: 900px;">
            <div class="text-center mb-5 fade-in-up">
                <h2 class="fw-bold mb-4" style="font-size: 1.8rem; color: #001f4d;">{{ __('about.who_we_are_heading') }}</h2>
                <p style="font-size: 1.05rem; color: #545454; line-height: 1.8;">
                    {{ __('about.who_we_are_p1') }}
                </p>
                <p style="font-size: 1.05rem; color: #545454; line-height: 1.8;">
                    {{ __('about.who_we_are_p2') }}
                </p>
            </div>

            <!-- Mission / Vision / Values -->
            <div class="row text-center mb-5">
                <div class="col-md-4 mb-4 fade-in-up stagger-1">
                    <div class="value-card p-4 h-100">
                        <div class="mb-3">
                            <i class="fas fa-bullseye fa-2x" style="color: #001f4d;"></i>
                        </div>
                        <h5 class="fw-bold">{{ __('about.mission_heading') }}</h5>
                        <p class="text-muted small mb-0">
                            {{ __('about.mission_desc') }}
                        </p>
                    </div>
                </div>
                <div class="col-md-4 mb-4 fade-in-up stagger-2">
                    <div class="value-card p-4 h-100">
                        <div class="mb-3">
                            <i class="fas fa-eye fa-2x" style="color: #001f4d;"></i>
                        </div>
                        <h5 class="fw-bold">{{ __('about.vision_heading') }}</h5>
                        <p class="text-muted small mb-0">
                            {{ __('about.vision_desc') }}
                        </p>
                    </div>
                </div>
                <div class="col-md-4 mb-4 fade-in-up stagger-3">
                    <div class="value-card p-4 h-100">
                        <div class="mb-3">
                            <i class="fas fa-heart fa-2x" style="color: #001f4d;"></i>
                        </div>
                        <h5 class="fw-bold">{{ __('about.values_heading') }}</h5>
                        <p class="text-muted small mb-0">
                            {{ __('about.values_desc') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5" style="background: #f8fafc;">
        <div class="container">
            <div class="text-center mx-auto mb-5 fade-in-up" style="max-width: 800px;">
                <p class="text-primary fw-bold text-uppercase mb-2" style="letter-spacing: 0.1em; font-size: 0.85rem;">{{ __('about.features_label') }}</p>
                <h2 class="fw-bold mb-3" style="font-size: 2rem; color: #001f4d;">{{ __('about.features_heading') }}</h2>
                <p class="text-muted">{{ __('about.features_sub') }}</p>
            </div>
            <div class="row g-4 justify-content-center">
                <div class="col-md-6 col-lg-4 fade-in-up stagger-1">
                    <div class="feature-card p-4 h-100">
                        <div class="feature-icon" style="background: rgba(0, 31, 77, 0.08);">
                            <i class="fas fa-calendar-check fa-lg" style="color: #001f4d;"></i>
                        </div>
                        <h5 class="fw-bold mb-2">{{ __('home.booking_system') }}</h5>
                        <p class="text-muted small mb-0">{{ __('home.booking_system_desc') }}</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 fade-in-up stagger-2">
                    <div class="feature-card p-4 h-100">
                        <div class="feature-icon" style="background: rgba(0, 31, 77, 0.08);">
                            <i class="fas fa-boxes fa-lg" style="color: #001f4d;"></i>
                        </div>
                        <h5 class="fw-bold mb-2">{{ __('home.package_slot') }}</h5>
                        <p class="text-muted small mb-0">{{ __('home.package_slot_desc') }}</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 fade-in-up stagger-3">
                    <div class="feature-card p-4 h-100">
                        <div class="feature-icon" style="background: rgba(0, 31, 77, 0.08);">
                            <i class="fas fa-credit-card fa-lg" style="color: #001f4d;"></i>
                        </div>
                        <h5 class="fw-bold mb-2">{{ __('home.secure_payment') }}</h5>
                        <p class="text-muted small mb-0">{{ __('home.secure_payment_desc') }}</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 fade-in-up stagger-4">
                    <div class="feature-card p-4 h-100">
                        <div class="feature-icon" style="background: rgba(0, 31, 77, 0.08);">
                            <i class="fas fa-chart-line fa-lg" style="color: #001f4d;"></i>
                        </div>
                        <h5 class="fw-bold mb-2">{{ __('home.dashboard_analytics') }}</h5>
                        <p class="text-muted small mb-0">{{ __('home.dashboard_analytics_desc') }}</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 fade-in-up stagger-5">
                    <div class="feature-card p-4 h-100">
                        <div class="feature-icon" style="background: rgba(0, 31, 77, 0.08);">
                            <i class="fas fa-users-cog fa-lg" style="color: #001f4d;"></i>
                        </div>
                        <h5 class="fw-bold mb-2">{{ __('home.worker_commission') }}</h5>
                        <p class="text-muted small mb-0">{{ __('home.worker_commission_desc') }}</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 fade-in-up stagger-6">
                    <div class="feature-card p-4 h-100">
                        <div class="feature-icon" style="background: rgba(0, 31, 77, 0.08);">
                            <i class="fas fa-bell fa-lg" style="color: #001f4d;"></i>
                        </div>
                        <h5 class="fw-bold mb-2">{{ __('home.notifications') }}</h5>
                        <p class="text-muted small mb-0">{{ __('home.notifications_desc') }}</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 fade-in-up stagger-1">
                    <div class="feature-card p-4 h-100">
                        <div class="feature-icon" style="background: rgba(99, 102, 241, 0.1);">
                            <i class="fas fa-robot fa-lg" style="color: #6366f1;"></i>
                        </div>
                        <h5 class="fw-bold mb-2">{{ __('home.ai_chatbot') }}</h5>
                        <p class="text-muted small mb-0">{{ __('home.ai_chatbot_desc') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section py-5">
        <div class="container text-center py-4 fade-in-up" style="max-width: 700px;">
            <h2 class="fw-bold mb-3 text-white" style="font-size: 1.8rem;">{{ __('about.cta_heading') }}</h2>
            <p class="mb-4" style="color: rgba(255,255,255,0.7);">
                {{ __('about.cta_sub') }}
            </p>
            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="{{ route('organizer.register') }}" class="btn btn-light fw-bold px-4 py-2" style="color: #001f4d;">
                    {{ __('about.cta_register') }}
                </a>
                <a href="https://wa.me/601123053082?text=Hi%20SistemKami,%20saya%20nak%20tanya%20tentang%20platform%20anda."
                   target="_blank"
                   class="btn btn-outline-light px-4 py-2">
                    <i class="fab fa-whatsapp me-1"></i> {{ __('about.cta_contact') }}
                </a>
            </div>
        </div>
    </section>
</main>
@endsection

@push('scripts')
<script>
    // Intersection Observer for scroll-triggered animations
    document.addEventListener('DOMContentLoaded', function() {
        var observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.fade-in-up').forEach(function(el) {
            observer.observe(el);
        });
    });
</script>
@endpush