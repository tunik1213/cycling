<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ListModel
{
    use HasFactory;

    protected Request $request; 

    public ?int $limit;
    public ?User $user;
    public ?Sight $sight;
    public ?District $district;
    public ?Area $area;
    public ?User $author;

    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->district = null;
        if($request->input('district')) {
            $district = District::find($request->input('district')) ?? null;
            if($district) $this->district = $district;
        }

        $this->area = null;
        if($request->input('area')) {
            $area = Area::find($request->input('area')) ?? null;
            if($area) $this->area = $area;
        }

        $this->user = null;
        $this->sight = null;
    }

    public function filters($add=[],$remove=[])
    {
        $data = [];
        if (!empty($this->user)) $data['user'] = $this->user->id;
        if (!empty($this->sight)) $data['sight'] = $this->sight->id;
        if (!empty($this->district)) $data['district'] = $this->district->id;
        if (!empty($this->area)) $data['area'] = $this->area->id;
        if (!empty($this->author)) $data['author'] = $this->author->id;

        $result = array_merge($data,$add);

        foreach($remove as $r) {
            if(isset($result[$r])) unset($result[$r]);
        }

        return $result;
    }

}
