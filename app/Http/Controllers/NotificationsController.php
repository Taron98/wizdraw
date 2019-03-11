<?php

namespace Wizdraw\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Wizdraw\Http\Requests\NotificationsRequest;
use Illuminate\Support\Facades\DB;




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
        $credentials = $request->all();
        $token = $credentials['expo_token'];
        $deviceId = $credentials['deviceId'];
        $res = DB::table('expo_token')->insert([
            ['expo_token' => $token],
            ['device_id' => $deviceId],
        ]);
        dd($res);

        return $this->respond([
            'success' => true,
        ]);
    }


}
