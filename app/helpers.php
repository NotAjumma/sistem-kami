<?php

use Illuminate\Support\Str;

if (!function_exists('lroute')) {
    function lroute(string $name, array $parameters = []): string
    {
        return \App\Helpers\LocaleUrl::route($name, $parameters);
    }
}

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

if (!function_exists('mail_env_tag')) {
    function mail_env_tag(): string
    {
        return match (app()->environment()) {
            'production' => '[PROD]',
            'staging'    => '[STG]',
            'local'      => '[LOCAL]',
            default      => '[' . strtoupper(app()->environment()) . ']',
        };
    }
}

if (!function_exists('configure_resend_mailer')) {
    /**
     * Apply Resend SMTP config at runtime using the DB-stored API key.
     * Call this before any Mail::send() outside of HealthCheckReport.
     * Returns false if resend_api_key is not configured.
     */
    function configure_resend_mailer(): bool
    {
        // Staging: suppress all emails except health check (which configures itself directly)
        if (app()->environment('staging')) {
            return false;
        }

        $resendKey = \App\Models\AppSetting::get('resend_api_key', '');

        if (empty($resendKey)) {
            return false;
        }

        $fromEmail = \App\Models\AppSetting::get('health_check_from', 'onboarding@resend.dev');
        $fromName  = \App\Models\AppSetting::get('health_check_from_name', 'Sistem Kami');

        \Illuminate\Support\Facades\Config::set('mail.default', 'smtp');
        \Illuminate\Support\Facades\Config::set('mail.mailers.smtp.host',       'smtp.resend.com');
        \Illuminate\Support\Facades\Config::set('mail.mailers.smtp.port',       465);
        \Illuminate\Support\Facades\Config::set('mail.mailers.smtp.encryption', 'ssl');
        \Illuminate\Support\Facades\Config::set('mail.mailers.smtp.username',   'resend');
        \Illuminate\Support\Facades\Config::set('mail.mailers.smtp.password',   $resendKey);
        \Illuminate\Support\Facades\Config::set('mail.from.address',            $fromEmail);
        \Illuminate\Support\Facades\Config::set('mail.from.name',               $fromName);

        return true;
    }
}
