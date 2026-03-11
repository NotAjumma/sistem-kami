@extends('home.homeLayout')

@section('title', $seo['title'] ?? 'FAQ | Sistem Kami')

@push('json_ld')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "FAQPage",
    "mainEntity": [
        {
            "@type": "Question",
            "name": "What is Sistem Kami?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Sistem Kami is a cloud-based booking and business management platform designed for studios, service providers, and event organizers in Malaysia. It helps you manage your packages, time slots, bookings, payments, workers, and customer communications — all in one place."
            }
        },
        {
            "@type": "Question",
            "name": "Who is Sistem Kami for?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Sistem Kami is built for any business that takes bookings — fishing clubs, photography studios, sports facilities, event planners, and more. If you manage time slots, packages, and customers, Sistem Kami is designed for you."
            }
        },
        {
            "@type": "Question",
            "name": "How do customers make a booking?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Customers can browse your packages on your public profile page and select a time slot to book. They fill in their details and pay online. Alternatively, you can also accept manual bookings — customers send a WhatsApp message and you create the booking from your dashboard."
            }
        },
        {
            "@type": "Question",
            "name": "Can I manage multiple packages and time slots?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Yes. You can create unlimited packages, each with their own time slots, pricing, capacity, and availability. Special dates, off-days, and slot-level customization are all supported."
            }
        },
        {
            "@type": "Question",
            "name": "What payment methods are supported?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Sistem Kami supports online payments via Toyyibpay (FPX, debit/credit card) and Stripe. Manual payment collection (cash, bank transfer) with QR code display is also supported."
            }
        },
        {
            "@type": "Question",
            "name": "Can I accept deposits instead of full payment?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Yes. Deposit-based booking is supported. You can configure the deposit amount per package and collect the remaining balance manually before the booking date."
            }
        },
        {
            "@type": "Question",
            "name": "Does the system send booking reminders?",
            "acceptedAnswer": {
                "@type": "Answer",
                "text": "Yes. Sistem Kami sends automated WhatsApp reminders to customers before their booking date. You can configure quiet hours so reminders are only sent at appropriate times."
            }
        }
    ]
}
</script>
@endpush

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
        <h1 class="fw-bold mb-3" style="font-size: clamp(2rem, 4vw, 3rem); color: #fff;">{{ __('faq.hero_heading') }}</h1>
        <p class="mb-0" style="color: rgba(255,255,255,0.75); max-width: 560px; margin: 0 auto; font-size: 1.05rem;">
            {{ __('faq.hero_sub') }} <a href="https://wa.me/601123053082" target="_blank" style="color:#93c5fd;">{{ __('faq.hero_chat') }}</a>
        </p>
    </div>
</section>

