<?php

namespace Tests\Unit\Currency\Converter;

use App\Domain\Currency\Converter\ArrayToCurrencyPriceVOConverter;
use App\Domain\Currency\Converter\DailyXmlToArray;
use App\Domain\Currency\VO\CurrencyPriceVo;
use PHPUnit\Framework\TestCase;
use Tests\CreatesApplication;

class ArrayToCurrencyPriceVoConverterTest extends TestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        $this->createApplication();
    }

    public function getXml()
    {
        return file_get_contents(__DIR__ . '/../../../data/daily.xml');
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testArray()
    {
        $date = '2020-12-12';
        $xmlConverter = new DailyXmlToArray($this->getXml());
        $arrayData = $xmlConverter->convert();
        $data = $arrayData->map(function ($item) use ($date) {
            $item['date'] = $date;
            return $item;
        });

        $arrayConverter = new ArrayToCurrencyPriceVOConverter($data);
        $arrayOfCurrencyPriceVo = $arrayConverter->convert();
        /**
         * @var CurrencyPriceVo $currencyPriceVo
         */
        $currencyPriceVo = $arrayOfCurrencyPriceVo[0];


        $this->assertEquals($date, $currencyPriceVo->getDate());

        $this->assertEquals('42.3584', $currencyPriceVo->getValue());

        $this->assertEquals('1', $currencyPriceVo->getNominal());

        $this->assertEquals('R01010', $currencyPriceVo->getCurrencyVo()->getValuteID());
        $this->assertEquals('AUD', $currencyPriceVo->getCurrencyVo()->getCharCode());
        $this->assertEquals('036', $currencyPriceVo->getCurrencyVo()->getNumCode());
    }
}
