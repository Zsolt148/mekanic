<?php

namespace App\Http\Requests;

use App\Models\Invoice;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InvoiceRequest extends FormRequest
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
            'partner_id' => ['nullable', 'exists:partners,id'],
            'name' => ['required', 'string'],
            'zip' => ['required', 'string'],
            'city' => ['required', 'string'],
            'address' => ['required', 'string'],
            'tax_number' => ['nullable', 'string'],
            'communal_tax_number' => ['nullable', 'string'],
            'order_number' => ['required', 'string'],
            'payment_mode' => ['required', Rule::in(array_keys(Invoice::PAYMENT_MODES))],
            'issue_date' => ['required', 'date'],
            'fulfillment_date' => ['required', 'date'],
            'expiration_date' => ['required', 'date'],
            'net' => ['required', 'numeric'],
            'tax' => ['required', 'numeric'],
            'gross' => ['required', 'numeric'],
            'comment' => ['nullable', 'string'],
            'invoice_items' => ['required', 'array'],
            'invoice_items.*.name' => ['required', 'string'],
            'invoice_items.*.quantity' => ['required', 'numeric'],
            'invoice_items.*.quantity_unit' => ['required', 'array'],
            'invoice_items.*.unit_price' => ['required', 'numeric'],
            'invoice_items.*.tax_percent' => ['required', 'numeric'],
            'invoice_items.*.net' => ['required', 'numeric'],
            'invoice_items.*.tax' => ['required', 'numeric'],
            'invoice_items.*.gross' => ['required', 'numeric'],
        ];
    }
}
