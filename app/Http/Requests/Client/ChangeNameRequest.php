<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 8/16/2017
 * Time: 16:28
 */

namespace Wizdraw\Http\Requests\Client;


use Wizdraw\Http\Requests\AbstractRequest;

class ChangeNameRequest extends AbstractRequest
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
            'firstName'         => 'min:1|max:70',
            'middleName'        => 'min:1|max:70',
            'lastName'          => 'min:1|max:70',
            'receiverId'        => 'required|integer|exists:clients,id',
        ];
    }

}