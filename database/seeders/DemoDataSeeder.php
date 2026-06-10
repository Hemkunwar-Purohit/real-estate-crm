<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Deal;
use App\Models\Lead;
use App\Models\User;
use App\Models\Property;
use App\Models\SiteVisit;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // $agent = User::first();
        $agent = User::where('email', 'admin@demo.com')->first();

        // =====================
        // LEADS
        // =====================
        $leads = [
            ['name' => 'Rahul Sharma',  'phone' => '9876543210', 'source' => 'magicbricks', 'listing_type' => 'buy',  'status' => 'new',       'preferred_city' => 'Mumbai', 'budget_min' => 5000000,  'budget_max' => 8000000],
            ['name' => 'Priya Patel',   'phone' => '9876543211', 'source' => 'google',       'listing_type' => 'rent', 'status' => 'contacted', 'preferred_city' => 'Pune',   'budget_min' => 15000,    'budget_max' => 25000],
            ['name' => 'Amit Gupta',    'phone' => '9876543212', 'source' => 'referral',     'listing_type' => 'buy',  'status' => 'qualified', 'preferred_city' => 'Delhi',  'budget_min' => 10000000, 'budget_max' => 15000000],
            ['name' => 'Sunita Verma',  'phone' => '9876543213', 'source' => '99acres',      'listing_type' => 'buy',  'status' => 'new',       'preferred_city' => 'Indore', 'budget_min' => 3000000,  'budget_max' => 5000000],
            ['name' => 'Ravi Kumar',    'phone' => '9876543214', 'source' => 'facebook',     'listing_type' => 'rent', 'status' => 'new',       'preferred_city' => 'Bhopal', 'budget_min' => 10000,    'budget_max' => 20000],
            ['name' => 'Neha Singh',    'phone' => '9876543215', 'source' => 'housing',      'listing_type' => 'buy',  'status' => 'contacted', 'preferred_city' => 'Mumbai', 'budget_min' => 7000000,  'budget_max' => 12000000],
            ['name' => 'Vikram Joshi',  'phone' => '9876543216', 'source' => 'walk_in',      'listing_type' => 'buy',  'status' => 'qualified', 'preferred_city' => 'Indore', 'budget_min' => 4000000,  'budget_max' => 6000000],
            ['name' => 'Kavita Mishra', 'phone' => '9876543217', 'source' => 'website',      'listing_type' => 'rent', 'status' => 'new',       'preferred_city' => 'Pune',   'budget_min' => 20000,    'budget_max' => 35000],
        ];

        foreach ($leads as $lead) {
            Lead::create(array_merge($lead, [
                'assigned_to' => $agent->id,
                'property_type' => 'apartment',
                'email' => strtolower(str_replace(' ', '.', $lead['name'])).'@gmail.com',
            ]));
        }

        $leadNames = [
    'Arjun Mehta','Pooja Jain','Karan Shah','Sneha Kapoor',
    'Mohit Yadav','Anjali Gupta','Rohit Verma','Nidhi Sharma',
    'Sanjay Patel','Ritika Singh','Harsh Agrawal','Komal Joshi',
    'Abhishek Mishra','Shweta Tiwari','Manoj Soni','Aarti Dubey',
    'Yash Malhotra','Rohan Bansal','Divya Chouhan','Tarun Jain',
];

