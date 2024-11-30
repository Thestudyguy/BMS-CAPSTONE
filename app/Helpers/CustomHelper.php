<?php

namespace App\Helpers;

use Jenssegers\Agent\Agent;

class CustomHelper
{
    public static function getBrowserDetails($userAgent)
    {
        $agent = new Agent();
        $agent->setUserAgent($userAgent);

        return [
            'browser' => $agent->browser(),
            'platform' => $agent->platform(),
            'platform_version' => $agent->version($agent->platform()),
        ];
    }
}
