<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-05-10
 * Time: 07:38
 */

namespace App\Domain\Currency\Converter;


use App\Domain\Currency\Interfaces\IItemsConverter;
use App\Domain\Currency\VO\CurrencyPriceVo;
use App\Domain\Currency\VO\CurrencyVo;

class ArrayToCurrencyPriceVOConverter implements IItemsConverter
{

    private $items;

    /**
     * ArrayToCurrencyPriceVOConverter constructor.
     * @param $items array
     * @description
     *
     * $item = [
     *      'valuteID' => '',
     *      'numCode'=>'',
     *      'charCode'=>'',
     *      'nominal'=>'',
     *      'value'=>'',
     *      'date'=>'',
     *
     * ]
     */
    public function __construct($items)
    {
        $this->items = $items;
    }

    public function convert()
    {
        return collect(
            array_map([$this, 'arrayToVo'], is_array($this->items) ? $this->items : $this->items->toArray())
        );
    }

    protected function arrayToVo($item)
    {

        $currencyVo = new CurrencyVo(
            $item['valuteID'] ?? '',
            $item['numCode'] ?? '',
            $item['charCode'] ?? ''
        );

        return new CurrencyPriceVo(
            $currencyVo,
            $item['value']??'',
            $item['date']??'',
            $item['nominal']??1
        );
    }


}
