<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Activity;
use Strava;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'athlete_id',
        'firstname',
        'lastname',
        'sex',
        'avatar',
        'premium',
        'strava_created_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
    ];

    public function sights()
    {
        return $this->hasMany(Sight::class)->orderBy('name');
    }

    public function importActivities() : void {

        $lastImportedActivity = Activity::where('user_id',$this->id)
            ->orderBy('start_date', 'desc')->select('start_date')->first();
        $after = $lastImportedActivity->start_date ?? null;
        var_dump($after);

        $perPage=200; // похоже что это максимальное число, которое разрешает страва. Выбрано с целью экономии вызовов к api
        $page=1;

        do { echo $page; echo('<br/>');
            $acts = Strava::activities($this->access_token,$page,$perPage,null,$after);

            foreach ($acts as $a) {
                $activity = Activity::where('strava_id',$a->id)->first();
                if ($activity != null) continue;

                $activity = Activity::create([
                    'user_id' => $this->id,
                    'strava_id' => $a->id,
                    'start_date' => Carbon::parse($a->start_date),
                    'summary_polyline' => $a->map->summary_polyline
                ]);
                $activity->save();
            }
            $page += 1;
        } while (count($acts) ?? 0 > 0);
    }

    public function topSightsVisited()
    {
        $result = DB::select('
        select s.id, s.name, count(*) count
        from sights s
        join visits v on v.sight_id = s.id
        join activities a on a.id = v.act_id
        where a.user_id = ?
        group by id,name
        order by count(*) desc
        limit 3
        ',[$this->id]);

        return $result;
    }
}
