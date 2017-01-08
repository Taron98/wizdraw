<?php

namespace Wizdraw\Repositories;

use Wizdraw\Models\Client;
use Wizdraw\Models\Vip;

/**
 * Class VipRepository
 * @package Wizdraw\Repositories
 */
class VipRepository extends AbstractRepository
{

    /**
     * @return string
     */
    public function model(): string
    {
        return Vip::class;
    }

    /**
     * @param Client $client
     *
     * @return Vip
     */
    public function createWithRelation(Client $client)
    {
        /** @var Vip $newVip */
        $newVip = $this->makeModel();

        $newVip->client()->associate($client);
        $newVip->save();

        return $newVip;
    }

}