<?php

namespace Wizdraw\Http\Requests\Auth;

use Wizdraw\Http\Requests\AbstractRequest;

/**
 * Class AuthFacebookRequest
 * @package Wizdraw\Http\Requests\Auth
 */
class AuthFacebookRequest extends AbstractRequest
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
        // TODO: add more validation
        return [
            'token'    => 'required',
            'expire'   => 'required',
            'deviceId' => 'required|unique:users',
        ];
    }

}
