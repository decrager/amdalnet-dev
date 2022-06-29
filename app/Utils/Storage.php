<?php
/**
 * Storage implementation override
 */
namespace App\Utils;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage as StorageFacade;


class Storage extends StorageFacade
{
    public static function url(string $path)
    {
        return parent::temporaryUrl($path, Carbon::now()->addMinutes(30));
    }
}
