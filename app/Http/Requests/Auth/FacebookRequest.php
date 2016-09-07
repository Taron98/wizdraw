<?php

namespace Wizdraw\Http\Requests\Auth;

use Wizdraw\Http\Requests\AbstractRequest;

/**
 * Class LoginFacebookRequest
 * @package Wizdraw\Http\Requests\Auth
 */
class FacebookRequest extends AbstractRequest
{

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return int
     */
    public function getExpire(): int
    {
        return $this->expire;
    }

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
            'token'  => 'required',
            'expire' => 'required',
        ];
    }
}
