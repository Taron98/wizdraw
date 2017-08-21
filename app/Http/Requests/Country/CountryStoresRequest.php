<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 8/21/2017
 * Time: 11:30
 */

namespace Wizdraw\Http\Requests\Country;


use Wizdraw\Http\Requests\AbstractRequest;

class CountryStoresRequest extends AbstractRequest
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
            'countryId'  => 'required|integer',
        ];
    }

}