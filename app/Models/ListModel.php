<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ListModel
{
    use HasFactory;

    public ?int $limit;
    public ?User $user;
    public ?Sight $sight;
    public ?District $district;
    public ?Area $area;

    public function __construct(Request $request)
    {
        $district = District::find($request->input('district')) ?? null;
        if($district) $this->district = $district;

        $area = Area::find($request->input('area')) ?? null;
        if($area) $this->area = $area;
    }

    public function filters($arr=[])
    {
        $result = [];
        if (!empty($this->user)) $result['user'] = $this->user->id;
        if (!empty($this->sight)) $result['sight'] = $this->sight->id;
        if (!empty($this->district)) $result['district'] = $this->district->id;
        if (!empty($this->area)) $result['area'] = $this->area->id;

        return array_merge($result,$arr);
    }

}
