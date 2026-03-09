@extends('home.homeLayout')

@section('title', $seo['title'] ?? 'FAQ | Sistem Kami')

@push('styles')
<style>
.faq-hero {
    background: linear-gradient(135deg, #001f4d 0%, #003080 100%);
    padding: 80px 0 60px;
}
.faq-section { padding: 60px 0; }
.faq-category-title {
    font-size: 1rem;
    font-weight: 700;
    color: #001f4d;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 16px;
    padding-bottom: 8px;
    border-bottom: 2px solid #e8edf5;
}
/* Custom accordion (no Bootstrap JS dependency) */
.sk-accordion-item {
    border: 1px solid #e8edf5;
    border-radius: 8px;
    margin-bottom: 8px;
    overflow: hidden;
}
.sk-accordion-btn {
    width: 100%;
    text-align: left;
    background: #fff;
    border: none;
    padding: 14px 18px;
    font-weight: 600;
    font-size: 0.95rem;
    color: #1a2942;
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: background 0.2s;
}
.sk-accordion-btn:hover { background: #f8faff; }
.sk-accordion-btn.open { background: #f0f4ff; color: #001f4d; }
.sk-accordion-btn .sk-chevron {
    width: 18px;
    height: 18px;
    flex-shrink: 0;
    transition: transform 0.25s ease;
    color: #6b7280;
}
.sk-accordion-btn.open .sk-chevron { transform: rotate(180deg); }
.sk-accordion-body {
    display: none;
    padding: 14px 18px 16px;
    font-size: 0.9rem;
    color: #4a5568;
    line-height: 1.7;
    border-top: 1px solid #f0f4f8;
}
.sk-accordion-body.open { display: block; }
.faq-animate {
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 0.5s ease, transform 0.5s ease;
}
.faq-animate.visible {
    opacity: 1;
    transform: translateY(0);
}
</style>
@endpush

@section('content')

{{-- Hero --}}
<section class="faq-hero text-white text-center">
    <div class="container">
        <h1 class="fw-bold mb-3" style="font-size: clamp(2rem, 4vw, 3rem); color: #fff;">Frequently Asked Questions</h1>
        <p class="mb-0" style="color: rgba(255,255,255,0.75); max-width: 560px; margin: 0 auto; font-size: 1.05rem;">
            Everything you need to know about Sistem Kami. Can't find your answer? <a href="https://wa.me/601123053082" target="_blank" style="color:#93c5fd;">Chat with us.</a>
        </p>
    </div>
</section>

{{-- FAQ Content --}}
<section class="faq-section bg-white">
    <div class="container" style="max-width: 780px;">

        {{-- General --}}
        <div class="mb-5 faq-animate">
            <div class="faq-category-title">General</div>
            <div class="sk-accordion">

                <div class="sk-accordion-item">
                    <button class="sk-accordion-btn open" type="button">
                        What is Sistem Kami?
                        <svg class="sk-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="sk-accordion-body open">
                        Sistem Kami is a cloud-based booking and business management platform designed for studios, service providers, and event organizers in Malaysia. It helps you manage your packages, time slots, bookings, payments, workers, and customer communications — all in one place.
                    </div>
                </div>

                <div class="sk-accordion-item">
                    <button class="sk-accordion-btn" type="button">
                        Who is Sistem Kami for?
                        <svg class="sk-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="sk-accordion-body">
                        Sistem Kami is built for any business that takes bookings — fishing clubs, photography studios, sports facilities, event planners, and more. If you manage time slots, packages, and customers, Sistem Kami is designed for you.
                    </div>
                </div>

                <div class="sk-accordion-item">
                    <button class="sk-accordion-btn" type="button">
                        Is Sistem Kami available in Bahasa Malaysia?
                        <svg class="sk-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="sk-accordion-body">
                        The dashboard is currently in English, but we are working on full Bahasa Malaysia support. Customer-facing communication via WhatsApp can be customized in any language by the organizer.
                    </div>
                </div>

            </div>
        </div>

        {{-- Booking & Packages --}}
        <div class="mb-5 faq-animate">
            <div class="faq-category-title">Booking & Packages</div>
            <div class="sk-accordion">

                <div class="sk-accordion-item">
                    <button class="sk-accordion-btn" type="button">
                        How do customers make a booking?
                        <svg class="sk-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="sk-accordion-body">
                        Customers can browse your packages on your public profile page and select a time slot to book. They fill in their details and pay online. Alternatively, you can also accept manual bookings — customers send a WhatsApp message and you create the booking from your dashboard.
                    </div>
                </div>

                <div class="sk-accordion-item">
                    <button class="sk-accordion-btn" type="button">
                        Can I manage multiple packages and time slots?
                        <svg class="sk-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="sk-accordion-body">
                        Yes. You can create unlimited packages, each with their own time slots, pricing, capacity, and availability. Special dates, off-days, and slot-level customization are all supported.
                    </div>
                </div>

                <div class="sk-accordion-item">
                    <button class="sk-accordion-btn" type="button">
                        Can I block out dates or set off-days?
                        <svg class="sk-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="sk-accordion-body">
                        Yes. You can set recurring off-days (e.g. every Monday), specific blocked dates, and custom opening hours per slot. This gives you full control over your availability.
                    </div>
                </div>

                <div class="sk-accordion-item">
                    <button class="sk-accordion-btn" type="button">
                        Can I create bookings on behalf of customers?
                        <svg class="sk-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="sk-accordion-body">
                        Yes. Organizers can create manual bookings directly from the dashboard. This is useful for walk-in customers or bookings received through WhatsApp.
                    </div>
                </div>

            </div>
        </div>

        {{-- Payments --}}
        <div class="mb-5 faq-animate">
            <div class="faq-category-title">Payments</div>
            <div class="sk-accordion">

                <div class="sk-accordion-item">
                    <button class="sk-accordion-btn" type="button">
                        What payment methods are supported?
                        <svg class="sk-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="sk-accordion-body">
                        Sistem Kami supports online payments via <strong>Toyyibpay</strong> (FPX, debit/credit card) and <strong>Stripe</strong>. Manual payment collection (cash, bank transfer) with QR code display is also supported.
                    </div>
                </div>

                <div class="sk-accordion-item">
                    <button class="sk-accordion-btn" type="button">
                        Is there a transaction fee?
                        <svg class="sk-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="sk-accordion-body">
                        Sistem Kami does not charge additional transaction fees beyond the payment gateway's standard rates. Toyyibpay and Stripe have their own processing fees — please refer to their respective websites for current rates.
                    </div>
                </div>

                <div class="sk-accordion-item">
                    <button class="sk-accordion-btn" type="button">
                        Can I accept deposits instead of full payment?
                        <svg class="sk-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="sk-accordion-body">
                        Yes. Deposit-based booking is supported. You can configure the deposit amount per package and collect the remaining balance manually before the booking date.
                    </div>
                </div>

            </div>
        </div>

        {{-- Workers & Team --}}
        <div class="mb-5 faq-animate">
            <div class="faq-category-title">Workers & Team</div>
            <div class="sk-accordion">

                <div class="sk-accordion-item">
                    <button class="sk-accordion-btn" type="button">
                        Can I add workers or staff to my account?
                        <svg class="sk-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="sk-accordion-body">
                        Yes. You can add workers with their own login access. Workers can view bookings, perform check-ins, and manage their assigned tasks — without access to your financial or admin settings.
                    </div>
                </div>

                <div class="sk-accordion-item">
                    <button class="sk-accordion-btn" type="button">
                        Does the system support worker commissions?
                        <svg class="sk-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="sk-accordion-body">
                        Yes. You can configure commission rates per worker and the system will track their earnings based on completed bookings.
                    </div>
                </div>

            </div>
        </div>

        {{-- Notifications --}}
        <div class="mb-5 faq-animate">
            <div class="faq-category-title">Notifications</div>
            <div class="sk-accordion">

                <div class="sk-accordion-item">
                    <button class="sk-accordion-btn" type="button">
                        Does the system send booking reminders?
                        <svg class="sk-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="sk-accordion-body">
                        Yes. Sistem Kami sends automated WhatsApp reminders to customers before their booking date (via Fonnte). You can configure quiet hours so reminders are only sent at appropriate times.
                    </div>
                </div>

                <div class="sk-accordion-item">
                    <button class="sk-accordion-btn" type="button">
                        What notifications does the organizer receive?
                        <svg class="sk-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="sk-accordion-body">
                        Organizers receive notifications for new bookings, payment confirmations, and upcoming sessions. Notifications are visible in the dashboard and optionally via WhatsApp.
                    </div>
                </div>

            </div>
        </div>

        {{-- CTA --}}
        <div class="text-center py-5 faq-animate">
            <p class="text-muted mb-3">Still have questions?</p>
            <a href="https://wa.me/601123053082?text=Hi%20SistemKami,%20saya%20nak%20tanya%20tentang%20platform%20anda." target="_blank" class="btn btn-primary px-4">
                <i class="fab fa-whatsapp me-2"></i> Chat with us on WhatsApp
            </a>
        </div>

    </div>
</section>

@endsection

@push('scripts')
<script>
(function () {
    // Scroll animation
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.classList.add('visible');
                observer.unobserve(e.target);
            }
        });
    }, { threshold: 0.1 });
    document.querySelectorAll('.faq-animate').forEach(el => observer.observe(el));

    // Custom accordion (no Bootstrap JS dependency)
    document.querySelectorAll('.sk-accordion-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const body = this.nextElementSibling;
            const isOpen = this.classList.contains('open');

            // Close all in same accordion group
            const accordion = this.closest('.sk-accordion');
            accordion.querySelectorAll('.sk-accordion-btn').forEach(function (b) {
                b.classList.remove('open');
                b.nextElementSibling.classList.remove('open');
            });

            // Toggle clicked
            if (!isOpen) {
                this.classList.add('open');
                body.classList.add('open');
            }
        });
    });
})();
</script>
@endpush
