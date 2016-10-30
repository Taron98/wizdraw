<?php

namespace Wizdraw\Repositories;

use Wizdraw\Models\Client;
use Wizdraw\Models\TransferStatus;
use Wizdraw\Models\Transfer;

/**
 * Class TransferRepository
 * @package Wizdraw\Repositories
 */
class TransferRepository extends AbstractRepository
{

    /**
     * @return string
     */
    public function model() : string
    {
        return Transfer::class;
    }

    /**
     * Create a transfer with his relationships
     *
     * @param Client $senderClient
     *
     * @param TransferStatus $status
     * @param array $natures
     * @param array $attributes
     *
     * @return null|Transfer
     */
    public function createWithRelation(Client $senderClient, TransferStatus $status, array $natures, array $attributes)
    {
        /** @var Transfer $newTransfer */
        $newTransfer = $this->makeModel()->fill($attributes);

        $newTransfer->client()->associate($senderClient);
        $newTransfer->status()->associate($status);

        if (!$newTransfer->save()) {
            return null;
        }

        $newTransfer->natures()->sync($natures);

        return $this->find($newTransfer->getId());
    }

}