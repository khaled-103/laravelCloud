<?php

namespace App\Jobs;

use App\Models\ConfigrationModel;
use App\Models\ReadStatistic;
use App\Models\StatisticsModel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RefreshStatisticsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $statistics = StatisticsModel::latest()->first();
        $rs = ReadStatistic::first();
        $config = ConfigrationModel::first();
        if (!$statistics) {
            $statistics = [
                'number_of_items' => 0,
                'total_items_size' => 0,
                'miss_rate' => 0,
                'hit_rate' => 0,
            ];
        }
        if ($rs) {
            $rs->update([
                'number_of_items' => $statistics->number_of_items,
                'total_items_size' => $statistics->total_items_size,
                'miss_rate' => $statistics->miss_rate,
                'hit_rate' => $statistics->hit_rate,
                'policy' => $config->replacment_policy == 2 ? 'Least Recently Used Replacment' : 'Random Replacment'
            ]);
            return;
        }
        ReadStatistic::create([
            'number_of_items' => $statistics->number_of_items,
            'total_items_size' => $statistics->total_items_size,
            'miss_rate' => $statistics->miss_rate,
            'hit_rate' => $statistics->hit_rate,
            'policy' => $config->replacment_policy == 2 ? 'Least Recently Used Replacment' : 'Random Replacment'
        ]);
    }
}
