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
        <h1 class="fw-bold mb-3" style="font-size: clamp(1.8rem, 4vw, 2.8rem); color: #fff !important;">{{ __('privacy.hero_heading') }}</h1>
        <p class="mb-0" style="color: rgba(255,255,255,0.7);">{{ __('privacy.last_updated') }} {{ date('d F Y') }}</p>
    </div>
</section>

<section class="policy-body bg-white">
    <div class="container" style="max-width: 780px;">

@if(app()->getLocale() === 'zh')

        <div class="policy-toc">
            <div class="fw-semibold mb-2" style="font-size:0.88rem; color:#001f4d;">{{ __('privacy.contents') }}</div>
            <ol class="mb-0 ps-3" style="line-height:2">
                <li><a href="#info-we-collect">我们收集的信息</a></li>
                <li><a href="#how-we-use">我们如何使用您的信息</a></li>
                <li><a href="#cookies">Cookie 与数据分析</a></li>
                <li><a href="#third-parties">第三方服务</a></li>
                <li><a href="#data-retention">数据保留期限</a></li>
                <li><a href="#your-rights">您的权利</a></li>
                <li><a href="#security">安全</a></li>
                <li><a href="#changes">政策变更</a></li>
                <li><a href="#contact">联系我们</a></li>
            </ol>
        </div>

        <p>
            Sistem Kami（"我们"）运营网站 <strong>sistemkami.com</strong> 并提供预订与业务管理平台。本隐私政策说明我们在您使用我们的服务时如何收集、使用和保护您的个人信息。
        </p>
        <p>使用 Sistem Kami，即表示您同意按本政策所述收集和使用信息。</p>

        <h2 id="info-we-collect">1. 我们收集的信息</h2>
        <p>我们收集以下类型的信息：</p>
        <ul>
            <li><strong>个人信息</strong> — 创建账户或预订时提供的姓名、电话号码、电子邮件地址及其他详情。</li>
            <li><strong>预订数据</strong> — 套餐选择、时间段、付款状态及预订历史。</li>
            <li><strong>业务信息</strong> — 主办方主页、套餐详情、定价及营业时间。</li>
            <li><strong>使用数据</strong> — 通过 Cookie 和分析工具收集的访问页面、停留时长、设备类型及浏览器信息。</li>
            <li><strong>付款信息</strong> — 付款由第三方网关（Toyyibpay、Stripe）处理，我们不存储完整的卡片详情。</li>
        </ul>

        <h2 id="how-we-use">2. 我们如何使用您的信息</h2>
        <p>我们使用所收集的信息来：</p>
        <ul>
            <li>处理和管理您的预订</li>
            <li>通过 WhatsApp 或电子邮件发送预订确认和提醒</li>
            <li>使主办方能够管理业务并与客户沟通</li>
            <li>改善平台性能和用户体验</li>
            <li>履行法律义务</li>
            <li>检测和防止欺诈或滥用</li>
        </ul>
        <p>我们不会将您的个人信息出售给第三方。</p>

        <h2 id="cookies">3. Cookie 与数据分析</h2>
        <p>
            我们使用 <strong>Google Analytics</strong> 了解访客与平台的互动情况。Google Analytics 使用 Cookie 收集匿名数据，如页面浏览量、会话时长和流量来源。
        </p>
        <p>
            我们还使用平台运行必需的功能性 Cookie（如会话管理、CSRF 保护），禁用这些 Cookie 会影响核心功能。
        </p>
        <p>
            您可通过我们的 Cookie 同意横幅控制分析性 Cookie。拒绝分析性 Cookie 将阻止 Google Analytics 追踪您的访问。详情请参阅 <a href="https://policies.google.com/privacy" target="_blank">Google 隐私政策</a>。
        </p>

        <h2 id="third-parties">4. 第三方服务</h2>
        <p>我们使用以下第三方服务，各自拥有独立的隐私政策：</p>
        <ul>
            <li><strong>Google Analytics</strong> — 网站数据分析</li>
            <li><strong>Toyyibpay</strong> — 在线支付处理</li>
            <li><strong>Stripe</strong> — 在线支付处理</li>
            <li><strong>Fonnte</strong> — WhatsApp 通知发送</li>
        </ul>
        <p>我们仅与这些服务共享提供其功能所需的最少信息。</p>

        <h2 id="data-retention">5. 数据保留期限</h2>
        <p>
            我们在您的账户有效期间或提供服务所需的时间内保留您的个人信息。主办方账户及其预订数据在账户关闭后至少保留 12 个月，用于法律和审计目的。
        </p>
        <p>您可随时联系我们要求删除您的数据。</p>

        <h2 id="your-rights">6. 您的权利</h2>
        <p>您有权：</p>
        <ul>
            <li>访问我们持有的关于您的个人数据</li>
            <li>要求更正不准确的信息</li>
            <li>要求删除您的数据（须符合法律要求）</li>
            <li>随时撤回对分析性 Cookie 的同意</li>
            <li>向相关数据保护机构提出投诉</li>
        </ul>
        <p>如需行使上述任何权利，请使用以下联系方式联系我们。</p>

        <h2 id="security">7. 安全</h2>
        <p>
            我们采取适当的技术和组织措施，保护您的个人数据免遭未经授权的访问、更改、披露或销毁。所有数据均通过 HTTPS 传输。付款处理由符合 PCI 标准的第三方网关负责。
        </p>
        <p>互联网上的任何传输方式都不能保证 100% 的安全。虽然我们竭力保护您的信息，但无法保证绝对安全。</p>

        <h2 id="changes">8. 政策变更</h2>
        <p>
            我们可能会不时更新本隐私政策。如有重大变更，我们将在本页发布更新后的政策及修订日期予以通知。在变更后继续使用平台，即表示您接受更新后的政策。
        </p>

        <h2 id="contact">9. 联系我们</h2>
        <p>如您对本隐私政策有任何疑问，请联系我们：</p>
        <ul>
            <li><strong>电子邮件：</strong> <a href="mailto:salessistemkami@gmail.com">salessistemkami@gmail.com</a></li>
            <li><strong>WhatsApp：</strong> <a href="https://wa.me/601123053082" target="_blank">+60 11-2405 3082</a></li>
            <li><strong>地址：</strong> Ipoh, Perak, Malaysia</li>
        </ul>

