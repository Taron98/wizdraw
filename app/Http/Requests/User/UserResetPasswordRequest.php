<?php

namespace Wizdraw\Http\Requests\User;

use Wizdraw\Http\Requests\AbstractRequest;

/**
 * Class UserResetPasswordRequest
 * @package Wizdraw\Http\Requests\User
 */
class UserResetPasswordRequest extends AbstractRequest
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
            'email' => 'required_without:phone',
            'phone' => 'required_without:email'
        ];
    }

}
