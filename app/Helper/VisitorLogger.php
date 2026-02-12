<?php

namespace App\Helper;

use App\Models\VisitorAction;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Str;

class VisitorLogger
{
    public static function log($action, $page = null, $referenceId = null, $meta = [], $uri = null)
    {
        // return;
        $agent = new Agent();

        $userAgent = request()->userAgent();
        
        if ($agent->isRobot() || preg_match('/bot|crawl|spider|facebook|whatsapp|telegram|preview|meta/i', $userAgent)) {
            return; // skip logging for bots
        }

        if (!session()->has('visitor_id')) {
            session(['visitor_id' => (string) Str::uuid()]);
        }

        // normalize meta
        if (isset($meta['time']) && is_array($meta['time'])) {
            $meta['time'] = implode(',', $meta['time']);
        }

        $finalUri = $uri ?? request()->fullUrl();

        /*
        ========================================
        PREVENT DUPLICATE LOGGING (IMPORTANT)
        ========================================
        */
        $last = VisitorAction::where('visitor_id', session('visitor_id'))
            ->where('action', $action)
            ->where('reference_id', $referenceId)
            ->latest()
            ->first();

        // if same action within 8 seconds → ignore
        if ($last && now()->diffInSeconds($last->created_at) < 8) {
            return;
        }

        VisitorAction::create([
            'visitor_id' => session('visitor_id'),
            'action' => $action,
            'page' => $page,
            'uri' => $finalUri,
            'reference_id' => $referenceId,
            'meta' => $meta,
            'ip_address' => request()->ip(),
            'browser' => $agent->browser(),
            'platform' => $agent->platform(),
            'device' => $agent->isMobile() ? 'Mobile' : ($agent->isTablet() ? 'Tablet' : 'Desktop'),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
