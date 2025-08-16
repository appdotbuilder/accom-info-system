<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\ServiceRequest
 *
 * @property int $id
 * @property int $booking_id
 * @property string $type
 * @property string $title
 * @property string $description
 * @property string $priority
 * @property string $status
 * @property int|null $assigned_to
 * @property string|null $resolution_notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Booking $booking
 * @property-read \App\Models\User|null $assignedTo
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceRequest whereBookingId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceRequest whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceRequest whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceRequest whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceRequest wherePriority($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceRequest whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceRequest whereAssignedTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceRequest whereResolutionNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ServiceRequest whereUpdatedAt($value)
 * @method static \Database\Factories\ServiceRequestFactory factory($count = null, $state = [])
 * 
 * @mixin \Eloquent
 */
class ServiceRequest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'booking_id',
        'type',
        'title',
        'description',
        'priority',
        'status',
        'assigned_to',
        'resolution_notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the booking for this service request.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the user assigned to this service request.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}