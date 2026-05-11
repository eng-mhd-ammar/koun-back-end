<?php

namespace Modules\Donation\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Observers\CRUDObserver;
use Illuminate\Database\Eloquent\Model;
use Modules\DonationRequest\Models\DonationRequestItem;

#[Fillable(['donation_id', 'unit_id', 'donation_type_id', 'name', 'description', 'quantity', 'notes'])]
#[ObservedBy([CRUDObserver::class])]
class DonationItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    public string $logChannel = "donation-item";

    protected $casts = [
        'donation_id' => 'string',
        'unit_id' => 'string',
        'donation_type_id' => 'string',
        'name' => 'string',
        'description' => 'string',
        'quantity' => 'string',
        'notes' => 'string',
    ];

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }

    public function donationType(): BelongsTo
    {
        return $this->belongsTo(DonationType::class, 'donation_type_id', 'id');
    }

    public function donation(): BelongsTo
    {
        return $this->belongsTo(Donation::class, 'donation_id', 'id');
    }

    public function donationRequestItems(): HasMany {
        return $this->hasMany(DonationRequestItem::class, 'donation_item_id', 'id');
    }
}
