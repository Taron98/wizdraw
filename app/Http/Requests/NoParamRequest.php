<?php

namespace Wizdraw\Http\Requests;

/**
 * Class NoParamRequest
 * @package Wizdraw\Http\Requests
 */
class NoParamRequest extends AbstractRequest
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

        ];
    }

}
