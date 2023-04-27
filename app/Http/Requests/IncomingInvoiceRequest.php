<?php

namespace App\Http\Requests;

use App\Models\IncomingInvoice;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IncomingInvoiceRequest extends FormRequest
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
            'created_by' => ['required', 'exists:admins,id'],
            'id_number' => ['required', 'string'],
            'subject' => ['required', 'string'],
            'net' => ['required', 'numeric'],
            'gross' => ['required', 'numeric'],
            'payment_mode' => ['required', Rule::in(array_keys(IncomingInvoice::PAYMENT_MODES))],
            'issuer' => ['required', 'array'],
            'type' => ['required', 'array'],
            'fulfillment_date' => ['required', 'date'],
            'expiration_date' => ['required', 'date'],
        ];
    }
}
