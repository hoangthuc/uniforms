<?php

namespace App\Http\Requests\auth;

use Illuminate\Foundation\Http\FormRequest;

class CheckFormRequest extends FormRequest
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
            'password_u' => 'required|min:5|max:255',
            'password_comfirm' => 'required|confirmed|min:5',
        ];
;
    }

    public function messages()
    {
        return [
            'password_u.required' => ':attribute not required',
            'password_comfirm.required' => ':attribute not required',
            'password_u.min' => ':attribute Not to be smaller :min',
            'password_u.max' => ':attribute Not bigger :max',
            'password_comfirm.confirmed' => 'Password Confirmation should match the Password',
        ];
    }
}
