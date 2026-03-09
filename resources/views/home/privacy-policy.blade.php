@extends('home.homeLayout')

@section('title', $seo['title'] ?? 'Privacy Policy | Sistem Kami')

@push('styles')
<style>
.policy-hero {
    background: linear-gradient(135deg, #001f4d 0%, #003080 100%);
    padding: 80px 0 60px;
}
.policy-body {
    padding: 60px 0 80px;
    font-size: 0.95rem;
    color: #374151;
    line-height: 1.8;
}
.policy-body h2 {
    font-size: 1.15rem;
    font-weight: 700;
    color: #001f4d;
    margin-top: 2.5rem;
    margin-bottom: 0.75rem;
    padding-bottom: 6px;
    border-bottom: 2px solid #e8edf5;
}
.policy-body p, .policy-body ul { margin-bottom: 1rem; }
.policy-body ul { padding-left: 1.4rem; }
.policy-body ul li { margin-bottom: 0.4rem; }
.policy-body a { color: #001f4d; }
.policy-toc {
    background: #f8faff;
    border: 1px solid #e8edf5;
    border-radius: 10px;
    padding: 20px 24px;
    margin-bottom: 2.5rem;
}
.policy-toc a { color: #3563a8; text-decoration: none; font-size: 0.88rem; }
.policy-toc a:hover { text-decoration: underline; }
</style>
@endpush

@section('content')

<section class="policy-hero text-white text-center">
    <div class="container">
        <h1 class="fw-bold mb-3" style="font-size: clamp(1.8rem, 4vw, 2.8rem);">Privacy Policy</h1>
        <p class="mb-0" style="color: rgba(255,255,255,0.7);">Last updated: {{ date('d F Y') }}</p>
    </div>
</section>

<section class="policy-body bg-white">
    <div class="container" style="max-width: 780px;">

        <div class="policy-toc">
            <div class="fw-semibold mb-2" style="font-size:0.88rem; color:#001f4d;">Contents</div>
            <ol class="mb-0 ps-3" style="line-height:2">
                <li><a href="#info-we-collect">Information We Collect</a></li>
                <li><a href="#how-we-use">How We Use Your Information</a></li>
                <li><a href="#cookies">Cookies & Analytics</a></li>
                <li><a href="#third-parties">Third-Party Services</a></li>
                <li><a href="#data-retention">Data Retention</a></li>
                <li><a href="#your-rights">Your Rights</a></li>
                <li><a href="#security">Security</a></li>
                <li><a href="#changes">Changes to This Policy</a></li>
                <li><a href="#contact">Contact Us</a></li>
            </ol>
        </div>

        <p>
            Sistem Kami ("we", "us", or "our") operates the website <strong>sistemkami.com</strong> and provides a booking and business management platform. This Privacy Policy explains how we collect, use, and protect your personal information when you use our services.
        </p>
        <p>By using Sistem Kami, you agree to the collection and use of information as described in this policy.</p>

        <h2 id="info-we-collect">1. Information We Collect</h2>
        <p>We collect the following types of information:</p>
        <ul>
            <li><strong>Personal information</strong> — name, phone number, email address, and other details provided when creating an account or making a booking.</li>
            <li><strong>Booking data</strong> — package selections, time slots, payment status, and booking history.</li>
            <li><strong>Business information</strong> — organizer profiles, package details, pricing, and operating hours.</li>
            <li><strong>Usage data</strong> — pages visited, time spent, device type, and browser information collected via cookies and analytics.</li>
            <li><strong>Payment information</strong> — payment is processed by third-party gateways (Toyyibpay, Stripe). We do not store full card details.</li>
        </ul>

        <h2 id="how-we-use">2. How We Use Your Information</h2>
        <p>We use the information we collect to:</p>
        <ul>
            <li>Process and manage your bookings</li>
            <li>Send booking confirmations and reminders via WhatsApp or email</li>
            <li>Enable organizers to manage their business and communicate with customers</li>
            <li>Improve platform performance and user experience</li>
            <li>Comply with legal obligations</li>
            <li>Detect and prevent fraud or misuse</li>
        </ul>
        <p>We do not sell your personal information to third parties.</p>

        <h2 id="cookies">3. Cookies & Analytics</h2>
        <p>
            We use <strong>Google Analytics</strong> to understand how visitors interact with our platform. Google Analytics uses cookies to collect anonymized data such as page views, session duration, and traffic sources.
        </p>
        <p>
            We also use functional cookies that are essential for the operation of the platform (e.g. session management, CSRF protection). These cannot be disabled without affecting core functionality.
        </p>
        <p>
            You can control analytics cookies through our cookie consent banner. Declining analytics cookies will prevent Google Analytics from tracking your visit. For more information, see <a href="https://policies.google.com/privacy" target="_blank">Google's Privacy Policy</a>.
        </p>

        <h2 id="third-parties">4. Third-Party Services</h2>
        <p>We use the following third-party services, each with their own privacy policies:</p>
        <ul>
            <li><strong>Google Analytics</strong> — website analytics</li>
            <li><strong>Toyyibpay</strong> — online payment processing</li>
            <li><strong>Stripe</strong> — online payment processing</li>
            <li><strong>Fonnte</strong> — WhatsApp notification delivery</li>
        </ul>
        <p>We share only the minimum information necessary with these services to provide their functionality.</p>

        <h2 id="data-retention">5. Data Retention</h2>
        <p>
            We retain your personal information for as long as your account is active or as needed to provide services. Organizer accounts and their booking data are retained for a minimum of 12 months after account closure for legal and audit purposes.
        </p>
        <p>You may request deletion of your data at any time by contacting us.</p>

        <h2 id="your-rights">6. Your Rights</h2>
        <p>You have the right to:</p>
        <ul>
            <li>Access the personal data we hold about you</li>
            <li>Request correction of inaccurate information</li>
            <li>Request deletion of your data (subject to legal requirements)</li>
            <li>Withdraw consent for analytics cookies at any time</li>
            <li>Lodge a complaint with the relevant data protection authority</li>
        </ul>
        <p>To exercise any of these rights, please contact us using the details below.</p>

        <h2 id="security">7. Security</h2>
        <p>
            We implement appropriate technical and organizational measures to protect your personal data against unauthorized access, alteration, disclosure, or destruction. All data is transmitted over HTTPS. Payment processing is handled by PCI-compliant third-party gateways.
        </p>
        <p>No method of transmission over the internet is 100% secure. While we strive to protect your information, we cannot guarantee absolute security.</p>

        <h2 id="changes">8. Changes to This Policy</h2>
        <p>
            We may update this Privacy Policy from time to time. We will notify you of significant changes by posting the updated policy on this page with a revised date. Continued use of the platform after changes constitutes acceptance of the updated policy.
        </p>

        <h2 id="contact">9. Contact Us</h2>
        <p>If you have any questions about this Privacy Policy, please contact us:</p>
        <ul>
            <li><strong>Email:</strong> <a href="mailto:salessistemkami@gmail.com">salessistemkami@gmail.com</a></li>
            <li><strong>WhatsApp:</strong> <a href="https://wa.me/601123053082" target="_blank">+60 11-2405 3082</a></li>
            <li><strong>Address:</strong> Ipoh, Perak, Malaysia</li>
        </ul>

    </div>
</section>

@endsection
