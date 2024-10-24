<?php

namespace App\Http\Requests\User;

use App\Enums\UserGenderEnum;
use App\Models\Level;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
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
            ],
            'email' => [
                'required',
                'email',
                Rule::unique(User::class, 'email'),
            ],
            'password' => [
                'required',
                'string',
            ],
            'gender' => [
                'required',
                Rule::in(UserGenderEnum::asArray()),
            ],
            'level_id' => [
                'required',
                Rule::exists(Level::class, 'id'),
            ],
            'avatar' => [
                'nullable',
                'file',
                'image',
            ],
        ];
    }
}
