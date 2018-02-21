<?php


/**
 * get stores available for money transaction in the current sender country
 */
if (!function_exists('get_country_stores')) {

    function get_country_stores($countryId)
    {
        $stores = DB::table('countries_stores')->where([['country_id' , '=', $countryId] , ['active', '=', 1]])->get();
        return $stores;
    }
}

/**
 * get stores who uses qr code in current sender's country
 */
if (!function_exists('use_qr_stores')) {

    function use_qr_stores($countryId)
    {
        $stores = DB::table('countries_stores')->where([['country_id' , '=', $countryId] , ['active', '=', 1], ['use_qr_code', '=', 1]])->get();
        return $stores->keyBy('store')->keys()->all();
    }
}
