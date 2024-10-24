<?php

namespace App\Http\Requests\Chapter;

use App\Models\Story;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'story_id' => [
                'required',
                Rule::exists(Story::class, 'id'),
            ],
            'name' => 'required',
            'content' => 'required',
        ];
    }
}
