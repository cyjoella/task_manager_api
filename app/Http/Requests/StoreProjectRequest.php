<?php

namespace App\Http\Requests;

use in;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class StoreProjectRequest extends FormRequest
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
            'title' => 'sometimes|required|max:255',
            'is_done' => 'sometimes|boolean',
            'project_id' => ['nullable', Rule::in([Auth::user()->memberships->pluck('id')])],
            'tasks.*.title' => ['required', 'string' ],
            'tasks.*.is_done' => ['required',Rule::in(['0','1'])],
        ];
    }

}
