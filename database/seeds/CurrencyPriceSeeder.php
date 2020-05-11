<?php

use Illuminate\Database\Seeder;

class CurrencyPriceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Illuminate\Database\Eloquent\Model::reguard();
        for ($i = 0; $i <= 30; $i++) {
            $date = (new \Carbon\Carbon())->subDays($i)->format('Y-m-d');
            Artisan::call('currency:parse ' . $date);
        }
    }
}
