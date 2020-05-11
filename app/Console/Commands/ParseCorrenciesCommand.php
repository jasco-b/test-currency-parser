<?php

namespace App\Console\Commands;

use App\Domain\Currency\Actions\ParseDailyAndSaveAction;
use App\Domain\Currency\Exceptions\ValidationError;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ParseCorrenciesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:parse {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get all currencies based on day';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(ParseDailyAndSaveAction $parseDailyAndSaveAction)
    {
        try {
            $parseDailyAndSaveAction->execute($this->getDate());
        } catch (ValidationError $exception) {
            $this->error($exception->getFirstError());
        }
    }

    protected function getDate()
    {
        return $this->argument('date')
            ? (new Carbon($this->argument('date')))->format('Y-m-d')
            : date('Y-m-d');
    }
}
