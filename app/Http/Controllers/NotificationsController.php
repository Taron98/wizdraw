<?php

namespace Wizdraw\Http\Controllers;

use ExponentPhpSDK\Expo;
use Illuminate\Http\JsonResponse;
use Wizdraw\Http\Requests\NotificationsRequest;
use Illuminate\Support\Facades\DB;
use Wizdraw\Models\ExpoToken;
use Wizdraw\Models\FirebaseToken;

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
        $fcm = FirebaseToken::where(['device_id' => $credentials['device_id'], 'client_id'=>$credentials['client_id']])->first();
        if ($fcm == null){
            FirebaseToken::create($credentials);
        }else{
            $fcm->update(['fcm_token' => $credentials['fcm_token']]);
        }
        return $this->respond([
            'success' => true,
        ]);
    }


}
