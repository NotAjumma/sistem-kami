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

@if(app()->getLocale() === 'zh')

        <div class="policy-toc">
            <div class="fw-semibold mb-2" style="font-size:0.88rem; color:#001f4d;">{{ __('terms.contents') }}</div>
            <ol class="mb-0 ps-3" style="line-height:2">
                <li><a href="#acceptance">接受条款</a></li>
                <li><a href="#services">我们的服务</a></li>
                <li><a href="#organizer-accounts">主办方账户</a></li>
                <li><a href="#customer-bookings">客户预订</a></li>
                <li><a href="#payments">付款与退款</a></li>
                <li><a href="#acceptable-use">可接受的使用</a></li>
                <li><a href="#intellectual-property">知识产权</a></li>
                <li><a href="#disclaimers">免责声明</a></li>
                <li><a href="#limitation">责任限制</a></li>
                <li><a href="#termination">终止</a></li>
                <li><a href="#governing-law">适用法律</a></li>
                <li><a href="#changes">条款变更</a></li>
                <li><a href="#contact">联系我们</a></li>
            </ol>
        </div>

        <p>在使用 Sistem Kami 平台（"服务"）之前，请仔细阅读本条款与条件（"条款"）。</p>
        <p>访问或使用 Sistem Kami，即表示您同意受本条款约束。如不同意，请勿使用本服务。</p>

        <h2 id="acceptance">1. 接受条款</h2>
        <p>通过注册账户、进行预订或以其他方式使用 Sistem Kami，您确认您已年满 18 岁，并具有签订本条款的法律行为能力。如您代表某一企业使用本服务，您声明您有权代表该企业接受本条款。</p>

        <h2 id="services">2. 我们的服务</h2>
        <p>Sistem Kami 提供软件平台，使企业（"主办方"）能够管理套餐、时间段、预订、付款和客户沟通。我们仅作为技术平台，不参与主办方与其客户之间的任何交易。</p>

        <h2 id="organizer-accounts">3. 主办方账户</h2>
        <ul>
            <li>您有责任保持登录凭证的机密性。</li>
            <li>注册账户时，您必须提供准确且最新的信息。</li>
            <li>您对账户下发生的所有活动负全责。</li>
            <li>如发现任何未经授权的账户访问，您必须立即通知我们。</li>
            <li>未经我们事先书面同意，主办方账户不可转让。</li>
        </ul>

        <h2 id="customer-bookings">4. 客户预订</h2>
        <p>通过 Sistem Kami 进行的预订是客户与主办方之间的协议。Sistem Kami 促进预订流程，但不对主办方提供的服务的质量、安全性或交付负责。</p>
        <ul>
            <li>主办方有责任向客户清晰传达其预订条款、取消政策和服务详情。</li>
            <li>客户在确认前必须仔细核查预订详情。</li>
            <li>Sistem Kami 不保证特定时间段或服务的可用性。</li>
        </ul>

        <h2 id="payments">5. 付款与退款</h2>
        <ul>
            <li>付款由第三方网关（Toyyibpay、Stripe）处理。付款即表示您同意其各自的条款。</li>
            <li>Sistem Kami 不控制主办方的退款政策。退款请求须直接向主办方提出。</li>
            <li>如发生付款纠纷，您可直接联系相关支付网关。</li>
            <li>Sistem Kami 保留对涉及欺诈性付款活动的账户进行暂停或终止的权利。</li>
        </ul>

        <h2 id="acceptable-use">6. 可接受的使用</h2>
        <p>您同意不得：</p>
        <ul>
            <li>将平台用于任何非法或未经授权的目的</li>
            <li>上传虚假、误导性或诽谤性内容</li>
            <li>试图未经授权访问平台的任何部分</li>
            <li>干扰或破坏平台的服务器或网络</li>
            <li>未经许可使用自动工具抓取或收集数据</li>
            <li>冒充任何个人或实体</li>
            <li>违反任何适用的法律法规</li>
        </ul>

        <h2 id="intellectual-property">7. 知识产权</h2>
        <p>Sistem Kami 上的所有内容、设计、代码、标志和商标均归我们所有或已获授权。未经我们书面许可，您不得复制、分发或创建衍生作品。</p>
        <p>您保留对上传内容的所有权。上传内容即表示您授予我们非独家、免版权费的许可，用于展示和使用该内容以提供服务。</p>

        <h2 id="disclaimers">8. 免责声明</h2>
        <p>本服务按"现状"和"可用"方式提供，不提供任何明示或暗示的保证。我们不保证服务不中断、无错误或无病毒，也不对基于平台信息所作决定承担责任。</p>

        <h2 id="limitation">9. 责任限制</h2>
        <p>在法律允许的最大范围内，Sistem Kami 对因您使用本服务引起的任何间接、附带、特殊、后果性或惩罚性损害不承担责任，包括但不限于利润、数据或商誉的损失。</p>
        <p>我们对您任何索赔的总责任不超过您在索赔前三个月内为使用本服务所支付的金额（如有）。</p>

        <h2 id="termination">10. 终止</h2>
        <p>我们保留在任何时候暂停或终止您访问本服务的权利，无论是否提前通知，针对违反本条款或对其他用户、我们或第三方有害的行为。您可随时联系我们终止账户。</p>

        <h2 id="governing-law">11. 适用法律</h2>
        <p>本条款依照马来西亚法律管辖和解释。因本条款引起的任何争议须提交马来西亚法院专属管辖。</p>

        <h2 id="changes">12. 条款变更</h2>
        <p>我们可能会不时更新本条款。如有重大变更，我们将在本页发布更新后的条款及修订日期。在变更后继续使用本服务，即表示您接受更新后的条款。</p>

        <h2 id="contact">13. 联系我们</h2>
        <p>如您对本条款有任何疑问，请联系我们：</p>
        <ul>
            <li><strong>电子邮件：</strong> <a href="mailto:salessistemkami@gmail.com">salessistemkami@gmail.com</a></li>
            <li><strong>WhatsApp：</strong> <a href="https://wa.me/601123053082" target="_blank">+60 11-2405 3082</a></li>
            <li><strong>地址：</strong> Ipoh, Perak, Malaysia</li>
        </ul>

