<?php

namespace App\Http\Requests\CRM;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
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
            'email' => 'nullable|email|max:255|unique:clients,email,'.$this->client->id,
            'alternate_phone' => 'nullable|string|max:20',
            'type' => 'required|in:buyer,seller,tenant,landlord',
            'city' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:500',
            'source' => 'nullable|string',
            'notes' => 'nullable|string|max:1000',
            'assigned_to' => 'nullable|exists:users,id',
        ];
    }
}
