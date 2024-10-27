<?php

namespace App\Http\Requests\Story;

use App\Enums\StoryLevelEnum;
use App\Enums\StoryStatusEnum;
use App\Models\Category;
use App\Models\Story;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\RequiredIf;

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
            'id' => [
                'required',
                'integer',
            ],
            'categories' => [
                'required',
                Rule::exists(Category::class, 'id'),
            ],
            'name' => [
                'required',
                'string',
                Rule::unique(Story::class, 'name')->ignore($this->id),
            ],
            'status' => [
                'required',
                Rule::in(StoryStatusEnum::asArray()),
            ],
            'level' => [
                'required',
                Rule::in(StoryLevelEnum::asArray()),
            ],
            'author' => [
                'required',
                'string',
            ],
            'author_2' => [
                new RequiredIf($this->level === (string)StoryLevelEnum::EDITOR),
            ],
            'descriptions' => [
                'required',
                'string',
            ],
            'image' => [
                'nullable',
                'filled',
            ],
            'image_old' => [
                'required',
            ],
        ];
    }
}
