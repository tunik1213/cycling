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
    public ?Route $route;
    protected ?int $timestamp;

    public function __construct(Request $request)
    {
        $this->request = $request;

        $this->district = null;
        if($request->input('district')) {
            $district = District::find($request->input('district')) ?? null;
            if($district) {
                $this->district = $district;
            }
        }

        $this->area = null;
        if($request->input('area')) {
            $area = Area::find($request->input('area')) ?? null;
            if($area) {
                $this->area = $area;
            }
        }

        $this->route = null;
        if($request->input('route')) {
            $route = Route::find($request->input('route')) ?? null;
            if($route) {
                $this->route = $route;
            }
        }

        $this->user = null;
        if($request->input('user')) {
            $user = User::find($request->input('user')) ?? null;
            if($user) {
                $this->user = $user;
            }
        }


        $this->sight = null;

        $this->timestamp = $request->input('notification');
    }

    public function filters($add = [], $remove = [])
    {
        $data = [];
        if (!empty($this->user)) {
            $data['user'] = $this->user->id;
        }
        if (!empty($this->sight)) {
            $data['sight'] = $this->sight->id;
        }
        if (!empty($this->district)) {
            $data['district'] = $this->district->id;
        }
        if (!empty($this->area)) {
            $data['area'] = $this->area->id;
        }
        if (!empty($this->category)) {
            $data['category'] = $this->category->id;
        }
        if (!empty($this->subcategory)) {
            $data['subcategory'] = $this->subcategory->id;
        }
        if (!empty($this->author)) {
            $data['author'] = $this->author->id;
        }
        if (!empty($this->route)) {
            $data['route'] = $this->route->id;
        }

        $result = array_merge($data, $add);

        foreach($remove as $r) {
            if(isset($result[$r])) {
                unset($result[$r]);
            }
        }

        return $result;
    }

}
