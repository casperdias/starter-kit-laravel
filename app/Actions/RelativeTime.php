<?php

namespace App\Actions;

use Carbon\Carbon;

class RelativeTime
{
    public static function forDate($date): string
    {
        return Carbon::parse($date)->diffForHumans();
    }
}
