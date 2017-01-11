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
            //'feedbackQuestionId' => 'required|integer|exists:feedback_questions,id',
            //'rating'             => 'required|integer|min:1|max:10',
            'note'               => 'string|max:150',
        ];
    }

}
