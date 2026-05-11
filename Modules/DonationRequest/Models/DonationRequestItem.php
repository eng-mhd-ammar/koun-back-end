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
use Modules\Donation\Models\DonationItem;
use Modules\DonationRequest\Enums\DeliveryStatus;
use Modules\DonationRequest\Models\DonationRequest;

#[Fillable(['donation_request_id', 'donation_item_id', 'requested_quantity', 'approved_quantity', 'received_quantity'])]
#[ObservedBy([CRUDObserver::class])]
class DonationRequestItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    public string $logChannel = "donation-request-item";

    protected $casts = [
        'donation_request_id' => 'string',
        'donation_item_id' => 'string',
        'requested_quantity' => 'integer',
        'approved_quantity' => 'integer',
        'received_quantity' => 'integer',
    ];

    public function donationItem(): BelongsTo {
        return $this->belongsTo(DonationItem::class, 'donation_item_id', 'id');
    }

    public function donationRequest(): BelongsTo {
        return $this->belongsTo(DonationRequest::class, 'donation_request_id', 'id');
    }
}
