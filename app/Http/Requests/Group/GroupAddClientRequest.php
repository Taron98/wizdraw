<?php

namespace Wizdraw\Http\Requests\Group;

use Wizdraw\Http\Requests\AbstractRequest;

/**
 * Class GroupAddClientRequest
 * @package Wizdraw\Http\Requests\Group
 */
class GroupAddClientRequest extends AbstractRequest
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
            'clients'                     => 'required|array',
            'clients.*.firstName'         => 'min:2|max:40',
            'clients.*.middleName'        => 'min:1|max:25',
            'clients.*.lastName'          => 'min:2|max:35',
            'clients.*.residentCountryId' => 'integer',
            'clients.*.phone'             => 'required|phone:AUTO',
        ];
    }

}
