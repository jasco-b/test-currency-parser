<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-05-09
 * Time: 19:11
 */

namespace App\Domain\Currency\VO;


use App\Domain\Currency\Exceptions\ValidationError;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Validator;

class CurrencyPriceVo implements Arrayable
{
    /**
     * @var CurrencyVo
     */
    private $currencyVo;
    private $value;
    private $date;
    /**
     * @var int
     */
    private $nominal;

    public function __construct(CurrencyVo $currencyVo, $value, $date, $nominal = 1)
    {
        $validator = Validator::make(compact('value', 'date', 'nominal'), [
            'value' => 'required|numeric',
            'date' => 'required|dateFormat:Y-m-d',
            'nominal' => 'required|numeric',
        ]);

        if ($validator->fails()) {

            throw new ValidationError($validator->errors());
        }



        $this->currencyVo = $currencyVo;
        $this->value = $value;
        $this->date = $date;
        $this->nominal = $nominal;
    }

    /**
     * @return CurrencyVo
     */
    public function getCurrencyVo(): CurrencyVo
    {
        return $this->currencyVo;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return int
     */
    public function getNominal(): int
    {
        return $this->nominal;
    }


    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        $array = $this->currencyVo->toArray();

        $data = [
            'nominal' => $this->nominal,
            'date' => $this->date,
            'value' => $this->value,
        ];

        return array_merge($array, $data);
    }
}
