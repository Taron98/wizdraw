<?php

namespace Wizdraw\Services;

use Wizdraw\Models\AbstractModel;
use Wizdraw\Models\Client;
use Wizdraw\Models\Nature;
use Wizdraw\Models\Status;
use Wizdraw\Repositories\TransferRepository;

/**
 * Class TransferService
 * @package Wizdraw\Services
 */
class TransferService extends AbstractService
{

    /** @var StatusService */
    protected $statusService;

    /** @var  NatureService */
    protected $natureService;

    /**
     * TransferService constructor.
     *
     * @param TransferRepository $transferRepository
     * @param StatusService $statusService
     * @param NatureService $natureService
     */
    public function __construct(
        TransferRepository $transferRepository,
        StatusService $statusService,
        NatureService $natureService
    ) {
        $this->repository = $transferRepository;
        $this->statusService = $statusService;
        $this->natureService = $natureService;
    }

    /**
     * @param int $id
     *
     * @return mixed
     */
    public function find(int $id)
    {
        return $this->repository->with(['client', 'receiverClient', 'natures', 'status'])->find($id);
    }

    /**
     * @param Client $senderClient
     * @param array $attributes
     *
     * @return AbstractModel
     */
    public function createTransfer(Client $senderClient, array $attributes = []) : AbstractModel
    {
        $initStatus = $this->statusService->findByStatus(Status::STATUS_WAIT);
        // todo: change when we'll add new natures
        $defaultNature = $this->natureService->findByNature(Nature::NATURE_SUPPORT_OR_GIFT);
        $defaultNatureIds = collect([$defaultNature])->pluck('id')->toArray();

        $transfer = $this->repository->createWithRelation($senderClient, $initStatus, $defaultNatureIds, $attributes);

        return $transfer;
    }

}