<?php

namespace Wizdraw\Http\Requests\Transfer;

use Wizdraw\Http\Requests\AbstractRequest;

/**
 * Class TransferStatusRequest
 * @package Wizdraw\Http\Requests\Transfer
 */
class TransferStatusRequest extends AbstractRequest
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
            'transferStatusId' => 'required|integer|exists:transfer_statuses,id',
        ];
    }

}
