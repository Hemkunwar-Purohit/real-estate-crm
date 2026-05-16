<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Client extends Model
{
    use LogsActivity, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'phone', 'alternate_phone',
        'type', 'city', 'address', 'source', 'status',
        'notes', 'assigned_to',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logAll();
    }

    public function assignedAgent()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function deals()
    {
        return $this->hasMany(Deal::class);
    }

    public function properties()
    {
        return $this->hasMany(Property::class, 'owner_id');
    }

    public function activities()
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
