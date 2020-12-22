<?php

namespace App;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Module extends Model
{
    protected $dates = [ 'created_at', 'updated_at' ];

    public function status() {
        $result = $this->hasOne('App\Incident', 'module_id', 'id')->latest()->first();

        if($result)
        {
            if($result->resolved)
                return 0;
            else
                return $result->status;
        }
        else
            return 0;
    }

    public function statusText() {
        $result = $this->hasOne('App\Incident', 'module_id', 'id')->latest()->first();

        if($result)
        {
            if($result->resolved)
                return '<span class="float-right text-success">Operational</span>';
            elseif ($result->status == 1)
                return '<span class="float-right text-warning">Partial outage</span>';
            elseif ($result->status == 2)
                return '<span class="float-right text-danger">Major outage</span>';
        }
        else
            return '<span class="float-right text-success">Operational</span>';
    }

    public function getIncidents(){
        return $this->hasMany('App\Incident','module_id', 'id');
    }

    public function getLatestIncident(){
        return $this->hasMany('App\Incident','module_id', 'id')->latest()->first();
    }

    public function timelineData() {
        $startDate = Carbon::now()->subDays(60)->toDateString();
        $endDate = Carbon::now()->toDateString();

        $data = $this->getIncidents()->select(DB::raw('DATE(created_at) as time'), DB::raw('count(*) as count'), 'id')
            ->where('module_id', '=',  $this->id)
            ->whereDate('created_at', '>=', date($startDate).' 00:00:00')
            ->whereDate('created_at', '<=', date($endDate).' 00:00:00')
            ->groupBy('created_at', 'id')
            ->get()
            ->keyBy('time');

        $range = array_map(function ($e) use ($data) {
            $date = Carbon::parse($e)->format('Y-m-d');
            return empty($data[$date]) ? [0, $date, null] : [$data[$date]->count, $date, Incident::where('module_id', '=', $this->id)->whereDate('created_at', '=', $date)->get()];
        }, CarbonPeriod::create(Carbon::parse($startDate), Carbon::parse($endDate))->toArray());

        return $range;
    }

    public function code() {
        return preg_replace('/[^\w]/', '', $this->Name);
    }

    public function responseTime() {
        $result = $this->hasOne('App\ResponseTime', 'module_id', 'id')->latest()->first();
        if($result)
            return $result->response_time;
        else
            return 0;
    }

    public function responseTimes() {
        return $this->hasMany('App\ResponseTime', 'module_id', 'id');
    }
}
