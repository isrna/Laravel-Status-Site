<?php

namespace App\Http\Controllers;

use App\Notice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class NoticeController extends BaseController
{
    public function NewNotice(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:2048',
            'title' => 'required|string|max:255',
            'incidentid' => 'required|numeric',
            'moduleid' => 'required|numeric'
        ]);

        $notice = new Notice();

        $notice->title = $request->title;
        $notice->notice = $request->text;
        $notice->module_id = $request->moduleid;
        $notice->incident_id = $request->incidentid;

        $check = $notice->save();

        if($check)
            return redirect()->back()->with('success', ['Notice successfully created!']);
        else
            return redirect()->back()->withErrors('Notice failed to be created!');
    }

    public function UpdateNotice(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:2048',
            'title' => 'required|string|max:255',
            'noticeid' => 'required|numeric'
        ]);

        $notice = Notice::find($request->noticeid);

        $notice->title = strip_tags($request->title);
        $notice->notice = strip_tags($request->text);

        $check = $notice->save();

        if($check)
            return redirect()->back()->with('success', ['Notice successfully update!']);
        else
            return redirect()->back()->withErrors('Notice failed to be update!');
    }
}