foreach ($leadNames as $name) {

    Lead::create([
        'name' => $name,
        'phone' => '98'.rand(10000000,99999999),
        'email' => strtolower(str_replace(' ','.', $name)).'@gmail.com',
        'source' => collect([
            'google',
            'facebook',
            'website',
            '99acres',
            'magicbricks',
            'housing',
            'referral'
        ])->random(),
        'listing_type' => collect(['buy','rent'])->random(),
        'status' => collect([
            'new',
            'contacted',
            'qualified'
        ])->random(),
        'preferred_city' => collect([
            'Mumbai',
            'Pune',
            'Delhi',
            'Indore',
            'Bangalore',
            'Hyderabad'
        ])->random(),
        'budget_min' => rand(1000000,5000000),
        'budget_max' => rand(6000000,25000000),
        'property_type' => collect([
            'apartment',
            'villa',
            'plot',
            'office'
        ])->random(),
        'assigned_to' => $agent->id,
    ]);
}

        // =====================
        // CLIENTS
        // =====================
        $clientsData = [
            ['name' => 'Suresh Agarwal', 'phone' => '9811111111', 'type' => 'buyer',    'city' => 'Mumbai', 'source' => 'referral'],
            ['name' => 'Meena Sharma',   'phone' => '9822222222', 'type' => 'seller',   'city' => 'Delhi',  'source' => 'website'],
            ['name' => 'Deepak Verma',   'phone' => '9833333333', 'type' => 'buyer',    'city' => 'Pune',   'source' => 'google'],
            ['name' => 'Anita Joshi',    'phone' => '9844444444', 'type' => 'landlord', 'city' => 'Indore', 'source' => 'walk_in'],
            ['name' => 'Raj Malhotra',   'phone' => '9855555555', 'type' => 'buyer',    'city' => 'Mumbai', 'source' => 'magicbricks'],
        ];

        foreach ($clientsData as $clientData) {
            Client::create(array_merge($clientData, [
                'assigned_to' => $agent->id,
                'status' => 'active',
                'email' => strtolower(str_replace(' ', '.', $clientData['name'])).'@gmail.com',
            ]));
        }

        for ($i = 1; $i <= 15; $i++) {

    Client::create([
        'name' => "Client {$i}",
        'phone' => '97'.rand(10000000,99999999),
        'email' => "client{$i}@demo.com",
        'type' => collect([
            'buyer',
            'seller',
            'landlord'
        ])->random(),
        'city' => collect([
            'Mumbai',
            'Pune',
            'Delhi',
            'Indore',
            'Bangalore'
        ])->random(),
        'source' => collect([
            'google',
            'website',
            'referral',
            'walk_in',
            'magicbricks'
        ])->random(),
        'status' => 'active',
        'assigned_to' => $agent->id,
    ]);
}

// =====================
// PROPERTIES
// =====================

$clients = Client::all();

$propertiesData = [
    [
        'title' => '3BHK Premium Apartment',
        'property_type' => 'apartment',
        'status' => 'available',
        'listing_type' => 'sale',
        'price' => 8500000,
        'city' => 'Mumbai',
        'locality' => 'Andheri West',
    ],
    [
        'title' => 'Luxury Villa',
        'property_type' => 'villa',
        'status' => 'available',
        'listing_type' => 'sale',
        'price' => 25000000,
        'city' => 'Pune',
        'locality' => 'Baner',
    ],
    [
        'title' => 'Commercial Office',
        'property_type' => 'office',
        'status' => 'available',
        'listing_type' => 'rent',
        'price' => 75000,
        'city' => 'Indore',
        'locality' => 'Vijay Nagar',
    ],
];

// 3 Premium Demo Properties
foreach ($propertiesData as $property) {

    Property::create(array_merge($property, [
        'currency' => 'INR',
        'area' => rand(800,2500),
        'bedrooms' => rand(2,5),
        'bathrooms' => rand(1,4),
        'floor' => rand(1,15),
        'address' => 'Demo Address',
        'description' => 'Premium demo property',
        'rera_number' => 'RERA'.rand(10000,99999),
        'owner_id' => $clients->random()->id,
        'added_by' => $agent->id,
    ]));
}

// 15 Random Properties
$propertyTypes = ['apartment','villa','plot','office','shop'];
$cities = ['Mumbai','Pune','Delhi','Indore','Bangalore'];

