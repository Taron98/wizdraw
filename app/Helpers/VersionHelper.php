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
        return [
            'version' => $currentVersion,
            'existsUpdate' => $currentVersion > $version,
            'needsUpdate' => intval($serverCurrentVersion[1])> intval($userCurrentVersion[1])
        ];
    }
}
