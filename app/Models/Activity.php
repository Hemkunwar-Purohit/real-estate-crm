<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'type', 'description', 'activity_date', 'completed', 'user_id',
    ];

    protected $casts = [
        'activity_date' => 'datetime',
        'completed' => 'boolean',
    ];

    public function subject()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
