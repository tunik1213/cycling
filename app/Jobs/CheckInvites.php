<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use App\Models\Sight;
use App\Models\Visit;
use App\Models\Activity;
use Illuminate\Support\Facades\DB;

class CheckInvites implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Sight $sight;

    public function __construct(Sight $s)
    {
        $this->sight = $s;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // 1. Проверить существующие посещения
        $visits = Visit::where('sight_id',$this->sight->id)->get();
        foreach ($visits as $v) {
            $a = Activity::find($v->act_id);

            if($a==null) {
                DB::table('visits')
                    ->delete($v->id);
            } else {
                if (!Visit::checkInvite($a, $this->sight)) {
                    DB::table('visits')
                        ->where('act_id', $a->id)
                        ->where('sight_id',$this->sight->id)
                        ->delete();
                }
            }
        }


        // 2. Найти новые посещения
        $acts = Activity::all();
        foreach($acts as $a) {
            Visit::searchInvites($a, $this->sight);
        }
        //Visit::findVisitsSight($this->sight);
    }
}
