<?php

namespace App\Console\Commands;

use \Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckDatabaseConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check database connection';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            DB::connection()->getPdo();
            $this->info('Database connection is successful.');
        } catch (Exception $e) {
            $this->error('Unable to connect to the database. Error: ' . $e->getMessage());
        }
    }
}
