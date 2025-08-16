<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->isCustomer();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'property_id' => 'required|exists:properties,id',
            'check_in_date' => 'required|date|after:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'guests' => 'required|integer|min:1',
            'special_requests' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->has(['property_id', 'check_in_date', 'check_out_date', 'guests'])) {
                $property = \App\Models\Property::find($this->property_id);
                
                if ($property) {
                    // Check if guests exceed property capacity
                    if ($this->guests > $property->max_guests) {
                        $validator->errors()->add('guests', "This property can accommodate a maximum of {$property->max_guests} guests.");
                    }

                    // Check for overlapping bookings
                    $checkIn = Carbon::parse($this->check_in_date);
                    $checkOut = Carbon::parse($this->check_out_date);
                    
                    $overlappingBookings = $property->bookings()
                        ->where('status', '!=', 'cancelled')
                        ->where(function ($query) use ($checkIn, $checkOut) {
                            $query->where(function ($q) use ($checkIn) {
                                $q->where('check_in_date', '<=', $checkIn)
                                  ->where('check_out_date', '>', $checkIn);
                            })->orWhere(function ($q) use ($checkOut) {
                                $q->where('check_in_date', '<', $checkOut)
                                  ->where('check_out_date', '>=', $checkOut);
                            })->orWhere(function ($q) use ($checkIn, $checkOut) {
                                $q->where('check_in_date', '>=', $checkIn)
                                  ->where('check_out_date', '<=', $checkOut);
                            });
                        })
                        ->exists();

                    if ($overlappingBookings) {
                        $validator->errors()->add('check_in_date', 'The selected dates are not available.');
                    }
                }
            }
        });
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'property_id.required' => 'Property is required.',
            'property_id.exists' => 'Selected property does not exist.',
            'check_in_date.required' => 'Check-in date is required.',
            'check_in_date.after' => 'Check-in date must be after today.',
            'check_out_date.required' => 'Check-out date is required.',
            'check_out_date.after' => 'Check-out date must be after check-in date.',
            'guests.required' => 'Number of guests is required.',
            'guests.min' => 'At least 1 guest is required.',
        ];
    }
}