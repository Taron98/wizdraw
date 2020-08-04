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
        $currentVersion = config('app.version')[$deviceType];
        $serverCurrentVersion = explode('.', $currentVersion);
        $userCurrentVersion = explode('.', $version);
        //intval($serverCurrentVersion[1])> intval($userCurrentVersion[1])
        $skipUpdate = intval($serverCurrentVersion[1]) == intval($userCurrentVersion[1]);
        if($deviceType == 'ios') {
            $skipUpdate = intval($serverCurrentVersion[0]) == intval($userCurrentVersion[0]);
        }
        return [
            'version' => $currentVersion,
            'existsUpdate' => $currentVersion > $version,
            'skipUpdate' => $skipUpdate
        ];
    }
}
