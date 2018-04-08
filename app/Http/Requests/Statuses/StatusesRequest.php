<?php

namespace Wizdraw\Http\Requests\Statuses;

use Wizdraw\Http\Requests\AbstractRequest;

/**
 * Class TransferAddReceiptRequest
 * @package Wizdraw\Http\Requests\Transfer
 */
class StatusesRequest extends AbstractRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //todo: validate ip's from wic's bridge
        $r = $this->ip();
        if($r){
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