@elseif(app()->getLocale() === 'ms')

        <div class="policy-toc">
            <div class="fw-semibold mb-2" style="font-size:0.88rem; color:#001f4d;">{{ __('terms.contents') }}</div>
            <ol class="mb-0 ps-3" style="line-height:2">
                <li><a href="#acceptance">Penerimaan Terma</a></li>
                <li><a href="#services">Perkhidmatan Kami</a></li>
                <li><a href="#organizer-accounts">Akaun Penganjur</a></li>
                <li><a href="#customer-bookings">Tempahan Pelanggan</a></li>
                <li><a href="#payments">Pembayaran & Bayaran Balik</a></li>
                <li><a href="#acceptable-use">Penggunaan Yang Dibenarkan</a></li>
                <li><a href="#intellectual-property">Harta Intelek</a></li>
                <li><a href="#disclaimers">Penafian</a></li>
                <li><a href="#limitation">Had Liabiliti</a></li>
                <li><a href="#termination">Penamatan</a></li>
                <li><a href="#governing-law">Undang-Undang Yang Mengawal</a></li>
                <li><a href="#changes">Perubahan Terma</a></li>
                <li><a href="#contact">Hubungi Kami</a></li>
            </ol>
        </div>

        <p>
            Sila baca Terma & Syarat ("Terma") ini dengan teliti sebelum menggunakan platform Sistem Kami ("Perkhidmatan") yang dikendalikan oleh Sistem Kami ("kami").
        </p>
        <p>Dengan mengakses atau menggunakan Sistem Kami, anda bersetuju untuk terikat dengan Terma ini. Jika anda tidak bersetuju, sila jangan gunakan Perkhidmatan ini.</p>

        <h2 id="acceptance">1. Penerimaan Terma</h2>
        <p>
            Dengan mendaftar akaun, membuat tempahan, atau menggunakan Sistem Kami, anda mengesahkan bahawa anda berumur sekurang-kurangnya 18 tahun dan mempunyai kapasiti undang-undang untuk memasuki Terma ini. Jika anda menggunakan Perkhidmatan bagi pihak perniagaan, anda menyatakan bahawa anda mempunyai kuasa untuk mengikat perniagaan tersebut dengan Terma ini.
        </p>

        <h2 id="services">2. Perkhidmatan Kami</h2>
        <p>
            Sistem Kami menyediakan platform perisian yang membolehkan perniagaan ("Penganjur") mengurus pakej, slot masa, tempahan, pembayaran, dan komunikasi pelanggan. Kami bertindak sebagai platform teknologi sahaja dan bukan pihak dalam sebarang transaksi antara Penganjur dan pelanggan mereka.
        </p>

        <h2 id="organizer-accounts">3. Akaun Penganjur</h2>
        <ul>
            <li>Anda bertanggungjawab untuk mengekalkan kerahsiaan kelayakan log masuk anda.</li>
            <li>Anda mesti memberikan maklumat yang tepat dan terkini semasa mendaftar akaun anda.</li>
            <li>Anda bertanggungjawab sepenuhnya atas semua aktiviti yang berlaku di bawah akaun anda.</li>
            <li>Anda mesti memaklumkan kami dengan segera tentang sebarang akses tidak sah ke akaun anda.</li>
            <li>Akaun penganjur tidak boleh dipindahkan tanpa persetujuan bertulis kami terlebih dahulu.</li>
        </ul>

        <h2 id="customer-bookings">4. Tempahan Pelanggan</h2>
        <p>
            Tempahan yang dibuat melalui Sistem Kami adalah perjanjian antara pelanggan dan Penganjur. Sistem Kami memudahkan proses tempahan tetapi tidak bertanggungjawab atas kualiti, keselamatan, atau penyampaian perkhidmatan yang disediakan oleh Penganjur.
        </p>
        <ul>
            <li>Penganjur bertanggungjawab untuk menyampaikan terma tempahan, dasar pembatalan, dan butiran perkhidmatan mereka dengan jelas kepada pelanggan.</li>
            <li>Pelanggan mesti menyemak butiran tempahan dengan teliti sebelum mengesahkan.</li>
            <li>Sistem Kami tidak menjamin ketersediaan sebarang slot masa atau perkhidmatan tertentu.</li>
        </ul>

        <h2 id="payments">5. Pembayaran & Bayaran Balik</h2>
        <ul>
            <li>Pembayaran diproses oleh get pembayaran pihak ketiga (Toyyibpay, Stripe). Dengan membuat pembayaran, anda juga bersetuju dengan terma masing-masing.</li>
            <li>Sistem Kami tidak mengawal dasar bayaran balik Penganjur. Permintaan bayaran balik mesti diarahkan kepada Penganjur.</li>
            <li>Dalam kes pertikaian pembayaran, anda boleh menghubungi get pembayaran yang berkaitan secara terus.</li>
            <li>Sistem Kami berhak untuk menggantung atau menamatkan akaun yang terlibat dalam aktiviti pembayaran penipuan.</li>
        </ul>

        <h2 id="acceptable-use">6. Penggunaan Yang Dibenarkan</h2>
        <p>Anda bersetuju untuk tidak:</p>
        <ul>
            <li>Menggunakan platform untuk sebarang tujuan haram atau tidak dibenarkan</li>
            <li>Memuat naik kandungan yang palsu, mengelirukan, atau memfitnah</li>
            <li>Cuba mendapatkan akses tidak sah kepada mana-mana bahagian platform</li>
            <li>Mengganggu atau menyebabkan gangguan pada pelayan atau rangkaian platform</li>
            <li>Menggunakan alat automatik untuk mengikis atau mengumpul data tanpa kebenaran kami</li>
            <li>Menyamar sebagai mana-mana orang atau entiti</li>
            <li>Melanggar mana-mana undang-undang atau peraturan yang terpakai</li>
        </ul>

        <h2 id="intellectual-property">7. Harta Intelek</h2>
        <p>
            Semua kandungan, reka bentuk, kod, logo, dan tanda niaga di Sistem Kami adalah milik atau berlesen kepada kami. Anda tidak boleh mengeluarkan semula, mengedar, atau mencipta karya terbitan tanpa kebenaran bertulis kami.
        </p>
        <p>
            Anda mengekalkan pemilikan sebarang kandungan yang anda muat naik (contohnya imej pakej, penerangan perniagaan). Dengan memuat naik kandungan, anda memberikan kami lesen bukan eksklusif, bebas royalti untuk memaparkan dan menggunakan kandungan tersebut berkaitan dengan penyediaan Perkhidmatan.
        </p>

        <h2 id="disclaimers">8. Penafian</h2>
        <p>
            Perkhidmatan ini disediakan "seadanya" dan "sebagaimana tersedia" tanpa waranti dalam apa jua bentuk, tersurat atau tersirat. Kami tidak menjamin bahawa Perkhidmatan akan tidak terganggu, bebas ralat, atau bebas virus. Kami tidak bertanggungjawab atas sebarang keputusan yang dibuat berdasarkan maklumat yang dipaparkan di platform.
        </p>

        <h2 id="limitation">9. Had Liabiliti</h2>
        <p>
            Setakat yang dibenarkan oleh undang-undang, Sistem Kami tidak akan bertanggungjawab atas sebarang kerosakan tidak langsung, sampingan, khas, berbangkit, atau punitif yang timbul daripada atau berkaitan dengan penggunaan Perkhidmatan oleh anda, termasuk tetapi tidak terhad kepada kehilangan keuntungan, data, atau muhibah.
        </p>
        <p>Jumlah liabiliti kami kepada anda untuk sebarang tuntutan tidak akan melebihi jumlah yang dibayar oleh anda (jika ada) untuk menggunakan Perkhidmatan dalam tiga bulan sebelum tuntutan.</p>

        <h2 id="termination">10. Penamatan</h2>
        <p>
            Kami berhak untuk menggantung atau menamatkan akses anda kepada Perkhidmatan pada bila-bila masa, dengan atau tanpa notis, untuk tingkah laku yang melanggar Terma ini atau yang membahayakan pengguna lain, kami, atau pihak ketiga. Anda boleh menamatkan akaun anda pada bila-bila masa dengan menghubungi kami.
        </p>

        <h2 id="governing-law">11. Undang-Undang Yang Mengawal</h2>
        <p>
            Terma ini ditadbir dan ditafsirkan mengikut undang-undang Malaysia. Sebarang pertikaian yang timbul daripada Terma ini tertakluk kepada bidang kuasa eksklusif mahkamah Malaysia.
        </p>

        <h2 id="changes">12. Perubahan Terma</h2>
        <p>
            Kami mungkin mengemas kini Terma ini dari semasa ke semasa. Kami akan memaklumkan anda tentang perubahan material dengan menyiarkan Terma yang dikemas kini di halaman ini dengan tarikh semakan. Penggunaan berterusan Perkhidmatan selepas perubahan merupakan penerimaan Terma yang dikemas kini oleh anda.
        </p>

        <h2 id="contact">13. Hubungi Kami</h2>
        <p>Jika anda mempunyai sebarang soalan tentang Terma ini, sila hubungi kami:</p>
        <ul>
            <li><strong>E-mel:</strong> <a href="mailto:salessistemkami@gmail.com">salessistemkami@gmail.com</a></li>
            <li><strong>WhatsApp:</strong> <a href="https://wa.me/601123053082" target="_blank">+60 11-2405 3082</a></li>
            <li><strong>Alamat:</strong> Ipoh, Perak, Malaysia</li>
        </ul>

@else

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

@endif

    </div>
</section>

@endsection
