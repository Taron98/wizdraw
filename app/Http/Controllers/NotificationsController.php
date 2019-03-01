<?php

namespace Wizdraw\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Wizdraw\Http\Requests\NotificationsRequest;


/**
 * Class NotificationsController
 * @package Wizdraw\Http\Controllers
 */
class NotificationsController extends AbstractController
{

    /**
     * Saving a token for push notifications
     *
     * @param NotificationsRequest $request
     *
     * @return JsonResponse
     */
    public function token( NotificationsRequest $request
    ): JsonResponse
    {

        //save the token here
        $token = $credentials = $request->only('token');
        $phone = $credentials = $request->only('phone');
        dd('test',$token, $phone);
        return $this->respond([
            'success' => true,
        ]);
    }


}