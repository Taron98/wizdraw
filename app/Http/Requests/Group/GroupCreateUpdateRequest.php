<?php

namespace Wizdraw\Http\Requests\Group;

use Wizdraw\Http\Requests\AbstractRequest;

/**
 * Class GroupCreateUpdateRequest
 * @package Wizdraw\Http\Requests\Group
 */
class GroupCreateUpdateRequest extends AbstractRequest
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
            'name'                 => 'required|min:2|max:50',
            'clients'              => 'required|array',
            'clients.*.firstName'  => 'min:2|max:40',
            'clients.*.middleName' => 'min:1|max:25',
            'clients.*.lastName'   => 'min:2|max:35',
            'clients.*.phone'      => 'required|phone:AUTO',
        ];
    }

}
