<?php

namespace Wizdraw\Http\Requests\Group;

use Wizdraw\Http\Requests\AbstractRequest;

/**
 * Class GroupRemoveClientRequest
 * @package Wizdraw\Http\Requests\Group
 */
class GroupRemoveClientRequest extends AbstractRequest
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
            'clients' => 'required|numeric_array',
        ];
    }

}
