<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'name'        => 'required|string|max:20',
            'postal_code' => 'required|string|max:8',
            'address'     => 'required|string|max:255',
            'building'    => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '名前は必須です。',
            'name.max' => '名前は20文字以内で入力してください。',
            'postal_code.required' => '郵便番号は必須です。',
            'postal_code.max' => '郵便番号はハイフンありの8文字以内で入力してください。',
            'address.required' => '住所は必須です。',
            'address.max' => '住所は255文字以内で入力してください。',
            'building.max' => '建物名は255文字以内で入力してください。',
        ];
    }
}
