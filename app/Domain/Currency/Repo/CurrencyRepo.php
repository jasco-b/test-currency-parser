<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-05-09
 * Time: 19:31
 */

namespace App\Domain\Currency\Repo;


use App\Domain\Currency\Interfaces\ICurrencyRepo;
use App\Models\Currency;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CurrencyRepo implements ICurrencyRepo
{
    public function findById($id)
    {
        return Currency::findOrFail($id);
    }

    /**
     * @param $valute
     * @return Currency|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     * @throws ModelNotFoundException
     */
    public function findByValute($valute)
    {
        return Currency::query()
            ->where('valuteID',$valute)
            ->firstOrFail();
    }


    public function save($data)
    {
        return Currency::updateOrCreate([
            'valuteID' => $data['valuteID'],
        ], $data);
    }

    public function getAllWithIndex()
    {
        return Currency::query()
            ->get()
            ->keyBy('valuteID');
    }
}
