<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 8/21/2017
 * Time: 11:40
 */

namespace Wizdraw\database\seeds;


use AbstractTableSeeder;
use Wizdraw\Models\CountryStore;

class CountriesStoresTableSeeder extends AbstractTableSeeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->insertStores();
    }

    /**
     * Insert another user, so we can have a user for testing
     */
    private function insertStores()
    {
        $stores = [
            [
                'country_id'     => 90,
                'store'          => '7-eleven',
                'active'         => 1
            ],
            [
                'country_id'     => 90,
                'store'          => 'Circle-K',
                'active'         => 1
            ],
            [
                'country_id'     => 13,
                'store'          => 'Wic-Store',
                'active'         => 1
            ],
        ];

        CountryStore::insert($stores);
    }
}