<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 1/12/2017
 * Time: 10:49
 */

namespace Wizdraw\Http\Requests\Feedback;

use Wizdraw\Http\Requests\AbstractRequest;

/**
 * Class FeedbackReviewRequest
 * @package Wizdraw\Http\Requests\Feedback
 */
class FeedbackReviewRequest extends AbstractRequest
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
            'note' => 'required|string|max:150',
        ];
    }
}