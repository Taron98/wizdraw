<?php

namespace Wizdraw\Http\Requests;

/**
 * Class LoginFacebookRequest
 * @package Wizdraw\Http\Requests
 */
class LoginFacebookRequest extends AbstractRequest
{
    protected $redirect = false;

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken(string $token)
    {
        $this->token = $token;
    }

    /**
     * @return int
     */
    public function getExpire(): int
    {
        return $this->expire;
    }

    /**
     * @param int $expire
     */
    public function setExpire(int $expire)
    {
        $this->expire = $expire;
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
