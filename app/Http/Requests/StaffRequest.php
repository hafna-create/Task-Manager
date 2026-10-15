<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\Staff;
use Illuminate\Validation\Rule;

class StaffRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('staff', 'email')->ignore($this->route('staff'))],
            'password' => [$this->isMethod('post') ? 'required' : 'nullable', 'string', 'min:8'],
            'is_active' => ['nullable', 'boolean'],
            'verified' => ['nullable', 'boolean'],
        ];
    }

    public function payload(?Staff $staff = null): array
    {
        $data = $this->validated();
        $data['is_active'] = $this->boolean('is_active');
        $data['email_verified_at'] = $this->boolean('verified') ? ($staff?->email_verified_at ?? now()) : null;
        if (empty($data['password'])) unset($data['password']);
        unset($data['verified']);
        return $data;
    }
}
