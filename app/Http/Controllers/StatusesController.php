<?php

namespace Wizdraw\Http\Controllers;

use Wizdraw\Http\Requests\Statuses\StatusesRequest;
use Wizdraw\Notifications\TransferAborted;
use Wizdraw\Services\TransferService;

class StatusesController extends AbstractController
{

    /** @var  TransferService */
    private $transferService;

    /**
     * StatusesController constructor.
     * @param TransferService $transferService
     */
    public function __construct(TransferService $transferService)
    {
        $this->transferService = $transferService;
    }

    /**
     * @param StatusesRequest $statusesRequest
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function notifyAborted(StatusesRequest $statusesRequest){
        $requestDone = $this->transferService->clientNotifyAbortedStatus($statusesRequest['transfers']);
        $success = $requestDone ? true : false;
        return $this->respond(
            array("success" => $success)
        );
    }
}

