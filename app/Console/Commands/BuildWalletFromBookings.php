<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Models\Organizer;
use App\Models\WalletTransaction;
use DB;

class BuildWalletFromBookings extends Command
{
    protected $signature = 'wallet:rebuild';

    protected $description = 'Rebuild wallet balance from bookings';

    public function handle()
    {
        $organizers = Organizer::all();

        foreach ($organizers as $organizer) {

            $balance = 0;

            $bookings = Booking::where('organizer_id', $organizer->id)
                ->where('status', 'paid')
                ->get();

            foreach ($bookings as $booking) {

                if ($booking->paid_amount <= 0) {
                    continue;
                }

                $before = $balance;
                $balance += $booking->paid_amount;

                WalletTransaction::create([
                    'organizer_id' => $organizer->id,
                    'type' => 'income',
                    'amount' => $booking->paid_amount,
                    'balance_before' => $before,
                    'balance_after' => $balance,
                    'reference_type' => Booking::class,
                    'reference_id' => $booking->id,
                    'description' => 'Booking payment - '.$booking->payment_type
                ]);
            }

            $organizer->update([
                'wallet_balance' => $balance
            ]);

        }

        $this->info('Wallet rebuilt successfully');
    }
}