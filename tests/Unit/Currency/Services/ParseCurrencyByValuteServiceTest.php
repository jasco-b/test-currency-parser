<?php

namespace Tests\Unit\Currency\Services;

use App\Domain\Currency\Exceptions\ValidationError;
use App\Domain\Currency\Interfaces\ICurrencyRepo;
use App\Domain\Currency\Services\ParseCurrencyByValuteService;
use App\Domain\Currency\VO\CurrencyPriceVo;
use App\Models\Currency;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Tests\CreatesApplication;

class ParseCurrencyByValuteServiceTest extends TestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();
        $this->createApplication();
    }

    public function getXml()
    {
        return file_get_contents(__DIR__ . '/../../../data/dynamic.xml');
    }

    public function getCurrencyRepo()
    {
        $currency = new Currency();
        $currency->valuteID = 'R01235';
        $currency->charCode = 'AUD';
        $currency->numCode = '036';

        return $this->createConfiguredMock(ICurrencyRepo::class, [
            'findByValute' => $currency,
        ]);
    }


    public function testWithBlankData()
    {
        $data = [];
        $service = new ParseCurrencyByValuteService(new Client(), $this->getCurrencyRepo());
        $this->expectException(ValidationError::class);
        $service->parse($data);
    }

    public function testBlankFrom()
    {
        $data = [
            'valuteID'=>'R01010',
            'from'=>'',
            'to'=>'05.01.2020',
        ];
        $service = new ParseCurrencyByValuteService(new Client(), $this->getCurrencyRepo());
        $this->expectException(ValidationError::class);
        $service->parse($data);
    }

    public function testBlankTo()
    {
         $data = [
            'valuteID'=>'R01010',
            'from'=>'01.01.2020',
            'to'=>'',
        ];
        $service = new ParseCurrencyByValuteService(new Client(), $this->getCurrencyRepo());
        $this->expectException(ValidationError::class);
        $service->parse($data);
    }

    public function testBlankValuteId()
    {
        $data = [
            'valuteID'=>'',
            'from'=>'01.01.2020',
            'to'=>'05.01.2020',
        ];
        $service = new ParseCurrencyByValuteService(new Client(), $this->getCurrencyRepo());
        $this->expectException(ValidationError::class);
        $service->parse($data);
    }

    public function testValidData()
    {
        $data = [
           'valuteID'=>'R01010',
           'from'=>'01.01.2020',
           'to'=>'05.01.2020',
        ];

        $mock = new MockHandler([
            new Response(200, [], $this->getXml()),
        ]);

        $handlerStack = HandlerStack::create($mock);

        $client = new Client(['handler' => $handlerStack]);

        $service = new ParseCurrencyByValuteService($client, $this->getCurrencyRepo());

        $arrayOfCurrencyPriceVo = $service->parse($data);

        /**
         * @var CurrencyPriceVo $currencyPriceVo
         */
        $currencyPriceVo = $arrayOfCurrencyPriceVo[0];


        $this->assertEquals('2001-03-02', $currencyPriceVo->getDate());

        $this->assertEquals('28.6200', $currencyPriceVo->getValue());

        $this->assertEquals('1', $currencyPriceVo->getNominal());

        $this->assertEquals('R01235', $currencyPriceVo->getCurrencyVo()->getValuteID());
        $this->assertEquals('AUD', $currencyPriceVo->getCurrencyVo()->getCharCode());
        $this->assertEquals('036', $currencyPriceVo->getCurrencyVo()->getNumCode());


    }
}
