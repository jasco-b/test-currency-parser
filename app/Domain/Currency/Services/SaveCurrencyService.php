<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-05-09
 * Time: 19:25
 */

namespace App\Domain\Currency\Services;


use App\Domain\Currency\Interfaces\ICurrencyPriceRepo;
use App\Domain\Currency\Interfaces\ICurrencyRepo;
use App\Domain\Currency\Repo\CurrencyPriceRepo;
use App\Domain\Currency\Repo\CurrencyRepo;
use App\Domain\Currency\VO\CurrencyPriceVo;
use App\Domain\Currency\VO\CurrencyVo;

class SaveCurrencyService
{
    /**
     * @var CurrencyRepo
     */
    private $repo;
    /**
     * @var CurrencyPriceRepo
     */
    private $currencyPriceRepo;

    protected $currencies;

    public function __construct(ICurrencyRepo $repo, ICurrencyPriceRepo $currencyPriceRepo)
    {
        $this->repo = $repo;
        $this->currencyPriceRepo = $currencyPriceRepo;
    }

    /**
     * @param $data CurrencyPriceVo[]
     */
    public function saveMultiple($data)
    {
        \DB::beginTransaction();

        foreach ($data as $item) {
            $this->saveOne($item);
        }
        \DB::commit();

        return true;
    }

    public function saveOne(CurrencyPriceVo $currencyPriceVo)
    {
        // create or get Currency
        $category = $this->getOrCreateCurrency($currencyPriceVo->getCurrencyVo());

        return $this->currencyPriceRepo->create($category, $currencyPriceVo->toArray());
    }

    protected function getOrCreateCurrency(CurrencyVo $currencyVo)
    {
        if (!$this->currencies) {
            $this->currencies = $this->repo->getAllWithIndex();
        }

        if (!isset($this->currencies[$currencyVo->getValuteID()])) {
            $category = $this->repo->save($currencyVo->toArray());
            $this->currencies[$currencyVo->getValuteID()] = $category;
        }

        $category = $this->currencies[$currencyVo->getValuteID()];


        return $category;
    }
}
