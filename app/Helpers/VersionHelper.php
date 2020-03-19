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
        if(is_null($version)) {
            $version = $deviceType;
            $currentVersion = config('app.versionOld');
            if(strpos($version, '2.3') !== false) {
                $currentVersion = '2.4';
            }
            $serverCurrentVersion = explode('.', $currentVersion);
            $userCurrentVersion = explode('.', $version);
            return [
                'version' => $currentVersion,
                'existsUpdate' => $currentVersion > $version,
                'needsUpdate' => true
            ];
        }
        $currentVersion = config('app.version')[$deviceType];
        $serverCurrentVersion = explode('.', $currentVersion);
        $userCurrentVersion = explode('.', $version);
        //intval($serverCurrentVersion[1])> intval($userCurrentVersion[1])
        return [
            'version' => $currentVersion,
            'existsUpdate' => $currentVersion > $version,
            'skipUpdate' => false
        ];
    }
}
