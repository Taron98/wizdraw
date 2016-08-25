<?php

namespace Wizdraw\Http\Requests;

class LoginFacebookRequest extends Request
{
    protected $redirect = false;

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
            'accessToken' => 'required',
            'expire' => 'required',
        ];
    }
}
