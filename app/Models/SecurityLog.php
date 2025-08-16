<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\SecurityLog
 *
 * @property int $id
 * @property int $property_id
 * @property int $logged_by
 * @property string $type
 * @property string $title
 * @property string $description
 * @property string $severity
 * @property bool $is_resolved
 * @property string|null $resolution_notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Property $property
 * @property-read \App\Models\User $loggedBy
 * 
 * @method static \Illuminate\Database\Eloquent\Builder|SecurityLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SecurityLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SecurityLog query()
 * @method static \Illuminate\Database\Eloquent\Builder|SecurityLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SecurityLog wherePropertyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SecurityLog whereLoggedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SecurityLog whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SecurityLog whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SecurityLog whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SecurityLog whereSeverity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SecurityLog whereIsResolved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SecurityLog whereResolutionNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SecurityLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SecurityLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SecurityLog unresolved()

 * 
 * @mixin \Eloquent
 */
class SecurityLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'property_id',
        'logged_by',
        'type',
        'title',
        'description',
        'severity',
        'is_resolved',
        'resolution_notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_resolved' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the property for this security log.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Get the user who logged this security event.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function loggedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'logged_by');
    }

    /**
     * Scope a query to only include unresolved security logs.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnresolved($query)
    {
        return $query->where('is_resolved', false);
    }
}