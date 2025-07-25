<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'fullname'     => 'required|string|max:255',
            'username'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'location_id' => 'required|exists:locations',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'in:admin,user',
            'image' =>'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120'
        ];
    }
}
