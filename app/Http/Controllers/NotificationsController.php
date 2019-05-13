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
        $expo = ExpoToken::where(['device_id' => $credentials['device_id'], 'client_id'=>$credentials['client_id']])->first();
        if ($expo == null){
            ExpoToken::create($credentials);
        }else{
            $expo->update(['expo_token' => $credentials['expo_token']]);
        }

        return $this->respond([
            'success' => true,
        ]);
    }


}
