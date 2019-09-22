<?php
/**
 * Created by PhpStorm.
 * User: Shahar
 * Date: 22/09/2019
 * Time: 16:04
 */

namespace Wizdraw\Services;


class LogsService
{

    public function createLogFilesWithPermission()
    {
        $dateOfTomorrow = date('Y-m-d', strtotime(date('Y-m-d'). ' + 1 days'));
        $dailyLaravelLogFilePath = storage_path() . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'laravel-' . $dateOfTomorrow . '.log';
        $fileToWrite = fopen($dailyLaravelLogFilePath, "a+");
        shell_exec("sudo chmod 777 $dailyLaravelLogFilePath");
        fclose($fileToWrite);
    }
}