<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePostRequest extends FormRequest
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
            'id' => 'required|integer|numeric|min:1',
            'title' => 'required|string|max:255',
            'body' => 'required|array|min:1',
            'body.*' => 'required|array|min:2|max:3',
            'body.*.type' => 'required|string',
            'body.*.value' => [
                'required_if:body.*.type,text',
                'required_without:body.*.id'
            ],
            'body.*.id' => 'integer|numeric|min:1',
        ];
    }
}
