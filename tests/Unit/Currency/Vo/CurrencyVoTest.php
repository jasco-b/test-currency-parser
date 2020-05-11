<?php

namespace Tests\Unit\Currency\Vo;

use App\Domain\Currency\Exceptions\ValidationError;
use App\Domain\Currency\VO\CurrencyVo;
use PHPUnit\Framework\TestCase;
use Tests\CreatesApplication;

class CurrencyVoTest extends TestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
       $this->createApplication();
    }

    public function testValidData()
    {
        $valute = 'valute';
        $numCode = '036';
        $charCode = 'SUM';
        $currencyVo = new CurrencyVo($valute, $numCode, $charCode);

        $this->assertEquals($valute, $currencyVo->getValuteID());

        $this->assertEquals($numCode, $currencyVo->getNumCode());

        $this->assertEquals($charCode, $currencyVo->getCharCode());
    }


    public function testWithBlankData()
    {
        $this->expectException(ValidationError::class);
        $currencyVo = new CurrencyVo('', '', '');
    }

    public function testWithBlankValute()
    {
        $valute = 'valute';
        $numCode = '036';
        $charCode = 'SUM';
        $this->expectException(ValidationError::class);
        $currencyVo = new CurrencyVo('', $numCode, $charCode);
    }

    public function testWithBlankNumCode()
    {
        $valute = 'valute';
        $numCode = '036';
        $charCode = 'SUM';
        $this->expectException(ValidationError::class);
        $currencyVo = new CurrencyVo($valute, '', $charCode);
    }

    public function testWithBlankCharCode()
    {
        $valute = 'valute';
        $numCode = '036';
        $charCode = 'SUM';
        $this->expectException(ValidationError::class);
        $currencyVo = new CurrencyVo($valute, $numCode, '');
    }

    public function testWithNumcodeLength()
    {
        $valute = 'valute';
        $numCode = '123123123';
        $charCode = 'SUM';
        $this->expectException(ValidationError::class);
        $currencyVo = new CurrencyVo($valute, $numCode, $charCode);
    }


    public function testWithCharCodeLength()
    {
        $valute = 'valute';
        $numCode = '036';
        $charCode = 'asdfasd';
        $this->expectException(ValidationError::class);
        $currencyVo = new CurrencyVo($valute, $numCode, $charCode);
    }

    public function testWithValuteLength()
    {
        $valute = '123123123123123';
        $numCode = '036';
        $charCode = 'SUM';
        $this->expectException(ValidationError::class);
        $currencyVo = new CurrencyVo($valute, $numCode, $charCode);
    }

    public function testToArray()
    {
        $valute = 'valute';
        $numCode = '036';
        $charCode = 'SUM';
        $currencyVo = new CurrencyVo($valute, $numCode, $charCode);

        $this->assertArrayHasKey('valuteID', $currencyVo->toArray());
        $this->assertArrayHasKey('numCode', $currencyVo->toArray());
        $this->assertArrayHasKey('charCode', $currencyVo->toArray());


        $this->assertEquals($valute, $currencyVo->toArray()['valuteID']??'');
        $this->assertEquals($numCode, $currencyVo->toArray()['numCode']??'');
        $this->assertEquals($charCode, $currencyVo->toArray()['charCode']??'');
    }
}
