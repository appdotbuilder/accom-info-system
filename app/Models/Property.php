<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Property
 *
 * @property int $id
 * @property int $accommodation_owner_id
 * @property string $name
 * @property string $description
 * @property string $type
 * @property string $address
 * @property string $city
 * @property string $state
 * @property string $country
 * @property string|null $postal_code
 * @property int $max_guests
 * @property int $bedrooms
 * @property int $bathrooms
 * @property float $price_per_night
 * @property array|null $amenities
 * @property array|null $photos
 * @property bool $is_active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\AccommodationOwner $accommodationOwner
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Booking> $bookings
 * @property-read int|null $bookings_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Review> $reviews
 * @property-read int|null $reviews_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SecurityLog> $securityLogs
 * @property-read int|null $security_logs_count
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Property newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Property newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Property query()
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereAccommodationOwnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property wherePostalCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereMaxGuests($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereBedrooms($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereBathrooms($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property wherePricePerNight($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereAmenities($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property wherePhotos($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Property active()
 * @method static \Database\Factories\PropertyFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class Property extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'accommodation_owner_id',
        'name',
        'description',
        'type',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'max_guests',
        'bedrooms',
        'bathrooms',
        'price_per_night',
        'amenities',
        'photos',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'max_guests' => 'integer',
        'bedrooms' => 'integer',
        'bathrooms' => 'integer',
        'price_per_night' => 'decimal:2',
        'amenities' => 'array',
        'photos' => 'array',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the accommodation owner that owns this property.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function accommodationOwner(): BelongsTo
    {
        return $this->belongsTo(AccommodationOwner::class);
    }

    /**
     * Get all bookings for this property.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get all reviews for this property.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get all security logs for this property.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function securityLogs(): HasMany
    {
        return $this->hasMany(SecurityLog::class);
    }

    /**
     * Scope a query to only include active properties.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}