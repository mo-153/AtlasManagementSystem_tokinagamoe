<?php

namespace App\Http\Requests\BulletinBoard;

use Illuminate\Foundation\Http\FormRequest;

class PostFormRequest extends FormRequest
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
        $rules = [
            'post_title' => 'required|string|max:100',
            'post_body' => 'required|string|max:2000',

            'main_category_name' =>'required|max:100|string|unique:main_categories,main_category',
            'maine_category_id' =>'required|exists:main_categories,id',
            'sub_category_name' => 'required|max:100|string|unique:sub_categories,sub_category',
    // →'unique:テーブル名(main_categories),カラム名(main_category)

        ];
        return $rules;
    }

    public function messages(){
        return [
            'post_title.required' => 'タイトルは必ず入力してください。',
            'post_title.string' => 'タイトルは文字列である必要があります。',
            'post_title.max' => 'タイトルは100文字以内で入力してください。',
            'post_body.required' => '内容は必ず入力してください。',
            'post_body.string' => '内容は文字列である必要があります。',
            'post_body.max' => '最大文字数は2000文字です。',

            'main_category_name.required' => 'メインカテゴリーは入力必須です。',
            'main_category_name.max' => '100文字以内で入力してください。',
            'main_category_name.unique' => 'そのメインカテゴリーは既に登録されています。',

            'maine_category_id.required'=>'メインカテゴリーは入力必須です。',
            'maine_category_id.exists:main_categories,id'=>'登録されたメインカテゴリーと一致しません',

            'sub_category_name.required'=>'サブカテゴリーは入力必須です。',
            'sub_category_name.max'=>'100文字以内で入力してください。',
            'sub_category_name.unique'=>'そのサブカテゴリーはすでに登録できません。',
        ];
    }
}
