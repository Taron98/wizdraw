<?php

namespace Wizdraw\Http\Requests\Client;

use Wizdraw\Http\Requests\AbstractRequest;
use Wizdraw\Traits\RequestAuthorizeUser;

/**
 * Class ClientUpdateRequest
 * @package Wizdraw\Http\Requests\Client
 */
class ClientUpdateRequest extends AbstractRequest
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
