<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'bookings';

    // ── Status ───────────────────────────────────────────────────────────────
    const STATUS_PENDING   = 'pending';    // created, awaiting payment
    const STATUS_PAID      = 'paid';       // payment received (sistemkami gateway)
    const STATUS_CONFIRMED = 'confirmed';  // manually confirmed (gform / admin)
    const STATUS_COMPLETED = 'completed';  // booking fulfilled
    const STATUS_CANCELLED = 'cancelled';  // booking cancelled
    const STATUS_FAILED    = 'failed';     // payment failed

    /** Statuses considered as "revenue" (paid + upcoming) */
    const REVENUE_STATUSES = [self::STATUS_PAID, self::STATUS_CONFIRMED, self::STATUS_COMPLETED];

    /** Statuses that count as active/valid bookings (not cancelled/failed) */
    const ACTIVE_STATUSES  = [self::STATUS_PENDING, self::STATUS_PAID, self::STATUS_CONFIRMED, self::STATUS_COMPLETED];

    // ── Payment Type ─────────────────────────────────────────────────────────
    const PAYMENT_TYPE_FULL_PAYMENT = 'full_payment';
    const PAYMENT_TYPE_DEPOSIT      = 'deposit';

    // ── Payment Method ───────────────────────────────────────────────────────
    const PAYMENT_METHOD_SISTEMKAMI = 'sistemkami';
    const PAYMENT_METHOD_GFORM      = 'gform';

    protected $fillable = [
        'participant_id',
        'event_id',
        'package_id',
        'organizer_id',
        'promoter_id',
        'booking_code',
        'total_price',
        'resit_path',
        'payment_method',
        'payment_type',
        'status',
        'coupon_code',
        'discount',
        'service_charge',
        'final_price',
        'extra_info',
        'paid_amount',
        'reminder_sent_at',
    ];

    protected $casts = [
        'extra_info'       => 'array',
        'reminder_sent_at' => 'datetime',
    ];

    protected $appends = [
        'is_deposit',
    ];


    // Relasi ke participant
    public function participant()
    {
        return $this->belongsTo(Participant::class);
    }

    // Relasi ke booking tickets (details)
    public function bookingTickets()
    {
        return $this->hasMany(BookingTicket::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function vendorTimeSlots()
    {
        return $this->hasMany(BookingsVendorTimeSlot::class, 'booking_id');
    }

    public function organizer()
    {
        return $this->belongsTo(Organizer::class);
    }

    public function getBalanceAttribute(): float
    {
        if ($this->payment_type !== 'deposit') {
            return 0.00;
        }

        return round(
            $this->final_price - ($this->paid_amount ?? 0),
            2
        );
    }

    public function getIsDepositAttribute(): bool
    {
        return $this->payment_type === 'deposit';
    }

    public function addons()
    {
        return $this->belongsToMany(PackageAddon::class, 'booking_addon', 'booking_id', 'addon_id')
                    ->withPivot('qty')
                    ->withTimestamps();
    }

    public function bookingAddons()
    {
        return $this->hasMany(BookingAddon::class, 'booking_id');
    }

    public function getDisplayImageUrlAttribute()
    {
        // 🔹 1️⃣ Try Slot Image First
        $bookingSlot = $this->vendorTimeSlots->first();

        if ($bookingSlot && $bookingSlot->vendorTimeSlot) {

            $slot = $bookingSlot->vendorTimeSlot;

            if ($slot->images->count()) {

                $image = $slot->images->firstWhere('is_cover', true)
                        ?? $slot->images->first();

                return asset(
                    "storage/uploads/{$this->organizer_id}/slots/{$slot->id}/{$image->url}"
                );
            }
        }

        // 🔹 2️⃣ Fallback Package Image
        if ($this->package && !empty($this->package->images)) {

            $image = collect($this->package->images)
                    ->firstWhere('is_cover', true)
                    ?? $this->package->images[0];

            return asset(
                "storage/uploads/{$this->organizer_id}/packages/{$this->package->id}/{$image['url']}"
            );
        }

        // 🔹 3️⃣ Final Fallback (Default Placeholder)
        return asset('images/default-placeholder.jpg');
    }

    public function promoter()
    {
        return $this->belongsTo(Worker::class, 'promoter_id');
    }

    public function details()
    {
        return $this->hasMany(BookingDetail::class);
    }

    public function getDetail($key)
    {
        return optional(
            $this->details->where('field_key', $key)->first()
        )->field_value;
    }

    public function walletTransactions()
    {
        return $this->morphMany(WalletTransaction::class, 'reference');
    }
}
