<?php

return [

    'app_name' => 'RealEstateCRM',
    'version' => '1.0.0',

    // Property types
    'property_types' => [
        'apartment' => 'Apartment',
        'villa' => 'Villa',
        'plot' => 'Plot / Land',
        'office' => 'Commercial Office',
        'shop' => 'Shop',
        'warehouse' => 'Warehouse',
    ],

    // Deal pipeline stages
    'pipeline_stages' => [
        'new' => 'New Inquiry',
        'site_visit' => 'Site Visit Scheduled',
        'negotiation' => 'Negotiation',
        'docs_pending' => 'Documents Pending',
        'won' => 'Deal Won',
        'lost' => 'Deal Lost',
    ],

    // Lead sources
    'lead_sources' => [
        'website' => 'Website',
        'referral' => 'Referral',
        'facebook' => 'Facebook Ads',
        'google' => 'Google Ads',
        'magicbricks' => 'MagicBricks',
        '99acres' => '99acres',
        'housing' => 'Housing.com',
        'walk_in' => 'Walk In',
        'other' => 'Other',
    ],

    // Property status
    'property_status' => [
        'available' => 'Available',
        'reserved' => 'Reserved',
        'sold' => 'Sold',
        'rented' => 'Rented',
    ],

    'currencies' => ['INR', 'USD', 'AED', 'GBP'],
    'default_currency' => 'INR',
    'per_page' => 25,

];