{{-- FAQ Content --}}
<section class="faq-section bg-white">
    <div class="container" style="max-width: 780px;">

        {{-- General --}}
        <div class="mb-5 faq-animate">
            <div class="faq-category-title">{{ __('faq.cat_general') }}</div>
            <div class="sk-accordion">

                <div class="sk-accordion-item">
                    <button class="sk-accordion-btn open" type="button">
                        {{ app()->getLocale() === 'ms' ? 'Apakah Sistem Kami?' : (app()->getLocale() === 'zh' ? '什么是 Sistem Kami？' : 'What is Sistem Kami?') }}
                        <svg class="sk-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="sk-accordion-body open">
                        @if(app()->getLocale() === 'ms')
                            Sistem Kami ialah platform pengurusan tempahan dan perniagaan berasaskan awan yang direka untuk studio, penyedia perkhidmatan, dan penganjur acara di Malaysia. Ia membantu anda mengurus pakej, slot masa, tempahan, pembayaran, pekerja, dan komunikasi pelanggan — semua dalam satu tempat.
                        @elseif(app()->getLocale() === 'zh')
                            Sistem Kami 是一个基于云端的预订与业务管理平台，专为马来西亚的工作室、服务提供商和活动主办方设计。帮助您管理套餐、时间段、预订、付款、员工及客户沟通——一切尽在一处。
                        @else
                            Sistem Kami is a cloud-based booking and business management platform designed for studios, service providers, and event organizers in Malaysia. It helps you manage your packages, time slots, bookings, payments, workers, and customer communications — all in one place.
                        @endif
                    </div>
                </div>

                <div class="sk-accordion-item">
                    <button class="sk-accordion-btn" type="button">
                        {{ app()->getLocale() === 'ms' ? 'Untuk siapa Sistem Kami?' : (app()->getLocale() === 'zh' ? 'Sistem Kami 适合谁？' : 'Who is Sistem Kami for?') }}
                        <svg class="sk-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="sk-accordion-body">
                        @if(app()->getLocale() === 'ms')
                            Sistem Kami dibina untuk mana-mana perniagaan yang menerima tempahan — kelab memancing, studio fotografi, kemudahan sukan, perancang acara, dan banyak lagi. Jika anda mengurus slot masa, pakej, dan pelanggan, Sistem Kami direka untuk anda.
                        @elseif(app()->getLocale() === 'zh')
                            Sistem Kami 适合所有接受预订的业务——钓鱼俱乐部、摄影工作室、体育设施、活动策划公司等。只要您管理时间段、套餐和客户，Sistem Kami 就是为您而设计的。
                        @else
                            Sistem Kami is built for any business that takes bookings — fishing clubs, photography studios, sports facilities, event planners, and more. If you manage time slots, packages, and customers, Sistem Kami is designed for you.
                        @endif
                    </div>
                </div>

                <div class="sk-accordion-item">
                    <button class="sk-accordion-btn" type="button">
                        {{ app()->getLocale() === 'ms' ? 'Adakah Sistem Kami tersedia dalam Bahasa Malaysia?' : (app()->getLocale() === 'zh' ? 'Sistem Kami 支持哪些语言？' : 'Is Sistem Kami available in Bahasa Malaysia?') }}
                        <svg class="sk-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="sk-accordion-body">
                        @if(app()->getLocale() === 'ms')
                            Laman web awam kini telah disokong dalam Bahasa Malaysia. Papan pemuka penganjur sedang dalam proses diterjemahkan. Komunikasi melalui WhatsApp boleh dikustomkan dalam mana-mana bahasa oleh penganjur.
                        @elseif(app()->getLocale() === 'zh')
                            公共网站现已支持英语、马来语和中文（普通话）。主办方控制台正在逐步翻译中。客户通过 WhatsApp 的沟通信息可由主办方以任意语言自定义。
                        @else
                            The public website now supports Bahasa Malaysia and Mandarin Chinese. The organizer dashboard is being progressively translated. Customer communication via WhatsApp can be customized in any language by the organizer.
                        @endif
                    </div>
                </div>

            </div>
        </div>

        {{-- Booking & Packages --}}
        <div class="mb-5 faq-animate">
            <div class="faq-category-title">{{ __('faq.cat_booking') }}</div>
            <div class="sk-accordion">

                <div class="sk-accordion-item">
                    <button class="sk-accordion-btn" type="button">
                        {{ app()->getLocale() === 'ms' ? 'Bagaimana pelanggan membuat tempahan?' : (app()->getLocale() === 'zh' ? '客户如何进行预订？' : 'How do customers make a booking?') }}
                        <svg class="sk-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="sk-accordion-body">
                        @if(app()->getLocale() === 'ms')
                            Pelanggan boleh melayari pakej anda di halaman profil awam dan memilih slot masa untuk ditempah. Mereka mengisi butiran dan membayar secara dalam talian. Sebagai alternatif, anda juga boleh menerima tempahan manual — pelanggan menghantar mesej WhatsApp dan anda mencipta tempahan dari papan pemuka anda.
                        @elseif(app()->getLocale() === 'zh')
                            客户可在您的公开主页浏览套餐，选择时间段进行预订，填写资料并在线付款。也可接受手动预订——客户发送 WhatsApp 消息，您从控制台创建预订。
                        @else
                            Customers can browse your packages on your public profile page and select a time slot to book. They fill in their details and pay online. Alternatively, you can also accept manual bookings — customers send a WhatsApp message and you create the booking from your dashboard.
                        @endif
                    </div>
                </div>

                <div class="sk-accordion-item">
                    <button class="sk-accordion-btn" type="button">
                        {{ app()->getLocale() === 'ms' ? 'Bolehkah saya mengurus pelbagai pakej dan slot masa?' : (app()->getLocale() === 'zh' ? '我可以管理多个套餐和时间段吗？' : 'Can I manage multiple packages and time slots?') }}
                        <svg class="sk-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="sk-accordion-body">
                        @if(app()->getLocale() === 'ms')
                            Ya. Anda boleh mencipta pakej tanpa had, masing-masing dengan slot masa, harga, kapasiti, dan ketersediaan tersendiri. Tarikh khas, hari rehat, dan penyesuaian peringkat slot semuanya disokong.
                        @elseif(app()->getLocale() === 'zh')
                            可以。您可以创建无限数量的套餐，每个套餐有独立的时间段、定价、容量和可用性设置，支持特殊日期、休息日及时段级别自定义。
                        @else
                            Yes. You can create unlimited packages, each with their own time slots, pricing, capacity, and availability. Special dates, off-days, and slot-level customization are all supported.
                        @endif
                    </div>
                </div>

                <div class="sk-accordion-item">
                    <button class="sk-accordion-btn" type="button">
                        {{ app()->getLocale() === 'ms' ? 'Bolehkah saya menyekat tarikh atau menetapkan hari rehat?' : (app()->getLocale() === 'zh' ? '我可以屏蔽日期或设置休息日吗？' : 'Can I block out dates or set off-days?') }}
                        <svg class="sk-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="sk-accordion-body">
                        @if(app()->getLocale() === 'ms')
                            Ya. Anda boleh menetapkan hari rehat berulang (cth. setiap Isnin), tarikh sekatan tertentu, dan waktu buka khusus setiap slot. Ini memberi anda kawalan penuh ke atas ketersediaan anda.
                        @elseif(app()->getLocale() === 'zh')
                            可以。您可以设置周期性休息日（如每周一）、特定屏蔽日期，以及每个时段的自定义营业时间，让您完全掌控自己的档期。
                        @else
                            Yes. You can set recurring off-days (e.g. every Monday), specific blocked dates, and custom opening hours per slot. This gives you full control over your availability.
                        @endif
                    </div>
                </div>

                <div class="sk-accordion-item">
                    <button class="sk-accordion-btn" type="button">
                        {{ app()->getLocale() === 'ms' ? 'Bolehkah saya membuat tempahan bagi pihak pelanggan?' : (app()->getLocale() === 'zh' ? '我可以代客户创建预订吗？' : 'Can I create bookings on behalf of customers?') }}
                        <svg class="sk-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="sk-accordion-body">
                        @if(app()->getLocale() === 'ms')
                            Ya. Penganjur boleh mencipta tempahan manual terus dari papan pemuka. Ini berguna untuk pelanggan yang datang sendiri atau tempahan yang diterima melalui WhatsApp.
                        @elseif(app()->getLocale() === 'zh')
                            可以。主办方可直接从控制台创建手动预订，适用于现场到访的客户或通过 WhatsApp 接收的预订。
                        @else
                            Yes. Organizers can create manual bookings directly from the dashboard. This is useful for walk-in customers or bookings received through WhatsApp.
                        @endif
                    </div>
                </div>

            </div>
        </div>

        {{-- Payments --}}
        <div class="mb-5 faq-animate">
            <div class="faq-category-title">{{ __('faq.cat_payments') }}</div>
            <div class="sk-accordion">

                <div class="sk-accordion-item">
                    <button class="sk-accordion-btn" type="button">
                        {{ app()->getLocale() === 'ms' ? 'Apakah kaedah pembayaran yang disokong?' : (app()->getLocale() === 'zh' ? '支持哪些付款方式？' : 'What payment methods are supported?') }}
                        <svg class="sk-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="sk-accordion-body">
                        @if(app()->getLocale() === 'ms')
                            Sistem Kami menyokong pembayaran dalam talian melalui <strong>Toyyibpay</strong> (FPX, kad debit/kredit) dan <strong>Stripe</strong>. Pengumpulan pembayaran manual (tunai, pindahan bank) dengan paparan kod QR juga disokong.
                        @elseif(app()->getLocale() === 'zh')
                            Sistem Kami 支持通过 <strong>Toyyibpay</strong>（FPX、借/贷记卡）和 <strong>Stripe</strong> 进行在线支付，也支持手动收款（现金、银行转账）及 QR 码展示。
                        @else
                            Sistem Kami supports online payments via <strong>Toyyibpay</strong> (FPX, debit/credit card) and <strong>Stripe</strong>. Manual payment collection (cash, bank transfer) with QR code display is also supported.
                        @endif
                    </div>
                </div>

                <div class="sk-accordion-item">
                    <button class="sk-accordion-btn" type="button">
                        {{ app()->getLocale() === 'ms' ? 'Adakah terdapat yuran transaksi?' : (app()->getLocale() === 'zh' ? '是否有交易手续费？' : 'Is there a transaction fee?') }}
                        <svg class="sk-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="sk-accordion-body">
                        @if(app()->getLocale() === 'ms')
                            Sistem Kami tidak mengenakan yuran transaksi tambahan melebihi kadar standard get pembayaran. Toyyibpay dan Stripe mempunyai yuran pemprosesan tersendiri — sila rujuk laman web masing-masing untuk kadar semasa.
                        @elseif(app()->getLocale() === 'zh')
                            Sistem Kami 不收取超出支付网关标准费率的额外交易手续费。Toyyibpay 和 Stripe 有各自的处理费用——请参阅其官网了解最新费率。
                        @else
                            Sistem Kami does not charge additional transaction fees beyond the payment gateway's standard rates. Toyyibpay and Stripe have their own processing fees — please refer to their respective websites for current rates.
                        @endif
                    </div>
                </div>

                <div class="sk-accordion-item">
                    <button class="sk-accordion-btn" type="button">
                        {{ app()->getLocale() === 'ms' ? 'Bolehkah saya menerima deposit dan bukan bayaran penuh?' : (app()->getLocale() === 'zh' ? '我可以接受押金而非全额付款吗？' : 'Can I accept deposits instead of full payment?') }}
                        <svg class="sk-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="sk-accordion-body">
                        @if(app()->getLocale() === 'ms')
                            Ya. Tempahan berasaskan deposit disokong. Anda boleh mengkonfigurasi amaun deposit setiap pakej dan mengumpul baki yang tinggal secara manual sebelum tarikh tempahan.
                        @elseif(app()->getLocale() === 'zh')
                            支持押金预订。您可以为每个套餐配置押金金额，并在预订日期前手动收取余款。
                        @else
                            Yes. Deposit-based booking is supported. You can configure the deposit amount per package and collect the remaining balance manually before the booking date.
                        @endif
                    </div>
                </div>

            </div>
        </div>

        {{-- Workers & Team --}}
        <div class="mb-5 faq-animate">
            <div class="faq-category-title">{{ __('faq.cat_workers') }}</div>
            <div class="sk-accordion">

                <div class="sk-accordion-item">
                    <button class="sk-accordion-btn" type="button">
                        {{ app()->getLocale() === 'ms' ? 'Bolehkah saya menambah pekerja atau kakitangan ke akaun saya?' : (app()->getLocale() === 'zh' ? '我可以为账户添加员工或职员吗？' : 'Can I add workers or staff to my account?') }}
                        <svg class="sk-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="sk-accordion-body">
                        @if(app()->getLocale() === 'ms')
                            Ya. Anda boleh menambah pekerja dengan akses log masuk tersendiri. Pekerja boleh melihat tempahan, melakukan daftar masuk, dan mengurus tugas yang ditugaskan — tanpa akses kepada tetapan kewangan atau pentadbiran anda.
                        @elseif(app()->getLocale() === 'zh')
                            可以。您可以为员工添加独立登录账户。员工可查看预订、办理签到并管理分配的任务——但无法访问您的财务或管理设置。
                        @else
                            Yes. You can add workers with their own login access. Workers can view bookings, perform check-ins, and manage their assigned tasks — without access to your financial or admin settings.
                        @endif
                    </div>
                </div>

                <div class="sk-accordion-item">
                    <button class="sk-accordion-btn" type="button">
                        {{ app()->getLocale() === 'ms' ? 'Adakah sistem menyokong komisyen pekerja?' : (app()->getLocale() === 'zh' ? '系统是否支持员工佣金？' : 'Does the system support worker commissions?') }}
                        <svg class="sk-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="sk-accordion-body">
                        @if(app()->getLocale() === 'ms')
                            Ya. Anda boleh mengkonfigurasi kadar komisyen setiap pekerja dan sistem akan menjejak pendapatan mereka berdasarkan tempahan yang diselesaikan.
                        @elseif(app()->getLocale() === 'zh')
                            可以。您可以为每位员工配置佣金比率，系统将根据已完成的预订自动追踪其收入。
                        @else
                            Yes. You can configure commission rates per worker and the system will track their earnings based on completed bookings.
                        @endif
                    </div>
                </div>

            </div>
        </div>

        {{-- Notifications --}}
        <div class="mb-5 faq-animate">
            <div class="faq-category-title">{{ __('faq.cat_notifications') }}</div>
            <div class="sk-accordion">

                <div class="sk-accordion-item">
                    <button class="sk-accordion-btn" type="button">
                        {{ app()->getLocale() === 'ms' ? 'Adakah sistem menghantar peringatan tempahan?' : (app()->getLocale() === 'zh' ? '系统会发送预订提醒吗？' : 'Does the system send booking reminders?') }}
                        <svg class="sk-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="sk-accordion-body">
                        @if(app()->getLocale() === 'ms')
                            Ya. Sistem Kami menghantar peringatan WhatsApp automatik kepada pelanggan sebelum tarikh tempahan mereka (melalui Fonnte). Anda boleh mengkonfigurasi waktu senyap supaya peringatan hanya dihantar pada waktu yang sesuai.
                        @elseif(app()->getLocale() === 'zh')
                            系统会在预订日期前通过 WhatsApp（Fonnte）自动发送提醒给客户。您可以配置免打扰时段，确保提醒仅在合适的时间发送。
                        @else
                            Yes. Sistem Kami sends automated WhatsApp reminders to customers before their booking date (via Fonnte). You can configure quiet hours so reminders are only sent at appropriate times.
                        @endif
                    </div>
                </div>

                <div class="sk-accordion-item">
                    <button class="sk-accordion-btn" type="button">
                        {{ app()->getLocale() === 'ms' ? 'Apakah pemberitahuan yang diterima penganjur?' : (app()->getLocale() === 'zh' ? '主办方会收到哪些通知？' : 'What notifications does the organizer receive?') }}
                        <svg class="sk-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div class="sk-accordion-body">
                        @if(app()->getLocale() === 'ms')
                            Penganjur menerima pemberitahuan untuk tempahan baru, pengesahan pembayaran, dan sesi yang akan datang. Pemberitahuan kelihatan dalam papan pemuka dan secara pilihan melalui WhatsApp.
                        @elseif(app()->getLocale() === 'zh')
                            主办方会收到新预订、付款确认和即将到来的场次通知。通知显示在控制台中，也可选择通过 WhatsApp 接收。
                        @else
                            Organizers receive notifications for new bookings, payment confirmations, and upcoming sessions. Notifications are visible in the dashboard and optionally via WhatsApp.
                        @endif
                    </div>
                </div>

            </div>
        </div>

        {{-- CTA --}}
        <div class="text-center py-5 faq-animate">
            <p class="text-muted mb-3">{{ __('faq.still_questions') }}</p>
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
