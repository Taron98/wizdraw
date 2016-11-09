<?php

namespace Wizdraw\Http\Requests\Transfer;

use Wizdraw\Http\Requests\AbstractRequest;

/**
 * Class TransferAddReceiptRequest
 * @package Wizdraw\Http\Requests\Transfer
 */
class TransferAddReceiptRequest extends AbstractRequest
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
            'image'       => 'required|base64image',
            'number'      => 'required|string',
            'expense'     => 'required|string',
            'expenseType' => 'required|string',
            'remark'      => 'required|string',
            'note'        => 'string',
            'issued_at'   => 'required|date|date_format:"Y-m-d"',
        ];
    }

}
