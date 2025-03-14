<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'egn' => 'required|string|size:10',
            'appointment_datetime' => 'required|date|after:now',
            'description' => 'nullable|string',
            'notification_type_id' => 'required|exists:notification_types,id',
            'email' => Rule::requiredIf(fn() => $this->notification_type_id == 1),
            'phone' => Rule::requiredIf(fn() => $this->notification_type_id == 2),
        ];
    }
}