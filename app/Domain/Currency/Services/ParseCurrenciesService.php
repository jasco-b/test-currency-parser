<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-05-09
 * Time: 18:39
 */

namespace App\Domain\Currency\Services;


use App\Domain\Currency\Converter\ArrayToCurrencyPriceVOConverter;
use App\Domain\Currency\Converter\DailyXmlToArray;
use App\Domain\Currency\Exceptions\InvalidDateException;
use App\Domain\Currency\Exceptions\ParserException;
use App\Domain\Currency\Exceptions\ValidationError;
use App\Domain\Currency\Interfaces\ICurrencyParser;
use App\Domain\Currency\VO\CurrencyPriceVo;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class ParseCurrenciesService implements ICurrencyParser
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param $isoDate
     * @return \Illuminate\Support\Collection | CurrencyPriceVo[]
     * @throws ParserException
     * @throws InvalidDateException
     * @throws ValidationError
     */
    public function parse($isoDate)
    {
        $dateObj = \DateTime::createFromFormat('Y-m-d', $isoDate);

        if (!$dateObj) {
            throw new InvalidDateException('Invalid date format ' . $isoDate);
        }

        $date = $dateObj->format('d-m-Y');

        $xml = $this->getData($date);

        return $this->xmlDataToCurrencyPriceVo($xml, $isoDate);
    }


    /**
     * @param $date
     * @return string
     * @throws ParserException
     */
    protected function getData($date)
    {

        try {
            $res = $this->client->get('http://www.cbr.ru/scripts/XML_daily_eng.asp', [
                'query' => [
                    'date_req' => $date,
                ],
            ]);
        } catch (RequestException $exception) {
            throw new ParserException($exception->getMessage());
        }

        return $res->getBody()->getContents();
    }

    protected function xmlDataToCurrencyPriceVo($xml, $date)
    {
        $items = $this->convertXmlToArray($xml, $date);

        return $this->convertArrayToCurrencyPriceVo($items);
    }

    /**
     *
     * @return \Illuminate\Support\Collection
     * @see ArrayToCurrencyPriceVOConverter for return collection items attributes
     */
    protected function convertXmlToArray($xml, $date)
    {
        $items = (new DailyXmlToArray($xml))->convert();

        return $items->map(function ($item) use ($date) {
            $item['date'] = $date;
            return $item;
        });
    }

    /**
     * @param $array
     * @return \Illuminate\Support\Collection | CurrencyPriceVo[]
     * @see ArrayToCurrencyPriceVOConverter for $array items
     */
    protected function convertArrayToCurrencyPriceVo($array)
    {
        $converter = new ArrayToCurrencyPriceVOConverter($array);
        return $converter->convert();
    }
}
