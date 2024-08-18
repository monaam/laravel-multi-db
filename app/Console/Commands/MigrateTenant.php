<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MigrateTenant extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:tenant {tenant}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        config(['database.connections.mysql_tenant.database' => $this->argument('tenant')]);
        $this->call('migrate', [
            '--database' => 'mysql_tenant',
            '--path' => 'database/migrations/tenant',
        ]);
    }
}
