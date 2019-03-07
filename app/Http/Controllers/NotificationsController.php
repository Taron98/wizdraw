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

        $credentials = $request->only('expo_token');
        dd($request->all());
        $token = $credentials['expo_token'];
        //store it somewhere
        return $this->respond([
            'success' => true,
        ]);
    }


}