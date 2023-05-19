<?php

namespace App\Http\Requests;

use App\Models\Car;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CarRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'created_by' => ['exists:admins,id'],
            'partner_id' => ['nullable', 'exists:partners,id'],
            'name' => ['string'],
            'zip' => ['string'],
            'city' => ['string'],
            'address' => ['string'],
            'tax_number' => ['nullable', 'string'],
            'communal_tax_number' => ['nullable', 'string'],

            'brand' => ['string'],
            'type' => ['string'],
            'license_plate' => ['string'],
            'mileage' => ['numeric'],
            'chassis_number' => ['string'],
            'motor_number' => ['string'],
            'vintage' => ['numeric'],
            'ti_validity' => ['date'],
        ];
    }
}
