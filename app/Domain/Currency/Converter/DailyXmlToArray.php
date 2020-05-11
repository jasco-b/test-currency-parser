<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-05-09
 * Time: 19:02
 */

namespace App\Domain\Currency\Converter;


use App\Domain\Currency\Interfaces\IItemsConverter;

class DailyXmlToArray implements IItemsConverter
{
    private $xml;

    public function __construct($xml)
    {
        $this->xml = $xml;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function convert()
    {
        $data = (array)simplexml_load_string($this->xml);

        return collect(
            array_map([$this, 'arrayToVo'], $data['Valute'])
        )->filter();
    }

    protected function arrayToVo($item)
    {

        return [
            'valuteID' => (string)$item->attributes()->ID,
            'numCode' => (string)$item->NumCode,
            'charCode' => (string)$item->CharCode,
            'value' => str_replace(',', '.', (string)$item->Value),
            'nominal' => (string)$item->Nominal,
        ];
    }
}
