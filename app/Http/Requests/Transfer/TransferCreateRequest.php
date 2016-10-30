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
            'receiver'            => 'required|array',
            'receiver.clientId'   => 'required|integer',
            'receiver.firstName'  => 'required|min:2|max:40',
            'receiver.middleName' => 'min:1|max:25',
            'receiver.lastName'   => 'required|min:2|max:35',
            'receiver.countryId'  => 'required|integer',

            'sender'           => 'required|array',
            'sender.countryId' => 'required|integer',

            'amount'     => 'required|integer',
            'commission' => 'required|integer',

            'typeId' => 'required|integer',

            'pickup'       => 'required_without:deposit|array',
            'pickup.city'  => 'required|min:2|max:30',
            'pickup.state' => 'required|min:2|max:35',

            'deposit'                => 'required_without:pickup|array',
            'deposit.bankId'         => 'required|integer',
            'deposit.bankBranchId'   => 'required_without:deposit.bankBranchName|integer',
            'deposit.bankBranchName' => 'required_without:deposit.bankBranchId|string',
            'deposit.accountNumber'  => 'required|string',

            'note' => 'string',

            'receipt'             => 'required|array',
            'receipt.image'       => 'required|image',
            'receipt.number'      => 'required|string',
            'receipt.expense'     => 'required|integer',
            'receipt.date'        => 'required|date',
            'receipt.typeExpense' => 'required|string',
            'receipt.remark'      => 'required|string'

            // 'natures', current is const
        ];
    }

}
