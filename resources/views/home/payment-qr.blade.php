<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>QR Pembayaran — {{ $organizer->name }}</title>
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: linear-gradient(135deg, #001f4d 0%, #001233 100%); min-height: 100dvh; }
    </style>
</head>
<body class="flex items-start justify-center p-4 pt-6">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden">
        {{-- Header --}}
        <div class="px-5 py-4 text-center" style="background: linear-gradient(135deg, #001f4d 0%, #003080 100%);">
            <h1 class="text-white font-bold text-lg leading-tight">{{ $organizer->name }}</h1>
            <p class="text-blue-200 text-sm mt-0.5">QR Kod Pembayaran</p>
        </div>

        @if($booking)
            @php $isFlow2 = $organizer->what_flow == 2; @endphp
            <div class="px-5 pt-4 pb-1 space-y-3">
                {{-- Customer info --}}
                <div class="space-y-1.5">
                    <p class="text-sm font-semibold text-gray-700">{{ ucwords(strtolower($booking->participant->name ?? '-')) }}</p>
                    <p class="text-xs text-gray-400">{{ $isFlow2 ? 'Slot' : 'Pakej' }}: {{ $booking->package->name ?? '-' }}</p>

                    {{-- Slots --}}
                    @foreach($booking->vendorTimeSlots as $slot)
                        <div class="flex items-start gap-2 text-xs text-gray-500">
                            <svg class="w-3.5 h-3.5 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <div>
                                @if($slot->vendorTimeSlot)
                                    <span class="font-medium text-gray-600">{{ $slot->vendorTimeSlot->slot_name }}</span> &middot;
                                @endif
                                @if($slot->booked_date_start)
                                    {{ \Carbon\Carbon::parse($slot->booked_date_start)->format('d M Y') }}
                                @endif
                                @if($slot->booked_time_start)
                                    &middot; {{ \Carbon\Carbon::parse($slot->booked_time_start)->format('h:i A') }}@if($slot->booked_time_end) - {{ \Carbon\Carbon::parse($slot->booked_time_end)->format('h:i A') }}@endif
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Payment summary --}}
                @if($booking->payment_type === 'deposit')
                    <div class="rounded-xl p-3 bg-gray-50 border border-gray-100 text-xs space-y-1.5">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Jumlah harga</span>
                            <span class="font-medium text-gray-600">RM {{ number_format($booking->final_price, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Deposit telah dibayar <span class="text-gray-300">({{ $booking->created_at->format('d M Y') }})</span></span>
                            <span class="font-medium text-green-600">- RM {{ number_format($booking->paid_amount ?? 0, 2) }}</span>
                        </div>
                        @if($booking->balance > 0)
                            <div class="border-t border-gray-200 pt-1.5 flex justify-between">
                                <span class="font-semibold text-gray-600">Baki perlu dibayar</span>
                                <span class="font-bold text-base" style="color: #001f4d;">RM {{ number_format($booking->balance, 2) }}</span>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        @endif

        {{-- Instructions --}}
        <div class="px-5 pt-3 pb-2">
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 text-sm text-amber-800 space-y-1.5">
                <p class="font-semibold flex items-center gap-1.5">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Cara Pembayaran
                </p>
                <ol class="list-decimal list-inside space-y-1 text-xs leading-relaxed pl-1">
                    <li>Screenshot atau muat turun QR code di bawah</li>
                    <li>Buka aplikasi perbankan anda dan imbas QR</li>
                    <li>Selesaikan pembayaran</li>
                    <li>Hantar resit pembayaran kepada {{ $organizer->name }} melalui butang Whatsapp di bawah</li>
                </ol>
            </div>
        </div>

        {{-- QR Image --}}
        <div class="px-5 py-3">
            <div class="bg-gray-50 rounded-xl p-4 flex items-center justify-center">
                <img src="{{ route('organizer.payment.qr.image', $organizer->slug) }}"
                     alt="QR Kod Pembayaran"
                     class="w-full rounded-lg">
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="px-5 pb-5 space-y-2.5">
            <a href="{{ route('organizer.payment.qr.image', $organizer->slug) }}"
               download="qr-pembayaran-{{ $organizer->slug }}.jpg"
               class="flex items-center justify-center gap-2 w-full text-white font-semibold py-3 px-4 rounded-xl text-sm"
               style="background-color: #001f4d;">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                Muat Turun QR Code
            </a>

            @if($organizer->phone)
                @php
                    $phone = preg_replace('/[^0-9]/', '', $organizer->phone);
                    if (str_starts_with($phone, '0')) {
                        $phone = '60' . substr($phone, 1);
                    }
                    $waText = 'Salam, saya ingin hantar resit pembayaran untuk ' . $organizer->name;
                    if ($booking) {
                        $waText .= ' (Kod Tempahan: ' . $booking->booking_code . ')';
                    }
                    $waUrl = 'https://wa.me/' . $phone . '?' . http_build_query(['text' => $waText]);
                @endphp
                <a href="{{ $waUrl }}"
                   target="_blank"
                   rel="noopener"
                   class="flex items-center justify-center gap-2 w-full bg-green-500 hover:bg-green-600 text-white font-semibold py-3 px-4 rounded-xl text-sm">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Hantar Resit via WhatsApp
                </a>
            @endif
        </div>

        {{-- Footer --}}
        <div class="bg-gray-50 px-5 py-3 flex items-center justify-center gap-1.5">
            <span class="text-xs text-gray-400">Dijana oleh</span>
            <img src="{{ asset('images/logo-black.png') }}" alt="Sistem Kami" class="h-4">
        </div>
    </div>
</body>
</html>
