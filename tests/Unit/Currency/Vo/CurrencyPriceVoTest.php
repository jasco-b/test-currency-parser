<?php

namespace Tests\Unit\Currency\Vo;

use App\Domain\Currency\Exceptions\ValidationError;
use App\Domain\Currency\VO\CurrencyPriceVo;
use App\Domain\Currency\VO\CurrencyVo;
use PHPUnit\Framework\TestCase;
use Tests\CreatesApplication;

class CurrencyPriceVoTest extends TestCase
{

    use CreatesApplication;

    protected function setUp(): void
    {
        $this->createApplication();
    }

    public function getCurrencyVo()
    {
        $valute = 'valute';
        $numCode = '036';
        $charCode = 'SUM';
        return new CurrencyVo($valute, $numCode, $charCode);
    }

    public function testValidData()
    {
        $value = '112.122';
        $date = '2020-12-12';
        $nominal = '1';
        $currencyVo = $this->getCurrencyVo();

        $currencyPriceVo = new CurrencyPriceVo($currencyVo, $value, $date, $nominal);

        $this->assertEquals($date, $currencyPriceVo->getDate());

        $this->assertEquals($value, $currencyPriceVo->getValue());

        $this->assertEquals($nominal, $currencyPriceVo->getNominal());

        $this->assertEquals($nominal, $currencyPriceVo->getNominal());

        $this->assertEquals($currencyVo, $currencyPriceVo->getCurrencyVo());
    }


    public function testWithBlankData()
    {
        $currencyVo = $this->getCurrencyVo();
        $value = '';
        $date = '';
        $nominal = '';
        $this->expectException(ValidationError::class);
        $currencyPriceVo = new CurrencyPriceVo($currencyVo, $value, $date, $nominal);
    }

    public function testWithBlankValue()
    {
        $this->expectException(ValidationError::class);
        $currencyVo = $this->getCurrencyVo();
        $value = '';
        $date = '2020-12-12';
        $nominal = '1';
        $currencyPriceVo = new CurrencyPriceVo($currencyVo, $value, $date, $nominal);
    }

    public function testWithBlankDate()
    {
        $this->expectException(ValidationError::class);
        $currencyVo = $this->getCurrencyVo();
        $value = '112.122';
        $date = '';
        $nominal = '1';
        $currencyPriceVo = new CurrencyPriceVo($currencyVo, $value, $date, $nominal);
    }

    public function testWithBlankNominal()
    {
        $this->expectException(ValidationError::class);
        $currencyVo = $this->getCurrencyVo();
        $value = '112.122';
        $date = '2020-12-12';
        $nominal = '';
        $currencyPriceVo = new CurrencyPriceVo($currencyVo, $value, $date, $nominal);
    }


    public function testWithWrongDateFormat()
    {
        $this->expectException(ValidationError::class);
        $currencyVo = $this->getCurrencyVo();
        $value = '112.122';
        $date = '01.01.2001';
        $nominal = '1';
        $currencyPriceVo = new CurrencyPriceVo($currencyVo, $value, $date, $nominal);
    }

    public function testWithWrongValue()
    {
        $this->expectException(ValidationError::class);
        $currencyVo = $this->getCurrencyVo();
        $value = 'asdfasdf';
        $date = '2020-12-12';
        $nominal = '1';
        $currencyPriceVo = new CurrencyPriceVo($currencyVo, $value, $date, $nominal);
    }

    public function testStingNominal()
    {
        $this->expectException(ValidationError::class);
        $currencyVo = $this->getCurrencyVo();
        $value = '112.122';
        $date = '2020-12-12';
        $nominal = 'adfasd';
        $currencyPriceVo = new CurrencyPriceVo($currencyVo, $value, $date, $nominal);
    }

    public function testToArray()
    {
        $value = '112.122';
        $date = '2020-12-12';
        $nominal = '2';
        $currencyVo = $this->getCurrencyVo();
        $currencyPriceVo = new CurrencyPriceVo($currencyVo, $value, $date, $nominal);

        $this->assertArrayHasKey('valuteID', $currencyPriceVo->toArray());
        $this->assertArrayHasKey('numCode', $currencyPriceVo->toArray());
        $this->assertArrayHasKey('charCode', $currencyPriceVo->toArray());
        $this->assertArrayHasKey('value', $currencyPriceVo->toArray());
        $this->assertArrayHasKey('nominal', $currencyPriceVo->toArray());
        $this->assertArrayHasKey('date', $currencyPriceVo->toArray());


        $this->assertEquals($currencyVo->getValuteID(), $currencyPriceVo->toArray()['valuteID'] ?? '');
        $this->assertEquals($currencyVo->getNumCode(), $currencyPriceVo->toArray()['numCode'] ?? '');
        $this->assertEquals($currencyVo->getCharCode(), $currencyPriceVo->toArray()['charCode'] ?? '');
        $this->assertEquals($date, $currencyPriceVo->toArray()['date'] ?? '');
        $this->assertEquals($value, $currencyPriceVo->toArray()['value'] ?? '');
        $this->assertEquals($nominal, $currencyPriceVo->toArray()['nominal'] ?? '');
    }
}
