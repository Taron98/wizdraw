<?php

namespace Wizdraw\Http\Requests\Auth;

use Wizdraw\Http\Requests\AbstractRequest;

/**
 * Class AuthSignupRequest
 * @package Wizdraw\Http\Requests\Auth
 */
class AuthSignupRequest extends AbstractRequest
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
            'firstName' => 'required',
            'lastName'  => 'required',
            'email'     => 'required',
            'phone'     => 'required',
            'deviceId'  => 'required', // todo: |unique:users',
        ];
    }

}
