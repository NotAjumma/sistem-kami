<?php

use Illuminate\Support\Str;

if (!function_exists('format_my_phone')) {
    /**
     * Format a phone number based on country.
     *
     * @param string $number
     * @param string|null $country
     * @return string
     */
    function format_my_phone($number, $country = null)
    {
        $number = preg_replace('/\D/', '', $number); // Remove non-digits

        if ($country === 'MY') {
            // Malaysian numbers (assumes starting with 0 and 10–11 digits)
            if (Str::startsWith($number, '0') && (strlen($number) === 10 || strlen($number) === 11)) {
                $prefix = substr($number, 1, 2);    // e.g., 11
                $middle = substr($number, 3, 4);    // e.g., 2406
                $end = substr($number, 7);          // e.g., 0291

                return "+60 $prefix-$middle $end";
            }
        }

        // Fallback — show as international format if number has country code
        if (Str::startsWith($number, '60')) {
            return '+' . $number;
        }

        // Otherwise, just return raw digits
        return $number;
    }

}

if (!function_exists('get_payment_method_label')) {
    function get_payment_method_label($method)
    {
        $methods = [
            'sistemkami-toyyibpay' => 'Bank Transfer',
            'manual-bank' => 'Manual Bank Transfer',
            'stripe-card' => 'Credit Card (Stripe)',
        ];

        return $methods[$method] ?? ucfirst(str_replace(['-', '_'], ' ', $method));
    }
}

if (!function_exists('get_payment_type_label')) {
    function get_payment_type_label($type)
    {
        return match ($type) {
            'full_payment' => 'Full Payment',
            'partial_payment' => 'Partial Payment',
            default => ucfirst(str_replace('_', ' ', $type)),
        };
    }
}

if (!function_exists('format_payment_datetime')) {
    function format_payment_datetime($datetime)
    {
        return $datetime?->format('d M Y, g:i A') ?? '-';
    }
}
