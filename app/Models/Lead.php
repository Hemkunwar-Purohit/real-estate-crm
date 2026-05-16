<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Lead extends Model
{
    use LogsActivity, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'phone', 'source', 'status',
        'property_type', 'listing_type', 'budget_min', 'budget_max',
        'preferred_city', 'preferred_locality', 'requirements',
        'assigned_to', 'converted_client_id', 'is_converted',
    ];

    protected $casts = [
        'is_converted' => 'boolean',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }

    public function assignedAgent()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function convertedClient()
    {
        return $this->belongsTo(Client::class, 'converted_client_id');
    }

    public function siteVisits()
    {
        return $this->hasMany(SiteVisit::class);
    }

    public function activities()
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    public function deals()
    {
        return $this->hasMany(Deal::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_converted', false);
    }
}
