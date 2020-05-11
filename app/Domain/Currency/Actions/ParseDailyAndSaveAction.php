<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-05-11
 * Time: 00:18
 */

namespace App\Domain\Currency\Actions;


use App\Domain\Currency\Services\ParseCurrenciesService;
use App\Domain\Currency\Services\SaveCurrencyService;

class ParseDailyAndSaveAction
{
    /**
     * @var ParseCurrenciesService
     */
    private $parseCurrenciesService;
    /**
     * @var SaveCurrencyService
     */
    private $saveCurrencyService;

    public function __construct(
        ParseCurrenciesService $parseCurrenciesService,
        SaveCurrencyService $saveCurrencyService
    )
    {
        $this->parseCurrenciesService = $parseCurrenciesService;
        $this->saveCurrencyService = $saveCurrencyService;
    }

    /**
     * @param $date
     * @return bool
     * @throws \App\Domain\Currency\Exceptions\InvalidDateException
     * @throws \App\Domain\Currency\Exceptions\ParserException
     */
    public function execute($date)
    {
        $currencies = $this->parseCurrenciesService->parse($date);

        return $this->saveCurrencyService->saveMultiple($currencies);
    }
}
