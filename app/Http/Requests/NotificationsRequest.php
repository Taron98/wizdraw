<?php
/**
 * Created by PhpStorm.
 * User: rubina.shakhkyan
 * Date: 01.03.2019
 * Time: 13:26
 */

namespace Wizdraw\Http\Requests;


class NotificationsRequest extends  AbstractRequest
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
            'expo_token' => 'required',
	        'deviceId' => 'required'
        ];
    }
}
