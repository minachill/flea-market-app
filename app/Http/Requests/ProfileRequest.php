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
            'profile_image' => 'nullable|mimes:jpeg,png',
            'name'        => 'required|string|max:20',
            'postal_code' => 'required|string|regex:/^\d{3}-\d{4}$/',
            'address'     => 'required|string|max:255',
            'building'    => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'profile_image.mimes' => 'プロフィール画像はjpegまたはpng形式でアップロードしてください。',
            'name.required' => '名前を入力してください。',
            'name.max' => '名前は20文字以内で入力してください。',
            'postal_code.required' => '郵便番号を入力してください。',
            'postal_code.regex' => '郵便番号はハイフンありの8文字以内で入力してください。',
            'address.required' => '住所を入力してください。',
            'address.max' => '住所は255文字以内で入力してください。',
            'building.max' => '建物名は255文字以内で入力してください。',
        ];
    }
}
