<?php

namespace Modules\Auth\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Modules\Auth\Enums\Gender;
use Modules\Core\Observers\CascadeSoftDeleteObserver;
use Modules\Core\Observers\SyncFilesObserver;
use Modules\Core\Observers\CRUDObserver;
use Modules\Donation\Models\Donation;
use Modules\DonationRequest\Models\DonationRequest;
use Modules\Institution\Models\Institution;
use Modules\Institution\Models\UserBranch;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

#[Fillable(['avatar', 'first_name', 'last_name', 'password', 'birthday', 'gender', 'username', 'phone', 'phone_verified_at', 'email', 'email_verified_at'])]
#[ObservedBy([SyncFilesObserver::class, CascadeSoftDeleteObserver::class, CRUDObserver::class])]
class User extends Authenticatable implements JWTSubject
{
    use HasFactory;
    use Notifiable;
    use SoftDeletes;
    use HasPermissions;
    use HasRoles;

    public string $logChannel = "user";

    public array $FilesFields = ['avatar'];

    public array $cascadeDeletes = ['verificationCodes', 'userBranches'];

    protected $guard_name = 'api';

    protected $casts = [
        'avatar' => 'string',
        'first_name' => 'string',
        'last_name' => 'string',
        'password' => 'hashed',
        'birthday' => 'date',
        'gender' => Gender::class,
        'username' => 'string',
        'phone' => 'string',
        // 'phone_verified_at' => 'datetime',
        'email' => 'string',
        // 'email_verified_at' => 'datetime',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function isAdmin(): Attribute
    {
        return new Attribute(
            get: fn () => Auth::guard('api')->check() && $this->hasRole('admin'),
        );
    }

    public function isUser(): Attribute
    {
        return new Attribute(
            get: fn () => Auth::guard('api')->check() && $this->hasRole('user'),
        );
    }

    public function isDelivery(): Attribute
    {
        return new Attribute(
            get: fn () => Auth::guard('api')->check() && $this->hasRole('delivery'),
        );
    }

    public function avatarUrl(): Attribute
    {
        return new Attribute(
            get: fn () => $this->avatar ? asset($this->avatar) : '',
        );
    }

    public function fullName(): Attribute
    {
        return new Attribute(
            get: fn () => $this->first_name . ' ' . $this->last_name,
        );
    }

    public function formattedBirthday(): Attribute
    {
        return new Attribute(
            get: fn () => $this->birthday ? \Carbon\Carbon::parse($this->birthday)->format('Y-m-d') : null,
        );
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function verificationCodes(): HasMany
    {
        return $this->hasMany(VerificationCode::class, 'user_id', 'id');
    }

    public function ownedInstitutions(): HasMany
    {
        return $this->hasMany(Institution::class, 'owner_id', 'id');
    }

    public function userBranches(): HasMany
    {
        return $this->hasMany(UserBranch::class, 'user_id', 'id');
    }

    public function branches(): BelongsToMany
    {
        return $this->belongsToMany(Branch::class, 'user_branches', 'user_id', 'branch_id');
    }

    public function memberInstitutions(): BelongsToMany
    {
        return $this->belongsToMany(Institution::class, 'user_institutions', 'user_id', 'institution_id');
    }

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class, 'sender_user_id', 'id');
    }

    public function donationsRequests(): HasMany
    {
        return $this->hasMany(DonationRequest::class, 'receiver_user_id', 'id');
    }
}
