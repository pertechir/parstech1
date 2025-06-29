<?php

namespace App\Console\Commands;

use App\Models\Person;
use Illuminate\Console\Command;

class UpdatePersonsFinancials extends Command
{
    protected $signature = 'persons:update-financials';
    protected $description = 'Update financial information for all persons';

    public function handle()
    {
        $this->info('Updating persons financial information...');

        $persons = Person::all();
        $bar = $this->output->createProgressBar(count($persons));
        $bar->start();

        foreach ($persons as $person) {
            $person->updateFinancials();
            $bar->advance();
        }

        $bar->finish();
        $this->info("\nAll persons financial information updated successfully!");
    }
}
