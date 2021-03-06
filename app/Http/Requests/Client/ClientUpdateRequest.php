<?php

namespace Wizdraw\Http\Requests\Client;

use Wizdraw\Http\Requests\AbstractRequest;

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
            'identityTypeId'    => 'integer|exists:identity_types,id',
            'identityNumber'    => 'min:4|max:20',
            'identityExpire'    => 'date|date_format:"Y-m-d"|after:today',
            'identityImage'     => 'base64image',
            'firstName'         => 'min:1|max:70',
            'middleName'        => 'min:1|max:70',
            'lastName'          => 'min:1|max:70',
            //'birthDate'         => 'before:18 years ago|after:100 years ago',
            'gender'            => 'in:male,female',
            'phone'             => 'phone:AUTO,IL',
            'defaultCountryId'  => 'integer|cacheExists:country',
            'residentCountryId' => 'integer|cacheExists:country',
            'state'             => 'min:2|max:120',
            'city'              => 'min:2|max:120',
            'address'           => 'min:2|max:120',
            'addressImage'      => 'base64image',
            'clientType'        => 'in:sender,receiver',
            'profileImage'      => 'base64image',
            'isApproved'        => 'integer'
        ];

//        return [
//            'identityTypeId'    => 'integer|exists:identity_types,id',
//            'identityNumber'    => 'min:4|max:20',
//            'identityExpire'    => 'date|date_format:"Y-m-d"|after:today',
//            'identityImage'     => 'base64image',
//            'firstName'         => 'min:2|max:40',
//            'middleName'        => 'min:1|max:25',
//            'lastName'          => 'min:2|max:35',
//            'birthDate'         => 'before:18 years ago|after:100 years ago',
//            'gender'            => 'in:male,female',
//            'phone'             => 'phone:AUTO',
//            'defaultCountryId'  => 'integer|cacheExists:country',
//            'residentCountryId' => 'integer|cacheExists:country',
//            'state'             => 'min:2|max:35',
//            'city'              => 'min:2|max:30',
//            'address'           => 'min:2|max:60',
//            'addressImage'      => 'base64image',
//            'clientType'        => 'in:sender,receiver',
//            'profileImage'      => 'base64image',
//        ];
    }


}
