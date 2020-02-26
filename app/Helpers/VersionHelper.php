<?php

if (!function_exists('versionControl')) {
    /**
     * check users current application version against current server application
     * enforce user to update the current installed version or not
     *
     * @param string $version
     *
     * @return array
     */
    function versionControl($deviceType, $version)
    {
        if($deviceType == 'ios') {
            $currentVersion = config('app.versionIos');
        } else {
            $currentVersion = config('app.versionAndroid');
        }
        $serverCurrentVersion = explode('.', $currentVersion);
        $userCurrentVersion = explode('.', $version);
        return [
            'version' => $currentVersion,
            'existsUpdate' => $currentVersion > $version,
            'skipUpdate' => intval($serverCurrentVersion[1])> intval($userCurrentVersion[1])
        ];
    }
}
