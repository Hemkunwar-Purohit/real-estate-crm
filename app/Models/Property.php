<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'property_type', 'status', 'listing_type',
        'price', 'currency', 'area', 'bedrooms', 'bathrooms',
        'floor', 'city', 'locality', 'address', 'description',
        'rera_number', 'owner_id', 'added_by',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function owner()
    {
        return $this->belongsTo(Client::class, 'owner_id');
    }

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    public function deals()
    {
        return $this->hasMany(Deal::class);
    }

    public function siteVisits()
    {
        return $this->hasMany(SiteVisit::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeForSale($query)
    {
        return $query->where('listing_type', 'sale');
    }

    public function scopeForRent($query)
    {
        return $query->where('listing_type', 'rent');
    }

    public function getFormattedPriceAttribute(): string
    {
        return $this->currency.' '.number_format($this->price);
    }
}
