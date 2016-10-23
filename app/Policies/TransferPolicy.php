<?php

namespace Wizdraw\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Wizdraw\Models\Client;
use Wizdraw\Models\Transfer;

/**
 * Class TransferPolicy
 * @package Wizdraw\Policies
 */
class TransferPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can show the transfer
     *
     * @param Client $client
     * @param Transfer $transfer
     *
     * @return bool
     */
    public function show(Client $client, Transfer $transfer)
    {
        return ($client->getId() === $transfer->client->getId() ||
            $client->getId() === $transfer->receiverClient->getId());
    }

}
