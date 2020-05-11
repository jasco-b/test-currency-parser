<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-05-11
 * Time: 14:58
 */

namespace App\Domain\Currency\Interfaces;


use App\Models\Currency;

interface ICurrencyPriceRepo
{
    public function create(Currency $currency, $data);

    public function filter($request);

}
