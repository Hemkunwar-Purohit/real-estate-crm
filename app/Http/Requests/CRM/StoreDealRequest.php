<?php

namespace App\Http\Requests\CRM;

use Illuminate\Foundation\Http\FormRequest;

class StoreDealRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:200',
            'stage' => 'required|string',
            'deal_value' => 'required|numeric|min:0',
            'currency' => 'required|string|max:5',
            'commission' => 'nullable|numeric|min:0',
            'client_id' => 'required|exists:clients,id',
            'property_id' => 'nullable|exists:properties,id',
            'lead_id' => 'nullable|exists:leads,id',
            'assigned_to' => 'required|exists:users,id',
            'expected_close_date' => 'nullable|date',
            'notes' => 'nullable|string|max:1000',
        ];
    }
}
