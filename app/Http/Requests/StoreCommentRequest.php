<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
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
            'body' => 'required|string',
            'id_reply_to' => 'integer|numeric|min:1|exists:comments,id',
            'user_name' => 'string',
            'email' => 'email|string',
            'id_post' => 'required|integer|numeric|min:1|exists:posts,id',
        ];
    }
}
