<?php

namespace App\Http\Controllers;

use App\Models\Worktime;
use App\Models\Calendar;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\JsonResponse;

class WorktimeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $hours = array(1, 2, 3, 4, 5, 6);
        $times = DB::table('worktimes')->where('id_user', '=', -1)->get();
        $current = DB::table('worktimes')->where('stop', '=', null)->orderByDesc('stop')->get()->first();
        $date = Carbon::now('Europe/Paris');
        $datas = array(
            "hours"   => $hours,
            "times"   => $times,
            "date"    => $date,
            "current" => $current,
            "calendar" => (new Calendar())->show(),
            "navi" => (new Calendar())->navigation()
        );

        return view('pages.worktime.index', compact('datas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response|JsonResponse
     */
    public function store(Request $request)
    {
        $response = new JsonResponse();
        $data = $request->get('data');
        $user = Auth::user();

        $date = Carbon::now('Europe/Paris');
        $time = new Worktime(['start' => $date]);
        if (($result = $time->save()) === false)
            $response->setStatusCode(400);

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Worktime $worktime
     * @return \Illuminate\Http\Response
     */
    public function show(Worktime $worktime)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Worktime $worktime
     * @return \Illuminate\Http\Response
     */
    public function edit(Worktime $worktime)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Worktime $worktime
     * @return \Illuminate\Http\Response|JsonResponse
     */
    public function update(Request $request, Worktime $worktime)
    {
        $response = new JsonResponse();
        $data = array();
        $user = Auth::user();

        $data['stop'] = Carbon::now('Europe/Paris');
        $worktime->update($data);
        $response->setData($worktime);

//        $date = Carbon::now('Europe/Paris');
//        $time = new Worktime(['start' => $date]);
//        if (($result = $time->save()) === false)
//            $response->setStatusCode(400);


        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Worktime $worktime
     * @return \Illuminate\Http\Response
     */
    public function destroy(Worktime $worktime)
    {
        //
    }
}
