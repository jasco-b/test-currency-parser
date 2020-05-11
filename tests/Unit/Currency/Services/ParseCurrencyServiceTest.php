<?php

namespace Tests\Unit\Currency\Services;

use App\Domain\Currency\Exceptions\InvalidDateException;
use App\Domain\Currency\Services\ParseCurrenciesService;
use App\Domain\Currency\VO\CurrencyPriceVo;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class ParseCurrencyServiceTest extends TestCase
{

    public function getXml()
    {
        return file_get_contents(__DIR__ . '/../../../data/daily.xml');
    }

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testWrongDateFormat()
    {
        $date = '12-12-2014';
        $service = new ParseCurrenciesService(new Client());
        $this->expectException(InvalidDateException::class);
        $service->parse($date);
    }

    public function testValidData()
    {
        $mock = new MockHandler([
            new Response(200, [], $this->getXml()),
        ]);

        $handlerStack = HandlerStack::create($mock);

        $client = new Client(['handler' => $handlerStack]);

        $service = new ParseCurrenciesService($client);

        $date = '2020-01-10';
        $arrayOfCurrencyPriceVo = $service->parse($date);

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
