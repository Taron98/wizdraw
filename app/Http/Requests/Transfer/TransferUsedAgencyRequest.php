<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 7/30/2017
 * Time: 15:43
 */

namespace Wizdraw\Http\Requests\Transfer;


use Wizdraw\Http\Requests\AbstractRequest;

class TransferUsedAgencyRequest extends AbstractRequest
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
            'paymentAgency' => 'required|string'
        ];
    }
}