<?php

namespace Modules\Institution\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Observers\CascadeSoftDeleteObserver;
use Modules\Core\Observers\SyncFilesObserver;
use Modules\Core\Observers\CRUDObserver;
use Modules\Institution\Enums\InstitutionType;
use Illuminate\Database\Eloquent\Model;
use Modules\Auth\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

#[Fillable(['logo', 'name', 'description', 'owner_id', 'phone', 'email', 'type', 'is_active', 'attachments'])]
#[ObservedBy([SyncFilesObserver::class, CascadeSoftDeleteObserver::class, CRUDObserver::class])]
class Institution extends Model
{
    use HasFactory;
    use SoftDeletes;

    public string $logChannel = "institution";

    public array $cascadeDeletes = ['branches'];

    protected $casts = [
        'logo' => 'string',
        'name' => 'string',
        'description' => 'string',
        'owner_id' => 'string',
        'phone' => 'string',
        'email' => 'string',
        'type' => InstitutionType::class,
        'is_active' => 'boolean',
        'attachments' => 'array',
    ];

    protected $attributes = [
        'attachments' => '[]',
    ];

    public function isDonor(): Attribute
    {
        return new Attribute(
            get: fn () => in_array($this->type, [InstitutionType::DONOR, InstitutionType::BOTH]),
        );
    }

    public function isCharity(): Attribute
    {
        return new Attribute(
            get: fn () => in_array($this->type, [InstitutionType::CHARITY, InstitutionType::BOTH]),
        );
    }

    public function employees(): Attribute
    {
        return Attribute::make(
            get: fn () => collect([
                $this->owner,
                ...$this->members ?? []
            ])
            ->filter()
            ->unique('id')
            ->values()
        );
    }

    public function admins(): Attribute
    {
        return Attribute::make(
            get: fn () => collect([
                $this->owner,
                ...($this->members?->filter(
                    fn ($member) => $member->pivot?->is_admin
                ) ?? [])
            ])
            ->filter()
            ->unique('id')
            ->values()
        );
    }

    public function logoUrl(): Attribute
    {
        return new Attribute(
            get: fn () => $this->logo ? asset($this->logo) : '',
        );
    }

    protected function attachmentsUrls(): Attribute
    {
        return new Attribute(
            get: fn () => array_map(function ($attachment) {
                return asset($attachment);
            }, $this->attachments),
        );
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id', 'id');
    }

    public function branches(): HasMany
    {
        return $this->hasMany(Branch::class, 'institution_id', 'id');
    }

    public function userInstitutions(): HasMany
    {
        return $this->hasMany(UserInstitution::class, 'institution_id', 'id');
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_institutions', 'institution_id', 'user_id');
    }

    public function scopeForUser(Builder $query, bool $value = false): Builder
    {
        $user = Auth::user();

        if (!$value) {
            if ($user->is_admin) {
                return $query;
            } else {
                $value = !$value;
            }
        }

        return $query->where(function ($q) use ($user) {

            $q->where('owner_id', $user->id)
            ->orWhereHas('members', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        });
    }

    public function isEmployee($user): bool
    {
        return $this->employees->contains('id', $user?->id);
    }

    public function isAdmin($user): bool
    {
        return $this->admins->contains('id', $user?->id);
    }
}
