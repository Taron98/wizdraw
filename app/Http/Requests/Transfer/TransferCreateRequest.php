<?php

namespace Wizdraw\Http\Requests\Transfer;

use Wizdraw\Http\Requests\AbstractRequest;

/**
 * Class TransferCreateRequest
 * @package Wizdraw\Http\Requests\Group
 */
class TransferCreateRequest extends AbstractRequest
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
            'receiverClientId'  => 'required|integer',
            'bankAccountId'     => 'required|integer',
            'receiverCountryId' => 'required|integer',
            'senderCountryId'   => 'required|integer',
            'amount'            => 'required|integer',
            'commission'        => 'required|integer',
            // 'natures', current is const
        ];
    }

}
