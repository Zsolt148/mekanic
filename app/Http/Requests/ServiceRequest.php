<?php

namespace App\Http\Requests;

use App\Models\Service;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServiceRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'description' => ['required', 'string'],
            'comment' => ['nullable', 'string'],
            'price' => ['required', 'numeric'],
        ];
    }
}
