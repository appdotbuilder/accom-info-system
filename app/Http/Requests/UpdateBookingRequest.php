<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class UpdateBookingRequest extends FormRequest
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

        $booking = $this->route('booking');
        
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->isCustomer() && $booking->user_id === $user->id) {
            return true;
        }

        if ($user->isAccommodationOwner() && $booking->property->accommodation_owner_id === $user->accommodationOwner->id) {
            return true;
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
        $rules = [
            'check_in_date' => 'sometimes|required|date|after:today',
            'check_out_date' => 'sometimes|required|date|after:check_in_date',
            'guests' => 'sometimes|required|integer|min:1',
            'special_requests' => 'nullable|string|max:1000',
        ];

        // Allow status updates for accommodation owners and admins
        if ($this->user()->isAccommodationOwner() || $this->user()->isAdmin()) {
            $rules['status'] = 'sometimes|required|in:pending,confirmed,checked_in,checked_out,cancelled';
        }

        return $rules;
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
            if ($this->has(['check_in_date', 'check_out_date', 'guests'])) {
                $booking = $this->route('booking');
                $property = $booking->property;
                
                // Check if guests exceed property capacity
                $guests = $this->guests ?? $booking->guests;
                if ($guests > $property->max_guests) {
                    $validator->errors()->add('guests', "This property can accommodate a maximum of {$property->max_guests} guests.");
                }

                // Check for overlapping bookings (excluding current booking)
                if ($this->has(['check_in_date', 'check_out_date'])) {
                    $checkIn = Carbon::parse($this->check_in_date ?? $booking->check_in_date);
                    $checkOut = Carbon::parse($this->check_out_date ?? $booking->check_out_date);
                    
                    $overlappingBookings = $property->bookings()
                        ->where('id', '!=', $booking->id)
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
            'check_in_date.after' => 'Check-in date must be after today.',
            'check_out_date.after' => 'Check-out date must be after check-in date.',
            'guests.min' => 'At least 1 guest is required.',
            'status.in' => 'Invalid booking status.',
        ];
    }
}