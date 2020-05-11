<?php

namespace Tests\Feature\Currency;

use App\Domain\Currency\Services\SaveCurrencyService;
use App\Domain\Currency\VO\CurrencyPriceVo;
use App\Domain\Currency\VO\CurrencyVo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SaveCurrencyServiceTest extends TestCase
{
    use RefreshDatabase;

    public function getCurrencyPriceVO(CurrencyVo $currencyVo, $date = '2020-12-12', $value = '2000', $nominal = 1)
    {
        return new CurrencyPriceVo($currencyVo, $value, $date, $nominal);
    }

    public function getCurrencyVo($valuteId = 'R01234', $numCode = '036', $charCode = 'SUM')
    {
        return new CurrencyVo($valuteId, $numCode, $charCode);
    }

    public function dummyData()
    {
        return [
            'currency' => [
                'R01234',
                'Rtest',
                'Rwowr',
            ],

            'record' => [
                [
                    'price' => 2000,
                    'date' => '2020-12-12',
                ],
                [
                    'price' => 3000,
                    'date' => '2020-12-11',
                ],
                [
                    'price' => 5000,
                    'date' => '2020-12-10',
                ],

            ]
        ];
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testSaveMultiple()
    {
        $dummyData = $this->dummyData();

        $currencyVO1 = $this->getCurrencyVo($dummyData['currency'][0]);
        $currencyVO2 = $this->getCurrencyVo($dummyData['currency'][1]);

        $currencyPriceVO1 = $this->getCurrencyPriceVO(
            $currencyVO1,
            $dummyData['record'][0]['date'],
            $dummyData['record'][0]['price']
        );
        $currencyPriceVO2 = $this->getCurrencyPriceVO(
            $currencyVO2,
            $dummyData['record'][1]['date'],
            $dummyData['record'][1]['price']
        );

        /**
         * @var $saveCurrency SaveCurrencyService
         */
        $saveCurrency = $this->app->make(SaveCurrencyService::class);

        $saveCurrency->saveMultiple([
            $currencyPriceVO1,
            $currencyPriceVO2
        ]);

        $this->assertDatabaseCount('currencies', 2);

        $this->assertDatabaseHas('currencies', [
            'valuteID' => $dummyData['currency'][0],
        ]);

        $this->assertDatabaseHas('currencies', [
            'valuteID' => $dummyData['currency'][1],
        ]);


        $this->assertDatabaseCount('currency_prices', 2);

        $this->assertDatabaseHas('currency_prices', [
            'value' => $dummyData['record'][0]['price'],
        ]);

        $this->assertDatabaseHas('currency_prices', [
            'value' => $dummyData['record'][1]['price'],
        ]);


    }

    public function testSaveOne()
    {
        $dummyData = $this->dummyData();

        $currencyVO1 = $this->getCurrencyVo($dummyData['currency'][2]);

        $currencyPriceVO1 = $this->getCurrencyPriceVO(
            $currencyVO1,
            $dummyData['record'][2]['date'],
            $dummyData['record'][2]['price']
        );

        /**
         * @var $saveCurrency SaveCurrencyService
         */
        $saveCurrency = $this->app->make(SaveCurrencyService::class);

        $saveCurrency->saveOne(
            $currencyPriceVO1
        );


        $this->assertDatabaseCount('currencies', 1);

        $this->assertDatabaseHas('currencies', [
            'valuteID' => $dummyData['currency'][2],
        ]);


        $this->assertDatabaseCount('currency_prices', 1);

        $this->assertDatabaseHas('currency_prices', [
            'value' => $dummyData['record'][2]['price'],
        ]);

    }

}
