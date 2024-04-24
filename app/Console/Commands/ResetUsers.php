<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class ResetUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes all users from the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        User::query()->delete();
        $this->info('All users have been deleted successfully.');
    }
}
