<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    protected $dates = [ 'created_at', 'updated_at' ];

    public function notices()
    {
        return $this->hasMany('App\Notice', 'incident_id', 'id');
    }

    public function duration()
    {
        $start = Carbon::parse($this->created_at);
        $end = Carbon::parse($this->ended_at);
        $total = $end->diff($start);

        if($total->h > 0 && $total->i > 0)
            return ($total->h > 0 ? $total->h.'h' : '').($total->i > 0 ? ' '.$total->i.'min' : '');
        else if($total->h > 0 && $total->i == 0)
            return $total->h.'h';
        else if($total->h == 0 && $total->i > 0)
            return $total->i.'min';
        else if($total->h == 0 && $total->i == 0 && $total->s > 0)
            return $total->s.'s';
        else if($total->h == 0 && $total->i == 0 && $total->s == 0 && $total->f > 0)
            return $total->f.'ms';
        else
            return '1min';

    }

    public function getIncidents(){
        return $this->hasMany('App\Incident')->groupBy('created_at');
    }
}
