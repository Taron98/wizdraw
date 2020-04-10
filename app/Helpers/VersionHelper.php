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
            $currentVersion = config('app.version')['android'];
            if($version === '2.3') {
                $currentVersion = config('app.version')['ios'];
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
            'skipUpdate' => ($version === '8.2.4' || $version === '2.8')
        ];
    }
}
