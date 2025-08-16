<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\Booking
 *
 * @property int $id
 * @property int $property_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon $check_in_date
 * @property \Illuminate\Support\Carbon $check_out_date
 * @property int $guests
 * @property float $total_price
 * @property string $status
 * @property string|null $special_requests
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Property $property
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ServiceRequest> $serviceRequests
 * @property-read int|null $service_requests_count
 * @property-read \App\Models\Review|null $review
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Message> $messages
 * @property-read int|null $messages_count
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|Booking newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Booking newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Booking query()
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking wherePropertyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereCheckInDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereCheckOutDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereGuests($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereTotalPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereSpecialRequests($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereUpdatedAt($value)
 * @method static \Database\Factories\BookingFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class Booking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'property_id',
        'user_id',
        'check_in_date',
        'check_out_date',
        'guests',
        'total_price',
        'status',
        'special_requests',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'guests' => 'integer',
        'total_price' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the property for this booking.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get the user who made this booking.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all service requests for this booking.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function serviceRequests(): HasMany
    {
        return $this->hasMany(ServiceRequest::class);
    }

    /**
     * Get the review for this booking.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    /**
     * Get all messages related to this booking.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}