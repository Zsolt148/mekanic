<?php

namespace App\Http\Requests;

use App\Models\Checkout;
use App\Models\IncomingInvoice;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CheckoutRequest extends FormRequest
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
            'receipt_number' => ['required', 'string'],
            'claimant' => ['required', 'string'],
            'amount' => ['required', 'numeric'],
            'type' => ['required', Rule::in(array_keys(Checkout::TYPE))],
            'title' => ['required', 'array'],
            'date' => ['required', 'date'],
        ];
    }
}
