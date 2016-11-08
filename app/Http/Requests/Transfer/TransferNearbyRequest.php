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
            'latitude'  => ['required', 'regex:/^-?([1-8]?[1-9]|[1-9]0)\.{1}\d{1,6}$/'],
            'longitude' => ['required', 'regex:/^-?([1]?[1-7][1-9]|[1]?[1-8][0]|[1-9]?[0-9])\.{1}\d{1,6}$/'],
        ];
    }

}
