<?php

namespace App\Console\Commands;

use App\Models\UserPlan;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckPlanExpiry extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plans:check-expiry';
//    protected $description = 'Check and mark expired plans';

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
        Log::info('plans:check-expiry command executed at ' . now());

        $now = Carbon::now()->toDateString();

        $expiredPlans = UserPlan::where('end_date', '<', $now)
            ->where('status', true) // Adjust based on your column names
            ->update(['status' => false]);
        $this->info("Expired Plans Updated: {$expiredPlans}");
    }
}




