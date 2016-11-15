<?php

namespace Wizdraw\Http\Requests\Transfer;

use Wizdraw\Http\Requests\AbstractRequest;

/**
 * Class TransferFeedbackRequest
 * @package Wizdraw\Http\Requests\Feedback
 */
class TransferFeedbackRequest extends AbstractRequest
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
            'feedbackQuestionId' => 'required|integer',
            'rating'             => 'required|integer',
            'note'               => 'string',
        ];
    }

}
