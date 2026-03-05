<?php

namespace App\Services;

use App\Models\CommissionRule;
use App\Models\PromoterCommission;
use App\Models\User;
use Illuminate\Support\Collection;
use App\Models\Worker;

class CommissionCalculator
{
    // Calculate commission for a given worker, package, addon and amount
    public static function calculateWorkerCommission(array $context, $rules): array
    {
        $workerId = $context['worker_id'];
        $packageId = $context['package_id'] ?? null;
        $addonId = $context['addon_id'] ?? null;
        $amount = (float) ($context['amount'] ?? 0);
        $bookingId = $context['booking_id'] ?? null;
        
       
        // \Log::info("rules" . json_encode($rules->toArray()));
        // \Log::info("context" . json_encode($context));
        $rule = null;

        // 1️⃣ If addon_id exists, prioritize addon rule first
        if ($addonId) {
            $rule = $rules->first(fn($r) =>
                $r->worker_id == $workerId &&
                $r->addon_id == $addonId
            );
        }

        // 2️⃣ Then try full package+addon match
        if (!$rule) {
            $rule = $rules->first(fn($r) =>
                $r->worker_id == $workerId &&
                $r->package_id == $packageId &&
                $r->addon_id == $addonId
            );
        }

        // 3️⃣ Then fallback to package-only rule
        if (!$rule && $packageId) {
            $rule = $rules->first(fn($r) =>
                $r->worker_id == $workerId &&
                $r->package_id == $packageId &&
                is_null($r->addon_id)
            );
        }

        // 4️⃣ Then fallback to addon-only rule (addon_id present, package null)
        if (!$rule && $addonId) {
            $rule = $rules->first(fn($r) =>
                $r->worker_id == $workerId &&
                is_null($r->package_id) &&
                $r->addon_id == $addonId
            );
        }

        if (!$rule) {
            return ['commission' => 0.0, 'rule' => null];
        }

        $commission = $rule->commission_type === 'percentage'
            ? ($rule->commission_value / 100.0) * $amount
            : (float) $rule->commission_value;

        if($bookingId == 97) {
            // \Log::info("Calculating commission for booking_id: " . $bookingId);
            // \Log::info("amount commission for booking_id: " . $amount);
            // \Log::info("commission: " . $commission);
        }
        return ['commission' => round($commission, 2), 'rule' => $rule];
    }

    public static function calculateWorkerCommissionAddon(array $context, $rules): array
    {
        $workerId = $context['worker_id'];
        $packageId = $context['package_id'] ?? null;
        $addonId = $context['addon_id'] ?? null;
        $amount = (float) ($context['amount'] ?? 0);
        $bookingId = $context['booking_id'] ?? null;
        
       
        \Log::info("bookingId " . $bookingId);
        \Log::info("workerId " . $workerId);
        \Log::info("amount " . $amount);
        // \Log::info("context" . json_encode($context));
        $rule = null;

        // 1️⃣ If addon_id exists, prioritize addon rule first
        if ($addonId) {
            $rule = $rules->first(fn($r) =>
                $r->worker_id == $workerId &&
                $r->addon_id == $addonId
            );


            $commission = $rule->commission_type === 'percentage'
                ? ($rule->commission_value / 100.0) * $amount
                : (float) $rule->commission_value;
        }

        if (!$rule) {
            return ['commission' => 0.0, 'rule' => null];
        }

        return ['commission' => round($commission, 2), 'rule' => $rule];
    }

    // Calculate promoter commission for a booking amount and promoter id
    public static function calculatePromoterCommission(?int $promoterId, float $amount, ?int $workerId = null): array
    {
        if (!$promoterId) {
            return ['commission' => 0.0, 'rule' => null];
        }

        // Try worker-specific first, then global (worker_id null)
        $rule = PromoterCommission::where('worker_id', $workerId)->first();
        if (!$rule) {
            $rule = PromoterCommission::whereNull('worker_id')->first();
        }

        if (!$rule) {
            return ['commission' => 0.0, 'rule' => null];
        }

        $commission = 0.0;
        if ($rule->commission_type === 'percentage') {
            $commission = ($rule->commission_value / 100.0) * $amount;
        } else {
            $commission = (float) $rule->commission_value;
        }

        return ['commission' => round($commission, 2), 'rule' => $rule];
    }

    // Calculate commissions for a booking record structure
    public static function calculateForBooking(array $booking, $workers, $rules): array
    {
        $totalWorkerCommission = 0.0;
        $workerBreakdown = [];
        $finalAmount = (float) $booking['amount'];

        // 1️⃣ Loop through workers
        foreach ($workers as $worker) {
            $workerAddonCommission = 0.0;
            $remainingPackageAmount = $finalAmount;

            foreach ($booking['addons'] ?? [] as $addon) {

                // Get all workers that own this addon
                $addonWorkers = $rules->filter(fn($r) =>
                    $r->addon_id == $addon['id']
                );

                if ($addonWorkers->isNotEmpty()) {

                    $shareCount = $addonWorkers->count();

                    // check if current worker is one of them
                    $workerRule = $addonWorkers->firstWhere('worker_id', $worker->id);

                    if ($workerRule) {

                        // Split addon amount
                        $sharedAmount = (float)$addon['total_price'] / $shareCount;

                        $workerAddonCommission += $sharedAmount;
                    }

                    // Remove addon price from package (only once globally)
                    $remainingPackageAmount -= (float)$addon['total_price'];
                }
            }

            // 2️⃣ Subtract all addon prices that **any worker owns** from package for this worker
            // foreach ($booking['addons'] ?? [] as $addon) {
            //     $ownerRule = $rules->first(fn($r) => $r->addon_id == $addon['id']);
            //     if ($ownerRule && $ownerRule->worker_id != $worker->id) {
            //         $remainingPackageAmount -= (float)$addon['total_price'];
            //     }
            // }

            $remainingPackageAmount = max(0, $remainingPackageAmount);

            // 3️⃣ Calculate package commission for remaining amount
            $resPackage = self::calculateWorkerCommission([
                'booking_id' => $booking['id'],
                'worker_id' => $worker->id,
                'package_id' => $booking['package_id'],
                'addon_id' => null,
                'amount' => $remainingPackageAmount,
            ], $rules);

            $workerTotal = $workerAddonCommission + $resPackage['commission'];

            if ($workerTotal > 0) {
                $workerBreakdown[] = [
                    'worker_id' => $worker->id,
                    'worker_name' => $worker->name,
                    'addon_commission' => round($workerAddonCommission, 2),
                    'package_commission' => round($resPackage['commission'], 2),
                    'commission' => round($workerTotal, 2),
                ];

                $totalWorkerCommission += $workerTotal;
            }
        }

        // 4️⃣ Promoter commission
        $promoterRes = self::calculatePromoterCommission(
            $booking['promoter_id'] ?? null,
            $finalAmount
        );
        $promoterCommission = $promoterRes['commission'];

        $companyNet = round($finalAmount - $totalWorkerCommission - $promoterCommission, 2);

        return [
            'booking_id' => $booking['id'],
            'booking_date' => $booking['created_at'] ?? null,
            'worker_breakdown' => $workerBreakdown,
            'total_worker_commission' => round($totalWorkerCommission, 2),
            'promoter_commission' => $promoterCommission,
            'company_net' => $companyNet,
        ];
    }
}
