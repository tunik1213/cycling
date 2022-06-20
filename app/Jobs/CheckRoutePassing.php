<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Route;
use App\Models\RoutePass;
use Illuminate\Support\Facades\DB;

class CheckRoutePassing implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Route $route;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Route $r)
    {
        $this->route = $r;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::statement('delete from route_passes where route_id = '.$this->route->id.';');

        DB::Statement('
insert route_passes (route_id,act_id)
select '.$this->route->id.', v.act_id
from route_sight rs
cross join visits v on v.sight_id = rs.sight_id
where rs.route_id = '.$this->route->id.'
group by v.act_id
having count(*) = '.$this->route->sights->count().';
        ');
    }
}
