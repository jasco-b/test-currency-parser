<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-05-11
 * Time: 00:39
 */

namespace App\Domain\Currency\Traits;


trait DateFormatTrait
{
    public function dateFormatApi()
    {
        return config('currencyApi.date.format');
    }
}
