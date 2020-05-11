<?php

namespace App\Domain\Currency\Providers;

use App\Domain\Currency\Interfaces\ICurrencyPriceRepo;
use App\Domain\Currency\Interfaces\ICurrencyRepo;
use App\Domain\Currency\Repo\CurrencyPriceRepo;
use App\Domain\Currency\Repo\CurrencyRepo;
use Illuminate\Support\ServiceProvider;

class CurrencyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ICurrencyRepo::class, CurrencyRepo::class);

        $this->app->bind(ICurrencyPriceRepo::class, CurrencyPriceRepo::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
