<?php

namespace Wizdraw\Http\Requests\User;

use Wizdraw\Http\Requests\AbstractRequest;
use Wizdraw\Traits\RequestAuthorizeUser;

/**
 * Class UpdateUserRequest
 * @package Wizdraw\Http\Requests\User
 */
class UpdateClientRequest extends AbstractRequest
{
    use RequestAuthorizeUser;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'identityTypeId'    => 'required|integer',
            'identityNumber'    => 'required|min:5|max:20',
            'identityExpire'    => 'required|date|after:today',
            'identityImage'     => 'image',
            'firstName'         => 'min:2|max:40',
            'middleName'        => 'required|min:1|max:25',
            'lastName'          => 'min:2|max:35',
            'birthDate'         => 'required|date|before:18 years ago',
            'gender'            => 'required|in:male,female',
            'phone'             => 'phone:AUTO',
            'defaultCountryId'  => 'required|integer',
            'residentCountryId' => 'required|integer',
            'city'              => 'min:2|max:30',
            'address'           => 'min:2|max:60',
            'clientType'        => 'required|in:sender,receiver',
            'profileImage'      => 'image',
        ];
    }

}
