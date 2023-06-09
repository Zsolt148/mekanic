<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WorksheetRequest extends FormRequest
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
            'type' => ['required'],
            'partner_id' => ['required'],
            'car_id' => ['required'],
            'done_at' => ['nullable'],
            'services' => ['required', 'array']
        ];
    }
}
