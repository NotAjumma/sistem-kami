@extends('home.homeLayout')

@section('title', $seo['title'] ?? 'Terms & Conditions | Sistem Kami')

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
        <h1 class="fw-bold mb-3" style="font-size: clamp(1.8rem, 4vw, 2.8rem); color: #fff !important;">{{ __('terms.hero_heading') }}</h1>
        <p class="mb-0" style="color: rgba(255,255,255,0.7);">{{ __('terms.last_updated') }} {{ date('d F Y') }}</p>
    </div>
</section>

<section class="policy-body bg-white">
    <div class="container" style="max-width: 780px;">

        <div class="policy-toc">
            <div class="fw-semibold mb-2" style="font-size:0.88rem; color:#001f4d;">{{ __('terms.contents') }}</div>
            <ol class="mb-0 ps-3" style="line-height:2">
                <li><a href="#acceptance">Acceptance of Terms</a></li>
                <li><a href="#services">Our Services</a></li>
                <li><a href="#organizer-accounts">Organizer Accounts</a></li>
                <li><a href="#customer-bookings">Customer Bookings</a></li>
                <li><a href="#payments">Payments & Refunds</a></li>
                <li><a href="#acceptable-use">Acceptable Use</a></li>
                <li><a href="#intellectual-property">Intellectual Property</a></li>
                <li><a href="#disclaimers">Disclaimers</a></li>
                <li><a href="#limitation">Limitation of Liability</a></li>
                <li><a href="#termination">Termination</a></li>
                <li><a href="#governing-law">Governing Law</a></li>
                <li><a href="#changes">Changes to Terms</a></li>
                <li><a href="#contact">Contact Us</a></li>
            </ol>
        </div>

        <p>
            Please read these Terms & Conditions ("Terms") carefully before using the Sistem Kami platform ("Service") operated by Sistem Kami ("we", "us", or "our").
        </p>
        <p>By accessing or using Sistem Kami, you agree to be bound by these Terms. If you do not agree, please do not use the Service.</p>

        <h2 id="acceptance">1. Acceptance of Terms</h2>
        <p>
            By registering an account, making a booking, or otherwise using Sistem Kami, you confirm that you are at least 18 years old and have the legal capacity to enter into these Terms. If you are using the Service on behalf of a business, you represent that you have authority to bind that business to these Terms.
        </p>

        <h2 id="services">2. Our Services</h2>
        <p>
            Sistem Kami provides a software platform that enables businesses ("Organizers") to manage packages, time slots, bookings, payments, and customer communications. We act as a technology platform only and are not a party to any transaction between Organizers and their customers.
        </p>

        <h2 id="organizer-accounts">3. Organizer Accounts</h2>
        <ul>
            <li>You are responsible for maintaining the confidentiality of your login credentials.</li>
            <li>You must provide accurate and up-to-date information when registering your account.</li>
            <li>You are solely responsible for all activity that occurs under your account.</li>
            <li>You must notify us immediately of any unauthorized access to your account.</li>
            <li>Organizer accounts are non-transferable without our prior written consent.</li>
        </ul>

        <h2 id="customer-bookings">4. Customer Bookings</h2>
        <p>
            Bookings made through Sistem Kami are agreements between the customer and the Organizer. Sistem Kami facilitates the booking process but is not responsible for the quality, safety, or delivery of services provided by Organizers.
        </p>
        <ul>
            <li>Organizers are responsible for clearly communicating their booking terms, cancellation policies, and service details to customers.</li>
            <li>Customers must review booking details carefully before confirming.</li>
            <li>Sistem Kami does not guarantee availability of any specific time slot or service.</li>
        </ul>

        <h2 id="payments">5. Payments & Refunds</h2>
        <ul>
            <li>Payments are processed by third-party gateways (Toyyibpay, Stripe). By making a payment, you also agree to their respective terms.</li>
            <li>Sistem Kami does not control Organizer refund policies. Refund requests must be directed to the Organizer.</li>
            <li>In cases of payment disputes, you may contact the relevant payment gateway directly.</li>
            <li>Sistem Kami reserves the right to suspend or terminate accounts involved in fraudulent payment activity.</li>
        </ul>

        <h2 id="acceptable-use">6. Acceptable Use</h2>
        <p>You agree not to:</p>
        <ul>
            <li>Use the platform for any illegal or unauthorized purpose</li>
            <li>Upload false, misleading, or defamatory content</li>
            <li>Attempt to gain unauthorized access to any part of the platform</li>
            <li>Interfere with or disrupt the platform's servers or networks</li>
            <li>Use automated tools to scrape or collect data without our permission</li>
            <li>Impersonate any person or entity</li>
            <li>Violate any applicable laws or regulations</li>
        </ul>

        <h2 id="intellectual-property">7. Intellectual Property</h2>
        <p>
            All content, design, code, logos, and trademarks on Sistem Kami are owned by or licensed to us. You may not reproduce, distribute, or create derivative works without our written permission.
        </p>
        <p>
            You retain ownership of any content you upload (e.g. package images, business descriptions). By uploading content, you grant us a non-exclusive, royalty-free licence to display and use that content in connection with providing the Service.
        </p>

        <h2 id="disclaimers">8. Disclaimers</h2>
        <p>
            The Service is provided "as is" and "as available" without warranties of any kind, express or implied. We do not warrant that the Service will be uninterrupted, error-free, or free of viruses. We are not responsible for any decisions made based on information displayed on the platform.
        </p>

        <h2 id="limitation">9. Limitation of Liability</h2>
        <p>
            To the maximum extent permitted by law, Sistem Kami shall not be liable for any indirect, incidental, special, consequential, or punitive damages arising out of or related to your use of the Service, including but not limited to loss of profits, data, or goodwill.
        </p>
        <p>Our total liability to you for any claim shall not exceed the amount paid by you (if any) to use the Service in the three months preceding the claim.</p>

        <h2 id="termination">10. Termination</h2>
        <p>
            We reserve the right to suspend or terminate your access to the Service at any time, with or without notice, for conduct that violates these Terms or is harmful to other users, us, or third parties. You may terminate your account at any time by contacting us.
        </p>

        <h2 id="governing-law">11. Governing Law</h2>
        <p>
            These Terms are governed by and construed in accordance with the laws of Malaysia. Any disputes arising from these Terms shall be subject to the exclusive jurisdiction of the courts of Malaysia.
        </p>

        <h2 id="changes">12. Changes to Terms</h2>
        <p>
            We may update these Terms from time to time. We will notify you of material changes by posting the updated Terms on this page with a revised date. Continued use of the Service after changes constitutes your acceptance of the updated Terms.
        </p>

        <h2 id="contact">13. Contact Us</h2>
        <p>If you have any questions about these Terms, please contact us:</p>
        <ul>
            <li><strong>Email:</strong> <a href="mailto:salessistemkami@gmail.com">salessistemkami@gmail.com</a></li>
            <li><strong>WhatsApp:</strong> <a href="https://wa.me/601123053082" target="_blank">+60 11-2405 3082</a></li>
            <li><strong>Address:</strong> Ipoh, Perak, Malaysia</li>
        </ul>

    </div>
</section>

@endsection
