<?php

namespace Wizdraw\Http\Controllers;

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
        ExpoToken::create($credentials);

        return $this->respond([
            'success' => true,
        ]);
    }


}
