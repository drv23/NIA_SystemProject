<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'fullname' => 'sometimes|string|max:255',
            'username' => 'sometimes|string|max:255|unique:users,username,' . $this->user,
            'email' => 'sometimes|email|max:255|unique:users,email,' . $this->user,
            'role' => 'sometimes|string|in:admin,user',
            'location_id' => 'sometimes|exists:locations,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
            'password' => 'sometimes|string|min:8|confirmed',
            'password_confirmation' => 'sometimes|required_with:password'
        ];
    }
}
