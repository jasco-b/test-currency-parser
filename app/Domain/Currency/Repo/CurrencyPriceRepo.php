<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-05-09
 * Time: 20:03
 */

namespace App\Domain\Currency\Repo;


use App\Domain\Currency\Interfaces\ICurrencyPriceRepo;
use App\Models\Currency;
use App\Models\CurrencyPrice;

class CurrencyPriceRepo implements ICurrencyPriceRepo
{
    public function create(Currency $currency, $data)
    {

        $model = new CurrencyPrice();
        $model->fill($data);

        return $currency->prices()->updateOrCreate(['date' => $data['date'] ?? ''], $data);
    }

    public function filter($request)
    {
        return CurrencyPrice::filter($request)
            ->with('currency')
            ->paginate();
    }
}
