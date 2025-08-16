<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\AccommodationOwner
 *
 * @property int $id
 * @property int $user_id
 * @property string $business_name
 * @property string|null $phone
 * @property string|null $address
 * @property string|null $description
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Property> $properties
 * @property-read int|null $properties_count
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|AccommodationOwner newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccommodationOwner newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AccommodationOwner query()
 * @method static \Illuminate\Database\Eloquent\Builder|AccommodationOwner whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccommodationOwner whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccommodationOwner whereBusinessName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccommodationOwner wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccommodationOwner whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccommodationOwner whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccommodationOwner whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccommodationOwner whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccommodationOwner whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AccommodationOwner active()
 * @method static \Database\Factories\AccommodationOwnerFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class AccommodationOwner extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'business_name',
        'phone',
        'address',
        'description',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns this accommodation business.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all properties for this accommodation owner.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }

    /**
     * Scope a query to only include active accommodation owners.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}