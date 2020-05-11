<?php

namespace App\Console\Commands;

use App\Domain\Currency\Actions\ParseByValuteAndSaveAction;
use App\Domain\Currency\Exceptions\ValidationError;
use App\Domain\Currency\Services\ParseCurrencyByValuteService;
use App\Domain\Currency\Services\SaveCurrencyService;
use Illuminate\Console\Command;

class ParseCurrenciesByValute extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:valute {id} {--from=} {--to=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse valute by id within dates';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(ParseByValuteAndSaveAction $action)
    {
        try{
            $action->execute([
                'valuteID' => $this->argument('id'),
                'from' => $this->option('from'),
                'to' => $this->option('to'),
            ]);
        }catch (ValidationError $exception){
            $this->error($exception->getFirstError());
        }


    }
}
