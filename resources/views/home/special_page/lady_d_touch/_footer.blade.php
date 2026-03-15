<footer class="ldt-footer">
    <div class="footer-brand">{{ $organizer->name }}</div>
    <div class="footer-links">
        <a href="{{ route(app()->getLocale() === 'ms' ? 'bm.business.profile' : 'business.profile', ['slug' => $specialPage]) }}">{{ __('lady_d_touch.nav_home') }}</a>
        <a href="{{ route(app()->getLocale() === 'ms' ? 'bm.special-page.wedding' : 'special-page.wedding', ['slug' => $specialPage]) }}">{{ __('lady_d_touch.nav_wedding') }}</a>
        @if($organizer->phone)
        <a href="https://wa.me/{{ preg_replace('/\D/', '', $organizer->phone) }}" target="_blank">WhatsApp</a>
        @endif
    </div>
    <p>© {{ date('Y') }} {{ $organizer->name }}. {{ __('lady_d_touch.footer_rights') }}</p>
</footer>
