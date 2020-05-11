<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-05-09
 * Time: 18:58
 */

namespace App\Domain\Currency\Interfaces;



interface ICurrencyParser
{
    public function parse($date);
}
