<?php

namespace Wizdraw\Http\Requests\Client;

use Wizdraw\Http\Requests\AbstractRequest;

/**
 * Class ClientPhoneRequest
 * @package Wizdraw\Http\Requests\Client
 */
class ClientPhoneRequest extends AbstractRequest
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
            'phone' => 'phone:AUTO',
        ];
    }

}
