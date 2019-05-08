<?php

use Wizdraw\Models\Supplier;

class SuppliersTableSeeder  extends AbstractTableSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Supplier::truncate();

        $suppliers= [
            [
                'supplier_name' => 'Samapath',
                'supplier_name_wfs' => 'Samapath Bank Ltd PickUp',
                'country_id' => '110',
                'active' => '1',
            ],
            [
                'supplier_name' => 'Nations Trust',
                'supplier_name_wfs' => 'Nations Trust Bank Pickup',
                'country_id' => '110',
                'active' => '1',
            ],
            [
                'supplier_name' => 'Royal',
                'supplier_name_wfs' => 'Royal Pickup',
                'country_id' => '54',
                'active' => '1',
            ],
            [
                'supplier_name' => 'Sunrise',
                'supplier_name_wfs' => 'Sunrise Pickup',
                'country_id' => '6',
                'active' => '1',
            ],
            [
                'supplier_name' => 'Advance',
                'supplier_name_wfs' => 'Advance Pickup',
                'country_id' => '6',
                'active' => '1',
            ],
            [
                'supplier_name' => 'Contact',
                'supplier_name_wfs' => 'Contact',
                'country_id' => '67',
                'active' => '1',
            ],
            [
                'supplier_name' => 'Korona',
                'supplier_name_wfs' => 'Korona Pay',
                'country_id' => '67',
                'active' => '1',
            ],
            [
                'supplier_name' => 'Intel',
                'supplier_name_wfs' => 'Intel Express',
                'country_id' => '67',
                'active' => '1',
            ],
            [
                'supplier_name' => 'Contact',
                'supplier_name_wfs' => 'Contact',
                'country_id' => '72',
                'active' => '1',
            ],
            [
                'supplier_name' => 'Korona',
                'supplier_name_wfs' => 'Korona Pay',
                'country_id' => '72',
                'active' => '1',
            ],
            [
                'supplier_name' => 'Intel',
                'supplier_name_wfs' => 'Intel Express',
                'country_id' => '72',
                'active' => '1',
            ],
            [
                'supplier_name' => 'Intel',
                'supplier_name_wfs' => 'Intel Express',
                'country_id' => '75',
                'active' => '1',
            ],
            [
                'supplier_name' => 'Privat Money',
                'supplier_name_wfs' => 'Privat Money',
                'country_id' => '75',
                'active' => '1',
            ],
            [
                'supplier_name' => 'Contact',
                'supplier_name_wfs' => 'Contact',
                'country_id' => '73',
                'active' => '1',
            ],
            [
                'supplier_name' => 'Korona Pay',
                'supplier_name_wfs' => 'Korona Pay',
                'country_id' => '73',
                'active' => '1',
            ],
            [
                'supplier_name' => 'Eway',
                'supplier_name_wfs' => 'Eway Pickup',
                'country_id' => '55',
                'active' => '1',
            ],
            [
                'supplier_name' => 'Pickup Philippines',
                'supplier_name_wfs' => 'Pickup Philippines',
                'country_id' => '3',
                'active' => '1',
            ],
            [
                'supplier_name' => 'METROBANK pick up',
                'supplier_name_wfs' => 'METROBANK pick up',
                'country_id' => '3',
                'active' => '1',
            ],
            [
                'supplier_name' => 'Bdo Pickup',
                'supplier_name_wfs' => 'Bdo Pickup',
                'country_id' => '3',
                'active' => '1',
            ],




        ];
        Supplier::insert($suppliers);
    }
}