<?php

namespace Wizdraw\Http\Controllers;

use ExponentPhpSDK\Expo;
use Illuminate\Http\JsonResponse;
use Wizdraw\Http\Requests\NotificationsRequest;
use Illuminate\Support\Facades\DB;
use Wizdraw\Models\ExpoToken;


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
     * @return string
     */
    public function token( NotificationsRequest $request
    ): JsonResponse
    {
        $credentials = $request->all();
        $expo = ExpoToken::firstOrNew(array('device_id' => $credentials['device_id'], 'client_id'=>$credentials['client_id']));
        $expo->expo_token = $credentials['expo_token'];
        $expo->save();

        return $this->respond([
            'success' => true,
        ]);
    }


}
