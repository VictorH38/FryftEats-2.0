<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Restaurant;

class ResetRestaurants extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'restaurants:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes all restaurants from the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Restaurant::query()->delete();
        $this->info('All restaurants have been deleted successfully.');
    }
}
