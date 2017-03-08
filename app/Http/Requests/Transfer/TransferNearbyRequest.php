<?php

namespace Wizdraw\Http\Requests\Transfer;

use Wizdraw\Http\Requests\AbstractRequest;

/**
 * Class TransferNearbyRequest
 * @package Wizdraw\Http\Requests\Transfer
 */
class TransferNearbyRequest extends AbstractRequest
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
            'latitude'  => 'required|latitude',
            'longitude' => 'required|longitude',
            'agency' => 'required'
        ];
    }

}
