<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TaskRequest extends FormRequest
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
            'title' => 'string|required',
            'description' => 'string',
            'status' => Rule::in(['not_started', 'in_progress', 'ready_for_test', 'completed']),
            'project_id' => 'required|exists:projects,id',
            'user_id' => 'required|exists:users,id'
        ];
    }
}
