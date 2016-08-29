<?php

namespace Wizdraw\Http\Requests;

/**
 * Class LoginRequest
 * @package Wizdraw\Http\Requests
 */
class LoginRequest extends BaseRequest
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
        // TODO: update rules
        return [
            'username' => 'required|max:255',
            'password' => 'required|min:3',
        ];
    }

}
