<?php

namespace Wizdraw\Http\Requests\Transfer\WizdrawCard;

use Wizdraw\Http\Requests\AbstractRequest;

class SendSMSRequest extends AbstractRequest
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
            'cId' => 'required|string'
        ];
    }
}
