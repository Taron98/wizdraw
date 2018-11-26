<?php

namespace Wizdraw\Http\Requests\Transfer\WizdrawCard;

use Wizdraw\Http\Requests\Transfer\TransferCreateRequest;

class SMSVerificationSendAmountRequest extends TransferCreateRequest
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
        $parentRules = parent::rules();
        return array_merge($parentRules, [
            'cId' => 'required|string',
            'smsCode' => 'required|string'
        ]);
    }
}
