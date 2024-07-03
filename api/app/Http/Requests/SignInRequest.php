<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignInRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
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
            'email' => 'required|max:256|email:dns|regex:' . config('const.email_dns_regex'),
            'password' => 'required|max:20',
        ];
    }


    /**
     * change display default attributes
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'email' => 'メールアドレス',
            'password' => 'パスワード',
            'refreshToken' => 'リフレッシュトークン',
        ];
    }

    /**
     * Custom messages
     *
     * @return array
     */
    public function messages()
    {
        return [
            'required_if' => ':attributeは、必ず指定してください。',
            'email.regex' => trans('validation.email'),
        ];
    }

}