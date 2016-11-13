<?php

namespace Wizdraw\Http\Requests\Transfer;

use Wizdraw\Http\Requests\AbstractRequest;
use Wizdraw\Models\TransferType;

/**
 * Class TransferCreateRequest
 * @package Wizdraw\Http\Requests\Transfer
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
            'receiver'            => 'required|array',
            'receiver.firstName'  => 'required|min:2|max:40',
            'receiver.middleName' => 'min:1|max:25',
            'receiver.lastName'   => 'required|min:2|max:35',

            'receiverClientId'  => 'required|integer',
            'receiverCountryId' => 'required|integer',
            'senderCountryId'   => 'required|integer',

            'amount'     => 'required|numeric',
            'commission' => 'required|numeric',

            'typeId' => 'required|exists:transfer_types,id',

            'pickup'       => 'required_without:deposit|array',
            'pickup.city'  => 'required_without:deposit|min:2|max:30',
            'pickup.state' => 'required_without:deposit|min:2|max:35',

            'deposit'                => 'required_without:pickup|array',
            'deposit.bankId'         => 'required_without:pickup|integer',
            'deposit.bankBranchId'   => 'integer',
            'deposit.bankBranchName' => 'string',
            'deposit.accountNumber'  => 'required_without:pickup|string',

            'note' => 'string',

            // 'natures', current is const
        ];
    }

    /**
     * @return string
     */
    public function getTransferType() : string
    {
        if (is_array($this->input('pickup'))) {
            $type = TransferType::TYPE_PICKUP_CASH;
        } else {
            $type = TransferType::TYPE_DEPOSIT;
        }

        return $type;
    }

}
