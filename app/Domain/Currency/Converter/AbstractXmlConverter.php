<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-05-10
 * Time: 08:01
 */

namespace App\Domain\Currency\Converter;


use App\Domain\Currency\Interfaces\IItemsConverter;

abstract class AbstractXmlConverter implements IItemsConverter
{
    protected $xml;

    public function __construct($xml)
    {
        $this->xml = $xml;
    }

    public function convert()
    {
        $data = (array)simplexml_load_string($this->xml);

        return collect(
            array_map([$this, 'arrayToVo'], $data['Valute'])
        )->filter();
    }

   abstract protected function arrayToVo($item);
}
