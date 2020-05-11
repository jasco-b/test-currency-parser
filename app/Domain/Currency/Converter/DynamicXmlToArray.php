<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-05-10
 * Time: 07:35
 */

namespace App\Domain\Currency\Converter;


use App\Domain\Currency\Interfaces\IItemsConverter;

class DynamicXmlToArray implements IItemsConverter
{
    private $xml;

    public function __construct($xml)
    {
        $this->xml = $xml;
    }

    public function convert()
    {
        $data = (array)simplexml_load_string($this->xml);

        return collect(
            array_map([$this, 'arrayToVo'], $data['Record'])
        )->filter();
    }


    protected function arrayToVo($item)
    {
        $item = (array)$item;
        if (!isset($item['Value'])) {
            return [];
        }

        return [
            'nominal' => $item['Nominal'],
            'valuteID' => $item['@attributes']['Id'],
            'date' => $item['@attributes']['Date'],
            'value' => str_replace(',', '.', $item['Value']),
        ];
    }
}
