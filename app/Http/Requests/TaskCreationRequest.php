<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskCreationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'dueDate' => ['required'],
            'assignee_id' => ['required', 'exists:users,id'],
        ];
    }

    public function attributes() {
        return [
            'name' => 'Task name',
            'description' => 'Description',
            'dueDate' => 'Due date',
            'assignee_id' => 'Assignee',
        ];
    }
}
