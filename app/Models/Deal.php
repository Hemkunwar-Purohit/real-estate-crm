<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Deal extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'stage', 'deal_value', 'currency',
        'commission', 'client_id', 'property_id',
        'lead_id', 'assigned_to', 'expected_close_date',
        'actual_close_date', 'notes',
    ];

    protected $casts = [
        'deal_value' => 'decimal:2',
        'commission' => 'decimal:2',
        'expected_close_date' => 'date',
        'actual_close_date' => 'date',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }

    public function assignedAgent()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function activities()
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    public function scopeWon($query)
    {
        return $query->where('stage', 'won');
    }

    public function scopeActive($query)
    {
        return $query->whereNotIn('stage', ['won', 'lost']);
    }

    public function isWon(): bool
    {
        return $this->stage === 'won';
    }
}