for ($i = 1; $i <= 15; $i++) {

    Property::create([
        'title' => "Property {$i}",
        'property_type' => collect($propertyTypes)->random(),
        'status' => collect(['available','sold','rented'])->random(),
        'listing_type' => collect(['sale','rent'])->random(),
        'price' => rand(1500000,30000000),
        'currency' => 'INR',
        'area' => rand(600,4000),
        'bedrooms' => rand(1,5),
        'bathrooms' => rand(1,4),
        'floor' => rand(1,20),
        'city' => collect($cities)->random(),
        'locality' => 'Prime Location',
        'address' => 'Demo Address',
        'description' => 'Sample demo property for showcase.',
        'rera_number' => 'RERA'.rand(10000,99999),
        'owner_id' => $clients->random()->id,
        'added_by' => $agent->id,
    ]);
}
// =====================
// SITE VISITS
// =====================

$properties = Property::all();
$leads = Lead::all();

for ($i = 1; $i <= 15; $i++) {

    SiteVisit::create([
        'lead_id' => $leads->random()->id,
        'property_id' => $properties->random()->id,
        'agent_id' => $agent->id,
        'visit_datetime' => now()->addDays(rand(-15,15)),
        'status' => collect([
            'scheduled',
            'completed',
            'cancelled'
        ])->random(),
        'feedback' => collect([
            'Interested in property',
            'Needs negotiation',
            'Will discuss with family',
            'Follow up required',
            'Budget mismatch'
        ])->random(),
        'interest_level' => collect([
            'low',
            'medium',
            'high'
        ])->random(),
    ]);
}

        // =====================
        // DEALS
        // =====================
        $clients = Client::all();
        $stages = array_keys(config('crm.pipeline_stages'));

        $dealsData = [
            ['title' => '3BHK Sale — Bandra West',      'deal_value' => 12000000, 'stage' => 'negotiation',  'commission' => 240000],
            ['title' => '2BHK Rent — Koregaon Park',    'deal_value' => 35000,    'stage' => 'site_visit',   'commission' => 35000],
            ['title' => 'Villa Purchase — South Delhi',  'deal_value' => 25000000, 'stage' => 'docs_pending', 'commission' => 500000],
            ['title' => 'Office Space — Indore',         'deal_value' => 8000000,  'stage' => 'new',          'commission' => 160000],
            ['title' => '1BHK Rent — Bhopal',           'deal_value' => 15000,    'stage' => 'won',          'commission' => 15000],
            ['title' => 'Plot Sale — Indore Ring Road',  'deal_value' => 5000000,  'stage' => 'negotiation',  'commission' => 100000],
            ['title' => '4BHK Flat — Mumbai Suburbs',   'deal_value' => 18000000, 'stage' => 'site_visit',   'commission' => 360000],
            ['title' => 'Shop — MG Road Indore',         'deal_value' => 3500000,  'stage' => 'new',          'commission' => 70000],
            ['title' => '2BHK Sale — Pune Wakad',       'deal_value' => 7500000,  'stage' => 'won',          'commission' => 150000],
            ['title' => 'Warehouse — Bhiwandi',          'deal_value' => 15000000, 'stage' => 'lost',         'commission' => 0],
        ];

        foreach ($dealsData as $deal) {
            Deal::create(array_merge($deal, [
                'currency' => 'INR',
                'client_id' => $clients->random()->id,
                'assigned_to' => $agent->id,
                'expected_close_date' => now()->addDays(rand(10, 90)),
                'actual_close_date' => in_array($deal['stage'], ['won', 'lost']) ? now()->subDays(rand(1, 30)) : null,
            ]));
        }

        for ($i = 1; $i <= 15; $i++) {

    $stage = collect([
        'new',
        'site_visit',
        'negotiation',
        'docs_pending',
        'won',
        'lost'
    ])->random();

    Deal::create([
        'title' => "Property Deal {$i}",
        'deal_value' => rand(2000000,30000000),
        'commission' => rand(50000,500000),
        'stage' => $stage,
        'currency' => 'INR',
        'client_id' => $clients->random()->id,
        'assigned_to' => $agent->id,
        'expected_close_date' => now()->addDays(rand(5,90)),
        'actual_close_date' => in_array($stage,['won','lost'])
            ? now()->subDays(rand(1,30))
            : null,
    ]);
}
    }
}
