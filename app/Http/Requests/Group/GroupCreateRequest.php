<?php

namespace Wizdraw\Http\Requests\Group;

use Wizdraw\Http\Requests\AbstractRequest;

/**
 * Class GroupCreateRequest
 * @package Wizdraw\Http\Requests\Group
 */
class GroupCreateRequest extends AbstractRequest
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
            'name'                        => 'required|min:2|max:50',
            'clients'                     => 'array',
            'clients.*.firstName'         => 'min:2|max:40',
            'clients.*.middleName'        => 'min:1|max:25',
            'clients.*.lastName'          => 'min:2|max:35',
            'clients.*.residentCountryId' => 'integer|cacheExists:country',
            'clients.*.phone'             => 'required|phone:AUTO',
        ];
    }

}
