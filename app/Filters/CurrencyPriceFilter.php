<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-05-11
 * Time: 11:42
 */

namespace App\Filters;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use JascoB\QueryFilter\QueryFilter;

class CurrencyPriceFilter extends QueryFilter
{
    private $inputs;

    /**
     * @param $query Builder
     * @param $value
     */
    public function valute($query, $value)
    {
        $query->whereHas('currency', function ($query) use ($value) {
            $query->where('valuteID', $value);
        });
    }

    /**
     * @param $query Builder
     * @param $value
     */
    public function from($query, $value)
    {

        $query->where(
            'date',
            '>=',
            Carbon::createFromFormat('d.m.Y', $value)
                ->format('Y-m-d')
        );
    }

    /**
     * @param $query Builder
     * @param $value
     */
    public function to($query, $value)
    {
        $query->where(
            'date',
            '<=',
            Carbon::createFromFormat('d.m.Y', $value)
                ->format('Y-m-d')
        );
    }

    public function filter($inputs): Builder
    {
        $this->inputs = array_filter($inputs);

        $builder = parent::filter($this->inputs);

        $this->applyDefaults();

        return $builder;
    }


    public function applyDefaults()
    {
         $this->applyDefaultOrder();
    }

    public function applyDefaultOrder()
    {
        if (empty($this->inputs['sort'])) {
            $this->builder->orderBy('date', 'desc');
        }
    }
}
