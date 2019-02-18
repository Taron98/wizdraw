<?php

namespace Wizdraw\Http\Controllers;

use Illuminate\Http\JsonResponse;

/**
 * Class NotificationsController
 * @package Wizdraw\Http\Controllers
 */
class NotificationsController extends AbstractController
{

    /**
     * Saving a token for push notifications
     *
     * @param string $token
     *
     * @param string $phone
     *
     * @return mixed
     */
    public function token($token, $phone)
    {
        var_dump('test',$token, $phone);
        //save the token here
    }


}