<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-05-11
 * Time: 14:58
 */

namespace App\Domain\Currency\Interfaces;


use App\Models\Currency;
use Illuminate\Database\Eloquent\ModelNotFoundException;

interface ICurrencyRepo
{
    public function findById($id);

    /**
     * @param $valute
     * @return Currency|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model
     * @throws ModelNotFoundException
     */
    public function findByValute($valute);


    public function save($data);

    public function getAllWithIndex();

}
