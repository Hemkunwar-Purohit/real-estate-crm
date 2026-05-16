<?php

namespace App\Http\Requests\CRM;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'source' => 'nullable|string',
            'status' => 'nullable|string',
            'property_type' => 'nullable|string',
            'listing_type' => 'required|in:buy,rent',
            'budget_min' => 'nullable|numeric',
            'budget_max' => 'nullable|numeric',
            'preferred_city' => 'nullable|string|max:100',
            'preferred_locality' => 'nullable|string|max:100',
            'requirements' => 'nullable|string|max:1000',
            'assigned_to' => 'nullable|exists:users,id',
        ];
    }
}
