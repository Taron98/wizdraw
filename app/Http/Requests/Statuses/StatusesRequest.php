<?php

namespace Wizdraw\Http\Requests\Statuses;

use Wizdraw\Http\Requests\AbstractRequest;

/**
 * Class StatusesRequest
 * @package Wizdraw\Http\Requests\Statuses
 */
class StatusesRequest extends AbstractRequest
{

    const VALID_IPS = array('::1', 'localhost', '52.21.225.207', '54.86.248.41', '82.81.220.149');

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $clientIp = $this->ip();
        if(in_array($clientIp, self::VALID_IPS)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'transfers' => 'required|array'
        ];
    }

}
