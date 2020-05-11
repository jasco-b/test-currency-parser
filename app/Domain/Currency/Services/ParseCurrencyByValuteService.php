<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-05-09
 * Time: 23:34
 */

namespace App\Domain\Currency\Services;


use App\Domain\Currency\Converter\ArrayToCurrencyPriceVOConverter;
use App\Domain\Currency\Converter\DynamicXmlToArray;
use App\Domain\Currency\Exceptions\ParserException;
use App\Domain\Currency\Exceptions\ValidationError;
use App\Domain\Currency\Interfaces\ICurrencyParser;
use App\Domain\Currency\Interfaces\ICurrencyRepo;
use App\Domain\Currency\Repo\CurrencyRepo;
use App\Domain\Currency\Traits\DateFormatTrait;
use App\Domain\Currency\VO\CurrencyPriceVo;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;

class ParseCurrencyByValuteService implements ICurrencyParser
{
    use DateFormatTrait;
    /**
     * @var Client
     */
    private $client;
    /**
     * @var CurrencyRepo
     */
    private $currencyRepo;

    private $currency;

    public function __construct(Client $client, ICurrencyRepo $currencyRepo)
    {
        $this->client = $client;
        $this->currencyRepo = $currencyRepo;
    }

    /**
     * @param $data
     * @return \Illuminate\Support\Collection | CurrencyPriceVo[]
     * @throws ValidationError
     * @throws ParserException
     * @throws \Illuminate\Validation\ValidationException
     * @throws ModelNotFoundException
     */
    public function parse($data)
    {
        $this->validate($data);
        $xml = $this->getData($data);


        return $this->xmlDataToCurrencyPriceVo($xml, $data['valuteID']);
    }

    /**
     * @param $data
     * @return array
     * @throws ValidationError
     * @throws \Illuminate\Validation\ValidationException
     * @throws ParserException
     */
    protected function validate($data)
    {
        $validator = Validator::make($data, [
            'from' => 'required|dateFormat:' . $this->dateFormatApi(),
            'to' => 'required|dateFormat:' . $this->dateFormatApi(),
            'valuteID' => 'required|string',
        ]);

        $validator->after(function ($validator) use ($data) {
            if (!$this->getCurrency($data['valuteID'] ?? '')) {
                $validator->errors()->add('valuteID', 'Valute id not found');
            }
        });

        if ($validator->fails()) {
            throw new ValidationError($validator->errors());
        }

        return $validator->validated();
    }

    /**
     * @param $data
     * @return string
     * @throws ParserException
     */
    protected function getData($data)
    {
        try {
            $res = $this->client->get('http://www.cbr.ru/scripts/XML_dynamic.asp', [
                'query' => [
                    'date_req1' => $data['from'],
                    'date_req2' => $data['to'],
                    'VAL_NM_RQ' => $data['valuteID'],
                ],
            ]);
        } catch (RequestException $exception) {
            throw new ParserException($exception->getMessage());
        }


        return $res->getBody()->getContents();
    }

    /**
     * @param $xml
     * @param $valute
     * @return CurrencyPriceVo[]|\Illuminate\Support\Collection | CurrencyPriceVo[]
     * @throws ModelNotFoundException
     */
    protected function xmlDataToCurrencyPriceVo($xml, $valute)
    {
        $items = $this->convertXmlToArray($xml, $valute);

        return $this->convertArrayToCurrencyPriceVo($items);
    }

    /**
     * @param $array
     * @return \Illuminate\Support\Collection
     * @throws ModelNotFoundException
     * @see ArrayToCurrencyPriceVOConverter for return collection items attributes
     *
     */
    protected function convertXmlToArray($xml, $valute)
    {
        $items = (new DynamicXmlToArray($xml))->convert();
        $currency = $this->getCurrency($valute);

        return $items->map(function ($item) use ($currency) {
            $item['numCode'] = $currency['numCode'];
            $item['charCode'] = $currency['charCode'];
            $date = new Carbon($item['date']);
            $item['date'] = $date ? $date->format('Y-m-d') : $item['date'];
            return $item;
        });
    }

    /**
     * @param $valute
     * @return CurrencyRepo|\Illuminate\Database\Eloquent\Model
     * @throws ModelNotFoundException
     */
    public function getCurrency($valute)
    {
        if (!$this->currency) {
            $this->currency = $this->currencyRepo->findByValute($valute);
        }

        return $this->currency;
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
