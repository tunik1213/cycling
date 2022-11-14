<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Image extends Model
{
    use HasFactory;

    public function getUrlAttribute() : string
    {
        return '/image/'.$this->id;
    }

    public function UsageCount() : int
    {
        $img_link = route('getImage',$this->id,false);

        $query_text = '
select count(*) count
from sights s
where s.description like \'%'.$img_link.'%\'
        ';

        $usage = 0;

        $result = DB::select(DB::raw($query_text));
        if(count($result)>0) {
            $usage = $result[0]->count;
        }
        return $usage;
    }
}
