<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePropertyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        if (!$user) {
            return false;
        }

        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isAccommodationOwner()) {
            $property = $this->route('property');
            return $property && $property->accommodation_owner_id === $user->accommodationOwner->id;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:2000',
            'type' => 'required|string|max:100',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'max_guests' => 'required|integer|min:1|max:50',
            'bedrooms' => 'required|integer|min:0|max:20',
            'bathrooms' => 'required|integer|min:1|max:20',
            'price_per_night' => 'required|numeric|min:0.01|max:9999999.99',
            'amenities' => 'nullable|array',
            'amenities.*' => 'string|max:100',
            'photos' => 'nullable|array',
            'photos.*' => 'string|max:500',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Property name is required.',
            'description.required' => 'Property description is required.',
            'type.required' => 'Property type is required.',
            'address.required' => 'Property address is required.',
            'city.required' => 'City is required.',
            'state.required' => 'State is required.',
            'country.required' => 'Country is required.',
            'max_guests.required' => 'Maximum number of guests is required.',
            'max_guests.min' => 'Property must accommodate at least 1 guest.',
            'bedrooms.required' => 'Number of bedrooms is required.',
            'bathrooms.required' => 'Number of bathrooms is required.',
            'bathrooms.min' => 'Property must have at least 1 bathroom.',
            'price_per_night.required' => 'Price per night is required.',
            'price_per_night.min' => 'Price must be greater than 0.',
        ];
    }
}