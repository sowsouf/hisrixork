<?php

namespace App\Http\Controllers;

use App\Helpers;
use App\Models\Category;
use App\Models\Wcalendar;
use App\Models\Worktime;
use App\Models\Calendar;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\JsonEncodingException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\JsonResponse;

class WcalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        (new Calendar())->getOutDays();
        $user = Auth::user();
        $data = array(
            "hours"      => array(1, 2, 3, 4, 5, 6),
            "date"       => Carbon::now('Europe/Paris'),
            "categories" => Category::all(),
            "calendar"   => (new Calendar())->show(),
            "navi"       => (new Calendar())->navigation(),
            "counts"     => array(
                'f'  => DB::table('wcalendars')->where('id_user', $user->id)->where('id_category', Category::where('name', 'F')->first()->id ?? -1)->count() - (DB::table('wcalendars')->where('half', 1)->where('id_category', Category::where('name', 'F')->first()->id ?? -1)->count() / 2),
                'cp' => DB::table('wcalendars')->where('id_user', $user->id)->where('id_category', Category::where('name', 'CP')->first()->id ?? -1)->count() - (DB::table('wcalendars')->where('half', 1)->where('id_category', Category::where('name', 'CP')->first()->id ?? -1)->count() / 2),
                'r'  => DB::table('wcalendars')->where('id_user', $user->id)->where('id_category', Category::where('name', 'R')->first()->id ?? -1)->count() - (DB::table('wcalendars')->where('half', 1)->where('id_category', Category::where('name', 'R')->first()->id ?? -1)->count() / 2),
                'am' => DB::table('wcalendars')->where('id_user', $user->id)->where('id_category', Category::where('name', 'AM')->first()->id ?? -1)->count() - (DB::table('wcalendars')->where('half', 1)->where('id_category', Category::where('name', 'AM')->first()->id ?? -1)->count() / 2),
                'cs' => DB::table('wcalendars')->where('id_user', $user->id)->where('id_category', Category::where('name', 'CS')->first()->id ?? -1)->count() - (DB::table('wcalendars')->where('half', 1)->where('id_category', Category::where('name', 'CS')->first()->id ?? -1)->count() / 2),
                'tt' => DB::table('wcalendars')->where('id_user', $user->id)->where('id_category', Category::where('name', 'TT')->first()->id ?? -1)->count() - (DB::table('wcalendars')->where('half', 1)->where('id_category', Category::where('name', 'TT')->first()->id ?? -1)->count() / 2),
            ),
            "days"       => Helpers::getNumberRange(1, 31),
            "months"     => Helpers::getNumberRange(1, 12),
            "years"      => Helpers::getNumberRange(2015, 2030),
        );

        return view('pages.wcalendar.index', compact('data'));
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

        Wcalendar::unguard();

        $response = new JsonResponse();
        try {
            $data = json_decode(base64_decode($request->get('data')));
        } catch (JsonEncodingException $e) {
            $data = json_decode(json_encode([]));
        }

        $user = Auth::user();
        $start = Carbon::parse($data->start, 'Europe/Paris') ?? Carbon::now('Europe/Paris');
        $stop = Carbon::parse($data->stop, 'Europe/Paris') ?? Carbon::now('Europe/Paris');
        $category = $data->category ?? -1;
        $half = $data->half ?? 0;

        for ($i = $start; $i <= $stop; $i++) {

            if (!in_array($i->toDateString(), session()->get('we')) && !in_array($i->toDateString(), session()->get('out'))) {

                if (($wc = Wcalendar::where('start', $i)->where('id_user', $user->id)->first()) === null)
                    $wc = new Wcalendar([
                        'start'       => $i,
                        'stop'        => $stop,
                        'half'        => $data->half ?? 0,
                        'id_category' => $data->category ?? -1,
                        'id_user'     => $user->id ?? -1
                    ]);
                else {
                    $wc->setAttribute('start', $start);
                    $wc->setAttribute('stop', $stop);
                    $wc->setAttribute('half', $half);
                    $wc->setAttribute('id_category', $category);
                }

                $wc->save();

            }

            $i = $i->addDay();

        }


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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Worktime $worktime
     * @return \Illuminate\Http\Response
     */
    public function destroyAllInvalid()
    {
        Wcalendar::where('id_category', '-1')->delete();
        return response()->json();
    }


    public function goto($month, $year)
    {
        $url = (new Calendar())->getNaviHref();

        return redirect(route('wcalendar.index', ['month' => $month, 'year' => $year]));

    }
}
