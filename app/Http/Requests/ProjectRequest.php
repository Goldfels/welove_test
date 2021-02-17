<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Status;

class ProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // User system not implemented
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
            'title' => 'required|max:150',
            'description' => 'required|max:16777215',
            'status' => 'required|integer|gte:1|lte:' . Status::count(),
            'owner_name' => 'required|max:150',
            'owner_email' => 'required|email|max:150',
        ];
    }
}
