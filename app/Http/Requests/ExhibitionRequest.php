<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
            'name'        => 'required|string|max:255',              // 商品名：必須
            'description' => 'required|string|max:255',              // 商品説明：必須、255文字以内
            'image'       => 'required|mimes:jpeg,png',        // 商品画像：必須、jpeg/pngのみ
            'categories'  => 'required|min:1',                 // カテゴリ：必須、複数選択OK
            'condition'   => 'required|integer|min:1|max:4',         // 商品状態：必須、1〜4の数値
            'price'       => 'required|integer|min:0',               // 価格：必須、整数、0円以上
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'        => '商品名を入力してください',
            'name.max'             => '商品名は255文字以内で入力してください',
            'description.required' => '商品説明を入力してください',
            'description.max'      => '商品説明は255文字以内で入力してください',
            'image.required'       => '商品画像をアップロードしてください',
            'image.mimes'          => '商品画像はjpegまたはpng形式でアップロードしてください',
            'categories.required'  => 'カテゴリを選択してください',
            'categories.min'       => 'カテゴリを1つ以上選択してください',
            'condition.required'   => '商品の状態を選択してください',
            'condition.integer'    => '商品の状態は数値で入力してください',
            'condition.min'        => '商品の状態を選択してください',
            'condition.max'        => '商品の状態を選択してください',
            'price.required'       => '価格を入力してください',
            'price.integer'        => '価格は整数で入力してください',
            'price.min'            => '価格は0円以上で入力してください',
        ];
    }
}
