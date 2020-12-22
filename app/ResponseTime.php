<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ResponseTime extends Model
{
    public $timestamps = false;

    protected $dates = [ 'created_at'];

    public $fillable = ['module_id', 'response_time', 'created_at'];

    public function getTime(){
        $minuteInterval = 10;
        return Carbon::createFromTime($this->created_at->hour, round($this->created_at->minute / $minuteInterval) * $minuteInterval, $this->created_at->second)->format('H:i');
    }

    /**
     * Sets created_at when creating the model.
     * Timestamps property should be false on the model.
     *
     * @param Model $model
     * @return void

     * crashes pivot
     * TODO: update time on API insert
     *
    public function creating(Model $model)
    {
        $model->date_created_at = $model->freshTimestamp();
        $model->time_created_at = $model->freshTimestamp();
    } */
}
