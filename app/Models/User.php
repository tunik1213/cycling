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
use App\Models\SightList;
use App\Models\Visit;

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

    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    public function importActivities() : void {

        $lastImportedActivity = Activity::where('user_id',$this->id)
            ->orderBy('start_date', 'desc')->select('start_date')->first();
        $after = $lastImportedActivity->start_date ?? null;

        $perPage=200; // похоже что это максимальное число, которое разрешает страва. Выбрано с целью экономии вызовов к api
        $page=1;
        $imported = [];

        do { echo $page; echo('<br/>');
            $acts = Strava::activities($this->access_token,$page,$perPage,null,$after);

            foreach ($acts as $a) {
                $activity = Activity::where('strava_id',$a->id)->first();
                if ($activity != null) continue;
                if (empty($a->map->summary_polyline)) continue;
                if ($a->type != 'Ride') continue;

                $activity = Activity::create([
                    'user_id' => $this->id,
                    'strava_id' => $a->id,
                    'start_date' => Carbon::parse($a->start_date),
                    'polyline' => $a->map->polyline ?? null,
                    'summary_polyline' => $a->map->summary_polyline,
                    'name' => $a->name
                ]);
                $activity->save();

                array_push($imported,$activity);
            }
            $page += 1;
        } while (count($acts) ?? 0 > 0);

        if(count($imported)>0) {
            Visit::findVisitsActivities($imported,$this);
        }
    }

    public function topSightsVisited()
    {
        $top = new SightList;
        $top->user = $this;
        $top->limit = 4;
        return $top->index();
    }

    public function getLinkAttribute()
    {
        return view('user.link',['user'=>$this]);
    }
    public function getStravaLinkAttribute()
    {
        return view('user.stravaLink',['user'=>$this]);
    }

    public function getfullNameAttribute()
    {
        return $this->firstname.' '.$this->lastname;
    }

    public function getRegisteredAtAttribute() {
        return view('user.registered_at',['user'=>$this]);
    }

    public function gender($male,$female)
    {
        return ($this->sex == 'F') ? $female : $male;
    }

    public function getdefaultSightDescriptionAttribute() : string
    {
        if($this->id === 3) return '<br /><br />Посилання: wikipedia'; // voronin

        return '';
    }

    public function stats() : array
    {
        $result = [];

        $result['activities'] = $this->activities->count();

        $q = DB::select('
select count(distinct v.sight_id) count
from visits v
join activities a on a.id = v.act_id
where a.user_id = :user_id
            ',['user_id'=>$this->id]);
        $result['sights'] = $q[0]->count ?? 0;

$q = DB::select('
select count(distinct s.area_id) areas,
    count(distinct s.district_id) districts
from visits v
join activities a on a.id = v.act_id
join sights s on s.id = v.sight_id
where a.user_id = :user_id
            ',['user_id'=>$this->id]);

        $result['areas'] = $q[0]->areas ?? 0;
        $result['districts'] = $q[0]->districts ?? 0;


        return $result;
    }

    public static function update_visits_verify($key)
    {
	// file_put_contents('/var/www/html/cycling/tmp/test.log',$key);

        if(strpos($key,'visits-user') !== false) {
            $user_id = str_replace('visits-user','',$key);

            $user = User::find($user_id);
            if ($user) {
                $user->visits_verified_at = Carbon::now();
                $user->save();
            }
        }
    }

    public function getAvatarUrlAttribute() : string
    {
        return route('userAvatar', ['id' => $this->id]);
    }

}
