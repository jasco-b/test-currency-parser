<?php
/**
 * Created by PhpStorm.
 * User: jasurbek
 * Date: 2020-05-11
 * Time: 00:18
 */

namespace App\Domain\Currency\Actions;


use App\Domain\Currency\Services\ParseCurrencyByValuteService;
use App\Domain\Currency\Services\SaveCurrencyService;
use App\Domain\Currency\Traits\DateFormatTrait;
use Carbon\Carbon;

class ParseByValuteAndSaveAction
{
    use DateFormatTrait;

    /**
     * @var ParseCurrencyByValuteService
     */
    private $currencyByValuteService;
    /**
     * @var SaveCurrencyService
     */
    private $saveCurrencyService;

    public function __construct(ParseCurrencyByValuteService $currencyByValuteService, SaveCurrencyService $saveCurrencyService)
    {
        $this->currencyByValuteService = $currencyByValuteService;
        $this->saveCurrencyService = $saveCurrencyService;
    }

    public function execute($data)
    {

        $currencyPriceVoArray = $this->currencyByValuteService->parse($this->getData($data));

        $this->saveCurrencyService->saveMultiple($currencyPriceVoArray);
    }

    public function getData($data)
    {
        $data['to'] = $this->getTo($data);
        $data['from'] = $this->getFrom($data);

        return $data;
    }

    protected function getFrom($data)
    {
        if (empty($data['from']) && !empty($data['to'])) {
            $date = Carbon::createFromFormat($this->dateFormatApi(), $data['to']);
            return $date ? $date->subDays(30)->format($this->dateFormatApi()) : null;
        } elseif (empty($data['from']) && empty($data['to'])) {
            return (new Carbon())->subDays(30)->format($this->dateFormatApi());
        }

        return $data['from'] ?? null;
    }

    protected function getTo($data)
    {
        if (empty($data['to']) && !empty($data['from'])) {
            $date = Carbon::createFromFormat($this->dateFormatApi(), $data['from']);
            return $date ? $date->addDays(30)->format($this->dateFormatApi()) : null;
        }elseif (empty($data['from']) && empty($data['to'])) {
            return date($this->dateFormatApi());
        }

        return $data['to'] ?? null;
    }


}
