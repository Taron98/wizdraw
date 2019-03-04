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

        $credentials = $request->only('token', 'phone');
        $token = $credentials['token'];
        $phone = $credentials['phone'];
        return $this->respond([
            'success' => true,
        ]);
    }


}