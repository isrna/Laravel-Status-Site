<?php

namespace App\Http\Controllers;

use App\Incident;
use App\Module;
use App\Notice;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class HomeController extends BaseController
{
    public function systemNoticeCheck(Collection $modules)
    {
        foreach($modules as $module)
        {
            $status = $module->status();
            if($status > 0)
            {
                switch ($status)
                {
                    case 1:
                        return '<div class="alert alert-warning" role="alert"><strong>'.$module->Name.'</strong> - '.$module->getLatestIncident()->title.'</div>';
                    case 2:
                        return '<div class="alert alert-danger" role="alert"><strong>'.$module->Name.'</strong> - '.$module->getLatestIncident()->title.'</div>';
                }
            }

        }


        return '<div class="alert alert-success" role="alert"><strong>All Systems Operational!</strong></div>';
    }

    function paginate($items, $perPage)
    {
        $pageStart           = request('week', 1);
        $offSet              = ($pageStart * $perPage) - $perPage;
        $itemsForCurrentPage = array_slice($items, $offSet, $perPage, TRUE);

        return new LengthAwarePaginator(
            $itemsForCurrentPage, count($items), $perPage,
            Paginator::resolveCurrentPage(),
            ['path' => Paginator::resolveCurrentPath()]
            //['path' => Paginator::resolveCurrentPath()]
        );
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $modules = Module::all();
        $notice = $this->systemNoticeCheck($modules);

        $startDate = Carbon::now()->subDays(60)->toDateString();
        $endDate = Carbon::now()->toDateString();

        $data = Notice::select(DB::raw('DATE(created_at) as time'), DB::raw('count(*) as count'), 'id')
        ->whereDate('created_at', '>=', date($startDate).' 00:00:00')
        ->whereDate('created_at', '<=', date($endDate).' 00:00:00')
        ->groupBy('created_at', 'id')
        ->get()
        ->keyBy('time');

        $notices = array_reverse(array_map(function ($e) use ($data) {
            $date = Carbon::parse($e)->format('Y-m-d');
            return empty($data[$date]) ? [0, $date, null] : [$data[$date]->count, $date, Notice::whereDate('created_at', '=', $date)->get()];
        }, CarbonPeriod::create(Carbon::parse($startDate), Carbon::parse($endDate))->toArray()));

        $notices = $this->paginate($notices,5);

        return view('layouts.main', compact('notice','modules', 'notices'));
    }

    public function updateModule(Request $request)
    {
        $request->validate([
                'moduleid' => 'numeric',
                'status' => 'numeric|max:2',
                'key' => 'alpha_num'
            ]);

        //dd($request);

        if($request->key != "80cf33333d03ac4af98154dbd12ae733")
            return response()->json(0);

        $checkIncidentExists = Incident::where(['module_id' => $request->moduleid])->whereDate('created_at', Carbon::today())->get();

        if(count($checkIncidentExists) > 0){
            if($checkIncidentExists[0]->resolved > 0)
            {
                $incident = Incident::find($checkIncidentExists[0]->id);

                $incident->status = $request->status;
                $incident->resolved = 0;

                $incident->save();
            }
            else
                return response()->json(1);
        }
        else
        {
            $incident = new Incident();

            $incident->module_id = $request->moduleid;
            $incident->status = $request->status;
            $incident->title = "Under investigation";

            $saved = $incident->save();

            if(!$saved)
                return response()->json(0);
        }

        return response()->json(1);
    }
}
