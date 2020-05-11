<?php

namespace App\Http\Controllers\Api;

use App\Domain\Currency\Interfaces\ICurrencyPriceRepo;
use App\Http\Controllers\Controller;
use App\Http\Requests\CurrencyPriceFilterRequest;
use App\Http\Resources\CurrencyPriceResource;

class CurrencyPriceController extends Controller
{
    public function index(CurrencyPriceFilterRequest $request, ICurrencyPriceRepo $currencyPriceRepo)
    {


        $models = $currencyPriceRepo->filter($request->all());
        return CurrencyPriceResource::collection($models);
    }
}
