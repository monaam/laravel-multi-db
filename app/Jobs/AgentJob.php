<?php

namespace App\Jobs;

use App\Models\Agent;
use App\Models\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;

class AgentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected string $database)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Config::set('database.connections.mysql_tenant.database', $this->database);
        App::singleton(Company::class, fn () => Company::query()->where('database_name', $this->database)->first());

        $count = 0;
        do {
            $count++;
            $agent = Agent::query()->create(['name' => "Agent $count"]);

            \app(Company::class)->update(['agents_count' => Agent::query()->count()]);

            echo "AgentJob: {$agent->id} for database {$this->database} \n";
            sleep(1);
        } while ($count < 10);
    }
}
