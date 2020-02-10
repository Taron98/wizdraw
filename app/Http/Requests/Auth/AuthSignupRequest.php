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
        $rules = [
            'firstName' => 'required',
            'lastName'  => 'required',
            'email'     => 'required|email',
            'deviceId'  => 'required', //|unique:users',
            'phone' => 'required|phone:AUTO,IL'
        ];
        if(strpos($this->phone, '5527') || strpos($this->phone, '5526')) {
            $rules['phone'] = array(
                'required',
                'regex:(^\+?(972|0|\+972|00972)(\-)?(([23489]{1}\d{7})|[5]{1}\d{8}))'
            );
        }
        return $rules;
    }

}
