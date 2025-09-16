<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
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
            'shipping_postal'   => 'required|string|max:8',
            'shipping_address'  => 'required|string|max:255',
            'shipping_building' => 'nullable|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'shipping_postal.required'  => '郵便番号は必須です。',
            'shipping_postal.max'       => '郵便番号はハイフンありの8文字以内で入力してください。',
            'shipping_address.required' => '住所は必須です。',
            'shipping_address.max'      => '住所は255文字以内で入力してください。',
            'shipping_building.max'     => '建物名は255文字以内で入力してください。',
        ];
    }
}
