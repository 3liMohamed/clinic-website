<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:appointments,email'],
            'phone' => ['required', 'string', 'regex:/^(01[0125][0-9]{8}|\+201[0125][0-9]{8})$/'],
            'doctor_id' => ['nullable', 'exists:doctors,id'],
            'message' => ['nullable', 'string', 'max:5000'],
            'website' => ['nullable', 'string', 'max:0'],
        ];
    }
}
