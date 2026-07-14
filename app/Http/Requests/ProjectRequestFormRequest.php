<?php

namespace App\Http\Requests;

use App\Models\ProjectRequest;
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
            'timeline' => ['required', Rule::in(ProjectRequest::TIMELINES)],
            'description' => ['required', 'string', 'max:5000'],
        ];
    }
}
