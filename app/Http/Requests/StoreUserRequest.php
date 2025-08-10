<?php

namespace App\Http\Requests;

use App\Enums\UserTitles;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'prefixname' => ['nullable', 'string', 'max:255', Rule::enum(UserTitles::class)],
            'firstname' => ['required', 'string', 'max:255'],
            'middlename' => ['nullable', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'remember_token' => ['nullable', 'string', 'max:100'],
            'email_verified_at' => ['nullable', 'date'],
        ];
    }

    /**
     * Customize the error messages.
     */
    public function messages(): array
    {
        return [
            'prefixname.enum' => 'Prefix name must be one of: '.implode(', ', UserTitles::values()).'.',
            'firstname.required' => 'First name is required.',
            'lastname.required' => 'Last name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Email must be a valid email address.',
            'email.unique' => 'This email is already in use.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least :min characters long.',
        ];
    }
}
