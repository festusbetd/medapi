<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterAuthRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'id_number' => 'required|string|max:8|min:8|unique:users',
            'role' => 'required|string|max:1',
            'tel' => 'required|string|min:10|max:10|unique:users',
            'job_number' => 'required|string|min:3|max:3|unique:users',
            'password' => 'required|string|min:4',
        ];
    }
}