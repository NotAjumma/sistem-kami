<footer class="ldt-footer">
    <div class="footer-brand">{{ $organizer->name }}</div>
    <div class="footer-links">
        <a href="{{ route('business.profile', ['slug' => $specialPage]) }}">Laman Utama</a>
        <a href="{{ route('special-page.wedding', ['slug' => $specialPage]) }}">Perkahwinan</a>
        @if(isset($packageGroups) && ($packageGroups->isNotEmpty() || (isset($standalonePackages) && $standalonePackages->isNotEmpty())))
        <a href="{{ route('special-page.packages', ['slug' => $specialPage]) }}">Pakej</a>
        @endif
        @if($organizer->phone)
        <a href="https://wa.me/{{ preg_replace('/\D/', '', $organizer->phone) }}" target="_blank">WhatsApp</a>
        @endif
    </div>
    <p>© {{ date('Y') }} {{ $organizer->name }}. Hak cipta terpelihara.</p>
</footer>
