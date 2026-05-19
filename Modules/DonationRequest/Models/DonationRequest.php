<?php

namespace Modules\DonationRequest\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Observers\CRUDObserver;
use Illuminate\Database\Eloquent\Model;
use Modules\Auth\Models\User;
use Modules\Core\Observers\CascadeSoftDeleteObserver;
use Modules\DonationRequest\Enums\DonationRequestStatus;
use Modules\DonationRequest\Observers\DonationRequestObserver;
use Modules\Institution\Models\Branch;

#[Fillable(['receiver_user_id', 'receiver_branch_id', 'status', 'notes'])]
#[ObservedBy([CRUDObserver::class, CascadeSoftDeleteObserver::class, DonationRequestObserver::class])]
class DonationRequest extends Model
{
    use HasFactory;
    use SoftDeletes;

    public string $logChannel = "donation-request";

    public array $cascadeDeletes = ['donationRequestItems'];

    protected $casts = [
        'receiver_user_id' => 'string',
        'receiver_branch_id' => 'string',
        'status' => DonationRequestStatus::class,
        'notes' => 'string',
    ];

    public function isApproved(): Attribute
    {
        return new Attribute(
            get: fn () => $this->status === DonationRequestStatus::APPROVED,
        );
    }

    public function isRejected(): Attribute
    {
        return new Attribute(
            get: fn () => $this->status === DonationRequestStatus::REJECTED,
        );
    }

    public function isPending(): Attribute
    {
        return new Attribute(
            get: fn () => $this->status === DonationRequestStatus::PENDING,
        );
    }

    // public function donation()
    // {
    //     return $this->belongsTo(Donation::class, 'donation_id', 'id');
    // }

    public function donationRequestItems()
    {
        return $this->hasMany(DonationRequestItem::class, 'donation_request_id', 'id');
    }

    public function receiverUser()
    {
        return $this->belongsTo(User::class, 'receiver_user_id', 'id');
    }

    public function receiverBranch()
    {
        return $this->belongsTo(Branch::class, 'receiver_branch_id', 'id');
    }
}
