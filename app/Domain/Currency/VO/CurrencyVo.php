<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-05-09
 * Time: 19:05
 */

namespace App\Domain\Currency\VO;


use App\Domain\Currency\Exceptions\ValidationError;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Validator;

class CurrencyVo implements Arrayable
{
    private $valuteID;
    private $numCode;
    private $charCode;

    public function __construct($valuteID, $numCode, $charCode)
    {

        $validator = Validator::make(compact('valuteID', 'numCode', 'charCode'), [
            'valuteID' => 'required|max:10',
            'numCode' => 'required|max:3',
            'charCode' => 'required|max:3',
        ]);

        if ($validator->fails()){

            throw new ValidationError($validator->errors());
        }

        $this->valuteID = $valuteID;
        $this->numCode = $numCode;
        $this->charCode = $charCode;
    }

    /**
     * @return mixed
     */
    public function getValuteID()
    {
        return $this->valuteID;
    }

    /**
     * @return mixed
     */
    public function getNumCode()
    {
        return $this->numCode;
    }

    /**
     * @return mixed
     */
    public function getCharCode()
    {
        return $this->charCode;
    }


    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'valuteID' => $this->valuteID,
            'charCode' => $this->charCode,
            'numCode' => $this->numCode,
        ];
    }
}