@elseif(app()->getLocale() === 'ms')

        <div class="policy-toc">
            <div class="fw-semibold mb-2" style="font-size:0.88rem; color:#001f4d;">{{ __('privacy.contents') }}</div>
            <ol class="mb-0 ps-3" style="line-height:2">
                <li><a href="#info-we-collect">Maklumat Yang Kami Kumpulkan</a></li>
                <li><a href="#how-we-use">Cara Kami Menggunakan Maklumat Anda</a></li>
                <li><a href="#cookies">Kuki & Analitik</a></li>
                <li><a href="#third-parties">Perkhidmatan Pihak Ketiga</a></li>
                <li><a href="#data-retention">Tempoh Penyimpanan Data</a></li>
                <li><a href="#your-rights">Hak Anda</a></li>
                <li><a href="#security">Keselamatan</a></li>
                <li><a href="#changes">Perubahan Dasar Ini</a></li>
                <li><a href="#contact">Hubungi Kami</a></li>
            </ol>
        </div>

        <p>
            Sistem Kami ("kami") mengendalikan laman web <strong>sistemkami.com</strong> dan menyediakan platform pengurusan tempahan dan perniagaan. Dasar Privasi ini menerangkan cara kami mengumpul, menggunakan, dan melindungi maklumat peribadi anda apabila anda menggunakan perkhidmatan kami.
        </p>
        <p>Dengan menggunakan Sistem Kami, anda bersetuju dengan pengumpulan dan penggunaan maklumat seperti yang diterangkan dalam dasar ini.</p>

        <h2 id="info-we-collect">1. Maklumat Yang Kami Kumpulkan</h2>
        <p>Kami mengumpulkan jenis maklumat berikut:</p>
        <ul>
            <li><strong>Maklumat peribadi</strong> — nama, nombor telefon, alamat e-mel, dan butiran lain yang diberikan semasa membuat akaun atau tempahan.</li>
            <li><strong>Data tempahan</strong> — pilihan pakej, slot masa, status pembayaran, dan sejarah tempahan.</li>
            <li><strong>Maklumat perniagaan</strong> — profil penganjur, butiran pakej, harga, dan waktu operasi.</li>
            <li><strong>Data penggunaan</strong> — halaman yang dilawati, masa yang dihabiskan, jenis peranti, dan maklumat pelayar yang dikumpul melalui kuki dan analitik.</li>
            <li><strong>Maklumat pembayaran</strong> — pembayaran diproses oleh get pembayaran pihak ketiga (Toyyibpay, Stripe). Kami tidak menyimpan butiran kad penuh.</li>
        </ul>

        <h2 id="how-we-use">2. Cara Kami Menggunakan Maklumat Anda</h2>
        <p>Kami menggunakan maklumat yang dikumpul untuk:</p>
        <ul>
            <li>Memproses dan mengurus tempahan anda</li>
            <li>Menghantar pengesahan dan peringatan tempahan melalui WhatsApp atau e-mel</li>
            <li>Membolehkan penganjur mengurus perniagaan dan berkomunikasi dengan pelanggan</li>
            <li>Meningkatkan prestasi platform dan pengalaman pengguna</li>
            <li>Mematuhi kewajipan undang-undang</li>
            <li>Mengesan dan mencegah penipuan atau penyalahgunaan</li>
        </ul>
        <p>Kami tidak menjual maklumat peribadi anda kepada pihak ketiga.</p>

        <h2 id="cookies">3. Kuki & Analitik</h2>
        <p>
            Kami menggunakan <strong>Google Analytics</strong> untuk memahami cara pengunjung berinteraksi dengan platform kami. Google Analytics menggunakan kuki untuk mengumpul data tanpa nama seperti paparan halaman, tempoh sesi, dan sumber trafik.
        </p>
        <p>
            Kami juga menggunakan kuki fungsional yang penting untuk operasi platform (contohnya pengurusan sesi, perlindungan CSRF). Ini tidak boleh dilumpuhkan tanpa menjejaskan fungsi utama.
        </p>
        <p>
            Anda boleh mengawal kuki analitik melalui banner persetujuan kuki kami. Menolak kuki analitik akan menghalang Google Analytics daripada menjejak lawatan anda. Untuk maklumat lanjut, lihat <a href="https://policies.google.com/privacy" target="_blank">Dasar Privasi Google</a>.
        </p>

        <h2 id="third-parties">4. Perkhidmatan Pihak Ketiga</h2>
        <p>Kami menggunakan perkhidmatan pihak ketiga berikut, masing-masing dengan dasar privasi mereka sendiri:</p>
        <ul>
            <li><strong>Google Analytics</strong> — analitik laman web</li>
            <li><strong>Toyyibpay</strong> — pemprosesan pembayaran dalam talian</li>
            <li><strong>Stripe</strong> — pemprosesan pembayaran dalam talian</li>
            <li><strong>Fonnte</strong> — penghantaran pemberitahuan WhatsApp</li>
        </ul>
        <p>Kami hanya berkongsi maklumat minimum yang diperlukan dengan perkhidmatan ini untuk menyediakan fungsinya.</p>

        <h2 id="data-retention">5. Tempoh Penyimpanan Data</h2>
        <p>
            Kami menyimpan maklumat peribadi anda selagi akaun anda aktif atau selagi diperlukan untuk menyediakan perkhidmatan. Akaun penganjur dan data tempahan mereka disimpan sekurang-kurangnya 12 bulan selepas penutupan akaun untuk tujuan undang-undang dan audit.
        </p>
        <p>Anda boleh meminta pemadaman data anda pada bila-bila masa dengan menghubungi kami.</p>

        <h2 id="your-rights">6. Hak Anda</h2>
        <p>Anda berhak untuk:</p>
        <ul>
            <li>Mengakses data peribadi yang kami simpan tentang anda</li>
            <li>Meminta pembetulan maklumat yang tidak tepat</li>
            <li>Meminta pemadaman data anda (tertakluk kepada keperluan undang-undang)</li>
            <li>Menarik balik persetujuan untuk kuki analitik pada bila-bila masa</li>
            <li>Membuat aduan kepada pihak berkuasa perlindungan data yang berkaitan</li>
        </ul>
        <p>Untuk menggunakan mana-mana hak ini, sila hubungi kami menggunakan butiran di bawah.</p>

        <h2 id="security">7. Keselamatan</h2>
        <p>
            Kami melaksanakan langkah teknikal dan organisasi yang sesuai untuk melindungi data peribadi anda daripada akses, pengubahan, pendedahan, atau pemusnahan yang tidak dibenarkan. Semua data dihantar melalui HTTPS. Pemprosesan pembayaran dikendalikan oleh get pembayaran pihak ketiga yang mematuhi PCI.
        </p>
        <p>Tiada kaedah penghantaran melalui internet yang 100% selamat. Walaupun kami berusaha melindungi maklumat anda, kami tidak dapat menjamin keselamatan mutlak.</p>

        <h2 id="changes">8. Perubahan Dasar Ini</h2>
        <p>
            Kami mungkin mengemas kini Dasar Privasi ini dari semasa ke semasa. Kami akan memaklumkan anda tentang perubahan ketara dengan menyiarkan dasar yang dikemas kini di halaman ini dengan tarikh semakan. Penggunaan berterusan platform selepas perubahan merupakan penerimaan dasar yang dikemas kini.
        </p>

        <h2 id="contact">9. Hubungi Kami</h2>
        <p>Jika anda mempunyai sebarang soalan tentang Dasar Privasi ini, sila hubungi kami:</p>
        <ul>
            <li><strong>E-mel:</strong> <a href="mailto:salessistemkami@gmail.com">salessistemkami@gmail.com</a></li>
            <li><strong>WhatsApp:</strong> <a href="https://wa.me/601123053082" target="_blank">+60 11-2405 3082</a></li>
            <li><strong>Alamat:</strong> Ipoh, Perak, Malaysia</li>
        </ul>

@else

        <div class="policy-toc">
            <div class="fw-semibold mb-2" style="font-size:0.88rem; color:#001f4d;">{{ __('privacy.contents') }}</div>
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

@endif

    </div>
</section>

@endsection
