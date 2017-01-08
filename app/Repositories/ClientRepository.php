<?php

namespace Wizdraw\Repositories;

use Wizdraw\Models\AbstractModel;
use Wizdraw\Models\Affiliate;
use Wizdraw\Models\Client;

/**
 * Class ClientRepository
 * @package Wizdraw\Repositories
 */
class ClientRepository extends AbstractRepository
{

    /**
     * @return string
     */
    public function model(): string
    {
        return Client::class;
    }

    /**
     * @param string $phone
     *
     * @return mixed
     */
    public function findByPhone(string $phone)
    {
        return $this->findByField('phone', $phone);
    }

    /**
     * @param $affiliate
     *
     * @param Client $client
     *
     * @return AbstractModel
     */
    public function updateAffiliate(Affiliate $affiliate, Client $client): AbstractModel
    {
        $client->setAffiliateId($affiliate->getId());

        return $this->updateModel($client);
    }
}