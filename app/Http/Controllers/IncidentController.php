<?php

namespace App\Http\Controllers;

use App\Incident;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class IncidentController extends BaseController
{
    public function LoadIncident(Request $request)
    {
        $request->validate([
           'date' => 'required|date_format:"m/d/Y"',
            'moduleid' => 'required|numeric|min:1'
        ]);

        $moduleid = $request->moduleid;

        $date = Carbon::createFromFormat("m/d/Y", $request->date)->format("Y-m-d");

        $incidents = Incident::where('module_id','=',$request->moduleid)->whereRaw("DATE(created_at) = '".$date."'")->get();

        $found = true;

        if($incidents->isEmpty())
            $found = false;

        if($found) {
            $notices = $incidents[0]->notices()->get();

            return view('dashboard.incident', compact('incidents', 'date', 'found', 'notices', 'moduleid'));
        }

        return view('dashboard.incident', compact('incidents', 'date', 'found', 'moduleid'));
    }

    public function NewIncident(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:2048',
            'moduleid' => 'required|numeric',
            'status' => 'required|numeric|max:2|min:1',
        ]);

        if(isset($request->resolved) && isset($request->enddate) == false)
            return redirect()->home()->withErrors('End date not set');

        $incident = new Incident();

        $incident->title = strip_tags($request->text);

        if($request->has('resolved'))
            $incident->resolved = 1;
        else
            $incident->resolved = 0;

        if(isset($request->enddate))
            $incident->ended_at = Carbon::createFromFormat("m/d/Y", $request->enddate);

        $incident->module_id = $request->moduleid;
        $incident->status = $request->status;

        $incident->created_at = Carbon::createFromFormat("Y-m-d", $request->date);

        $check = $incident->save();

        if($check)
            return redirect()->back()->with('success', ['Incident successfully created!']);
        else
            return redirect()->back()->withErrors('Incident failed to be created!');
    }

    public function UpdateIncident(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:2048',
            'incidentid' => 'required|numeric',
            'status' => 'required|numeric|max:2|min:1',
        ]);

        if(isset($request->resolved) && isset($request->enddate) == false)
            return redirect()->home()->withErrors('End date not set');

        $incident = Incident::find($request->incidentid);

        $incident->title = $request->text;

        if($request->has('resolved'))
            $incident->resolved = 1;
        else
            $incident->resolved = 0;

        if(isset($request->enddate))
            $incident->ended_at = Carbon::createFromFormat("m/d/Y", $request->enddate);

        $incident->status = $request->status;

        $check = $incident->save();

        if($check)
            return redirect()->back()->with('success', ['Incident successfully updated!']);
        else
            return redirect()->back()->withErrors('Incident failed to be updated!');
    }
}
