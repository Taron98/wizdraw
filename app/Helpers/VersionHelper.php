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
    function versionControl($version)
    {
        $currentVersion = config('app.version');
        $serverCurrentVersion = explode('.', $currentVersion);
        $userCurrentVersion = explode('.', $version);

        return [
            'version' => $currentVersion,
            'needsUpdate' => intval($serverCurrentVersion[0]) > intval($userCurrentVersion[0])
        ];
    }
}