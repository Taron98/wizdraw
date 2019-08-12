<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    $data = file_get_contents(database_path('cache/commissionsOriginSingapore.json'));
    $data = json_decode($data, true);
    $id = 2113;
    foreach ($data as $key => $value) {
        $data[$key]['id'] = $id;
        $id++;
    }
//    echo "<pre>";
//    print_r($data);
//    echo "</pre>";
//    die;
    echo json_encode($data);
//    return view('welcome');
});
