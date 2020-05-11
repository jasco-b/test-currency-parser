<?php

namespace Tests\Feature\Api\Currency;

use App\Models\CurrencyPrice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CurrencyPriceFilterTest extends TestCase
{
    use RefreshDatabase;

    public function generate($number = 5)
    {
        $currencies = factory(CurrencyPrice::class, 5)->create();
        return $currencies->sortBy('date')->map(function ($item) {
            $item->date = $item->date->format('d.m.Y');
            return $item;
        });
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testHasAll()
    {

        $number = 5;
        $this->generate($number);

        $response = $this->get('/api/currencies/prices');

        $response->assertStatus(200);

        $response->assertJson([
            'meta' => [
                'total' => $number,

            ],
        ]);
    }

    public function testFilterByValute()
    {
        $currencies = $this->generate(5);
        $currency = $currencies[0];


        $response = $this->get('/api/currencies/prices?valute=' . $currency['currency']['valuteID']);

        $response->assertJson([
            'meta' => [
                'total' => 1,
            ],
            'data' => [
                [
                    'valuteID' => $currency->currency->valuteID,
                    'numCode' => $currency->currency->numCode,
                    'charCode' => $currency->currency->charCode,
                    'value' => $currency['value'],
                    'date' => $currency['date']->format('d.m.Y'),
                    'nominal' => $currency['nominal'],
                ]
            ]
        ]);
    }

    public function testFilterByFrom()
    {
        $currencies = $this->generate(5);

        $currency = $currencies->last();

        $response = $this->get('/api/currencies/prices?from=' . $currency['date']->format('d.m.Y'));

        $response->assertJson([
            'meta' => [
                'total' => 1,
            ],
            'data' => [
                [
                    'valuteID' => $currency->currency->valuteID,
                    'numCode' => $currency->currency->numCode,
                    'charCode' => $currency->currency->charCode,
                    'value' => $currency['value'],
                    'date' => $currency['date']->format('d.m.Y'),
                    'nominal' => $currency['nominal'],
                ]
            ]
        ]);
    }

    public function testFilterByTo()
    {
        $currencies = $this->generate(5);

        $currency = $currencies->first();


        $response = $this->get('/api/currencies/prices?to=' . $currency['date']->format('d.m.Y'));


        $response->assertJson([
            'meta' => [
                'total' => 1,
            ],
            'data' => [
                [
                    'valuteID' => $currency->currency->valuteID,
                    'numCode' => $currency->currency->numCode,
                    'charCode' => $currency->currency->charCode,
                    'value' => $currency['value'],
                    'date' => $currency['date']->format('d.m.Y'),
                    'nominal' => $currency['nominal'],
                ]
            ]
        ]);
    }


    public function testFilterByFromAndTo()
    {
        $currencies = $this->generate(5);
        $currencies = $currencies->slice(1, 3);
        $url = sprintf('/api/currencies/prices?from=%s&to=%s',
            $currencies->first()['date']->format('d.m.Y'),
            $currencies->last()['date']->format('d.m.Y')
        );
        $response = $this->get($url);

        $data = $currencies->map(function ($item) {
            return [
                'valuteID' => $item['currency']['valuteID'],
                'value' => $item['value'],
                'date' => $item['date']->format('d.m.Y')
            ];
        })->reverse()->values()->toArray();


        $response->assertJson([
            'meta' => [
                'total' => 3,
            ],
        ]);

        $response->assertJson([
            'data'=>$data
        ]);


    }


}
