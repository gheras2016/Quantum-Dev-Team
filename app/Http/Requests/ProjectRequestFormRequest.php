<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectRequestFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'whatsapp' => ['nullable', 'string', 'max:50'],
            'project_type' => ['required', Rule::in(['web_app', 'mobile_app', 'system', 'other'])],
            'budget_range' => ['required', Rule::in(['under_5k', '5k_10k', '10k_25k', '25k_50k', 'over_50k'])],
            'description' => ['required', 'string', 'max:5000'],
        ];
    }
}
