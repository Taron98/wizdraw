<?php

namespace Wizdraw\Http\Requests\User;

use Wizdraw\Http\Requests\AbstractRequest;

/**
 * Class UserPasswordRequest
 * @package Wizdraw\Http\Requests\User
 */
class UserPasswordRequest extends AbstractRequest
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
            // "confirmed" rule requires that we pass passwordConfirmation property
            'password' => 'required|min:7|confirmed',
        ];
    }

}
