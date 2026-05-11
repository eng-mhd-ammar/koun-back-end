<?php

namespace Modules\DonationRequest\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Observers\CRUDObserver;
use Illuminate\Database\Eloquent\Model;
use Modules\DonationRequest\Enums\DeliveryStatus;
use Modules\DonationRequest\Models\DonationRequest;

#[Fillable(['donation_request_id', 'delivery_id', 'receiver_id', 'status', 'picked_at', 'delivered_at'])]
#[ObservedBy([CRUDObserver::class])]
class Delivery extends Model
{
    use HasFactory;
    use SoftDeletes;

    public string $logChannel = "delivery";

    protected $casts = [
        'donation_request_id' => 'string',
        'delivery_id' => 'string',
        'receiver_id' => 'string',
        'status' => DeliveryStatus::class,
        'picked_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function formattedPickedAt(): Attribute
    {
        return new Attribute(
            get: fn () => $this->picked_at ? \Carbon\Carbon::parse($this->picked_at)->format('Y-m-d H:i') : null,
        );
    }

    public function formattedDeliveredAt(): Attribute
    {
        return new Attribute(
            get: fn () => $this->delivered_at ? \Carbon\Carbon::parse($this->delivered_at)->format('Y-m-d H:i') : null,
        );
    }

    public function isPending(): Attribute
    {
        return new Attribute(
            get: fn () => $this->status === DeliveryStatus::PENDING,
        );
    }

    public function isPickedUp(): Attribute
    {
        return new Attribute(
            get: fn () => $this->status === DeliveryStatus::PICKED_UP,
        );
    }

    public function isInTransit(): Attribute
    {
        return new Attribute(
            get: fn () => $this->status === DeliveryStatus::IN_TRANSIT,
        );
    }

    public function isDelivered(): Attribute
    {
        return new Attribute(
            get: fn () => $this->status === DeliveryStatus::DELIVERED,
        );
    }

    public function delivery(): BelongsTo
    {
        return $this->belongsTo(User::class, 'delivery_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function donationRequest(): BelongsTo
    {
        return $this->belongsTo(DonationRequest::class, 'donation_request_id');
    }
}
