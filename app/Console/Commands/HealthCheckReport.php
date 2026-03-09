<?php

namespace App\Console\Commands;

use App\Mail\HealthCheckMail;
use App\Models\AppSetting;
use App\Services\HealthCheckService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class HealthCheckReport extends Command
{
    protected $signature   = 'health:report {--force : Send email even if all checks pass}';
    protected $description = 'Run site health checks and email the report';

    public function handle(): int
    {
        $this->info('Running health checks…');

        $report = HealthCheckService::run();

        $failed = $report['total'] - $report['passed'];
        $color  = $failed === 0 ? 'green' : 'red';

        $this->line('');
        foreach ($report['results'] as $r) {
            $icon = $r['status'] === 'pass' ? '<fg=green>✔</>' : '<fg=red>✘</>';
            $this->line("  $icon [{$r['group']}] {$r['name']}: {$r['detail']}");
        }
        $this->line('');
        $this->line("<fg=$color>Result: {$report['summary']}</>");

        $email     = AppSetting::get('health_check_email', 'salessistemkami@gmail.com');
        $resendKey = AppSetting::get('resend_api_key', '');

        if (empty($email)) {
            $this->warn('No health_check_email in settings — skipping email.');
            return $failed > 0 ? self::FAILURE : self::SUCCESS;
        }

        if (empty($resendKey)) {
            $this->warn('No resend_api_key in settings — skipping email. Add it at Superadmin → Settings.');
            return $failed > 0 ? self::FAILURE : self::SUCCESS;
        }

        if ($failed > 0 || $this->option('force')) {
            // From address: use DB setting, fallback to Resend's pre-verified test sender
            $fromEmail = AppSetting::get('health_check_from', 'onboarding@resend.dev');
            $fromName  = AppSetting::get('health_check_from_name', 'Sistem Kami Health Check');

            // Configure Resend SMTP at runtime using the DB-stored key
            Config::set('mail.default', 'smtp');
            Config::set('mail.mailers.smtp.host',       'smtp.resend.com');
            Config::set('mail.mailers.smtp.port',       465);
            Config::set('mail.mailers.smtp.encryption', 'ssl');
            Config::set('mail.mailers.smtp.username',   'resend');
            Config::set('mail.mailers.smtp.password',   $resendKey);
            Config::set('mail.from.address',            $fromEmail);
            Config::set('mail.from.name',               $fromName);

            $trigger = $this->option('force') ? 'forced' : 'failures detected';
            $this->info("Sending report from $fromEmail to $email ($trigger)…");

            Mail::to($email)->send(new HealthCheckMail($report));

            $this->info('Email sent.');
        } else {
            $this->info('All checks passed — no email sent (use --force to always send).');
        }

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }
}
