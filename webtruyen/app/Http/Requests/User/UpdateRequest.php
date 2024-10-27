<?php

namespace App\Http\Requests\User;

use App\Enums\UserGenderEnum;
use App\Models\User;
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
            'name' => [
                'required',
                'string',
                Rule::unique(User::class, 'name')->ignore($this->id),
            ],
            'gender' => [
                'required',
                Rule::in(UserGenderEnum::asArray()),
            ],
            'email' => [
                'required',
                'email',
                Rule::unique(User::class, 'email')->ignore($this->id),
            ],
        ];
    }
}
