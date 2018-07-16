@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-12 p-0">
            <div class="ring-left"></div>
            <div class="ring-right"></div>
            <div class="calendar-header bg-color-4 row text-white">
                {{--<div class="col-12 col-md-6 mx-auto">--}}
                {{--<div class="d-flex justify-content-center align-items-center flex-column fa-2x">--}}
                {{--<div class="row">--}}
                {{--{{ ($date = \Carbon\Carbon::now('Europe/Paris'))->day }}--}}
                {{--</div>--}}
                {{--<div class="row">--}}
                {{--                            {{ $data['navi']['date'] }}--}}
                {{--</div>--}}
                {{--</div>--}}
                {{--</div>--}}
                <div class="col-12 col-m6 mx-auto d-flex justify-content-center align-items-center flex-column">
                    <div class="w-100">
                        @if(($navi = $data['navi']) !== null)
                            {{--<div class="row w-100">--}}
                            {{--<div class="row w-100">--}}
                            {{--<div class="col-12 text-center">--}}
                            {{--{{ $navi['date'] }}--}}
                            {{--</div>--}}
                            {{--</div>--}}
                            <div class="row w-100 mx-auto">
                                <div class="col-3 text-left">
                                    <a href="{{ $navi['prev'] }}" class="text-white"><i
                                                class="fa fa-chevron-left fa-2x"></i></a>
                                </div>
                                <div class="col-6 text-center text-uppercase">
                                    {{ $navi['date'] }}
                                </div>
                                <div class="col-3 text-right">
                                    <a href="{{ $navi['next'] }}" class="text-white"><i
                                                class="fa fa-chevron-right fa-2x"></i></a>
                                </div>
                            </div>
                            <div class="row w-100 mx-auto">
                                <div class="col-12 text-center">
                                    <a href="{{ $navi['today'] }}" class="text-white">
                                        <i class="fa fa-bullseye"></i>
                                    </a>
                                </div>
                            </div>
                            {{--</div>--}}
                        @endif
                        {{--<div class="row">--}}
                        {{--{{ $date->format('F') }}--}}
                        {{--</div>--}}
                    </div>
                </div>

                {{--<div class="col-12 col-md-6 mx-auto">
                    <div class="event d-flex justify-content-center align-items-center fa-4x">
                        @if (($current = $data['current']) === null || $current->stop !== null)
                            <a href="{{ route('worktime.store') }}"
                               class="text-white h-100 w-100 d-flex justify-content-center align-items-center"
                               id="startDay"><i
                                        class="fa fa-play"></i></a>
                        @elseif($current->stop === null)
                            <a href="{{ route('worktime.update', $current->id) }}"
                               class="text-white h-100 w-100 d-flex justify-content-center align-items-center"
                               id="stopDay"><i
                                        class="fa fa-stop"></i></a>
                        @endif
                    </div>
                </div>--}}
            </div>

            <div class="col-12 clock-container d-none justify-content-center align-items-center">
                <div class="clock">
                    @if(isset($data['hours']))
                        @foreach($data['hours'] as $hour)
                            <div class="hour hour-{{$hour}}"></div>
                        @endforeach
                    @endif
                    <div class="center-empty m-auto bg-white rounded-circle"></div>
                    <div class="center m-auto bg-dark rounded-circle"></div>
                    <div class="hours m-auto bg-dark"></div>
                    <div class="minutes m-auto bg-dark"></div>
                    <div class="seconds m-auto bg-dark"></div>
                    <div class="date bg-dark text-white z-depth-1 my-auto ml-auto text-center fa-xs d-flex justify-content-center align-items-center rounded">{{ $data['date']->day ?? 0 }}</div>
                </div>
            </div>

            <a href="{{ route('wcalendar.destroy.invalid') }}" id="destroyInvalid"></a>

            <div class="row">

                <div class="col-12">
                    {!! $data['calendar'] !!}
                </div>

            </div>

            <div class="row" id="legend">
                @foreach($data['counts'] as $label => $count)
                    <div class="col-4 col-md-2 text-center">
                        <div class="type d-block m-auto rounded-circle text-white {{ strtoupper($label) }}">
                            <span class="d-flex justify-content-center align-items-center">{{ strtoupper($label) }}</span>
                        </div>
                        <i>{{ $count }}</i>
                    </div>
                @endforeach

            </div>
        </div>
    </div>

    @include('includes.modals.addcalendar', [
    'categories' => $data['categories']
    ])

    @include('includes.modals.pickdate', [
    'days' => $data['days'],
    'months' => $data['months'],
    'years' => $data['years'],
    ])


    <div class="export bg-success rounded-circle d-flex justify-content-center align-items-center z-depth-1-half cursor-pointer"
         data-url="{{ route('export.route') }}">
        <i class="fa fa-file-excel text-white"></i>
    </div>

    <div class="pick-date bg-info rounded-circle d-flex justify-content-center align-items-center z-depth-1-half cursor-pointer"
         data-url="{{ route('export.route') }}">
        <i class="fa fa-calendar-alt text-white"></i>
    </div>

@endsection

@section('javascript')
    <script>

        /* Fonction qui sert à afficher l'écran d'horloge */
        $(function () {

            let inactive, interval = 600000

            inactive = setTimeout(function () {
                $('.clock-container').removeClass('d-none').addClass('d-flex')
            }, interval)

            $('.clock-container').click(function () {
                clearTimeout(inactive)
                $('.clock-container').addClass('d-none').removeClass('d-flex')
                inactive = setTimeout(function () {
                    $('.clock-container').removeClass('d-none').addClass('d-flex')
                }, interval)
            })

            $(document).click(function () {
                clearTimeout(inactive)
                $('.clock-container').addClass('d-none').removeClass('d-flex')
                inactive = setTimeout(function () {
                    $('.clock-container').removeClass('d-none').addClass('d-flex')
                }, interval)
            })
        })

        /* Fonction qui sert à effectuer la rotation des aiguilles */

        $(function () {
            jQuery.fn.rotate = function (degrees) {
                $(this).css({
                    '-webkit-transform': 'rotate(' + degrees + 'deg)',
                    '-moz-transform': 'rotate(' + degrees + 'deg)',
                    '-ms-transform': 'rotate(' + degrees + 'deg)',
                    'transform': 'rotate(' + degrees + 'deg)'
                });
                return $(this);
            }

            let time, date, h, m, s, e = 360

            time = setInterval(function () {
                date = moment().locale('fr')
                h = date.hours()
                m = date.minutes()
                s = date.seconds()
                $('.clock .hours').rotate((e * h) / 12)
                $('.clock .minutes').rotate((e * m) / 60)
                $('.clock .seconds').rotate((e * s) / 60)
            }, 1000)
        })

        $(function () {
            $('a#startDay').click(function (e) {
                (e || window.event).preventDefault()

                let that = $(this), url = that.attr('href')

                axios.post(url).then((r) => {
                    console.log(r)
                })

            })

            $('a#stopDay').click(function (e) {
                (e || window.event).preventDefault()

                let that = $(this), url = that.attr('href')

                axios.put(url).then((r) => {
                    console.log(r)
                })

            })
        })

        $(function () {
            $('#calendar .box-content li').click(function () {

                let that = $(this), id = (that.attr('id') || 'li-').split('li-')[1] || null,
                    modal = $('#addCalendarModal'), cat = that.attr('data-cat')

                axios.get('/category/' + (cat || -1)).then(r => {
                    modal.find('select#category').val(r.data.id || -1)
                    modal.find('input#start').val(id)
                    modal.find('input#stop').val(id)
                    modal.modal('show')
                }, () => {
                    modal.find('input#start').val(id)
                    modal.find('input#stop').val(id)
                    modal.modal('show')
                })

            })

        })

    </script>

    <script>

        $('input#start, input#stop').on('input', function () {
            let start = $('input#start').val(), stop = $('input#stop').val()
            if (start === stop)
                $('#halfRow').removeClass('d-none')
            else
                $('#halfRow').addClass('d-none')
        })

        $(function () {
            $('#addCalendarBtn').click(function (e) {

                $("#form-load").toggleClass("d-flex d-none")

                e.preventDefault()

                let data = {}, url = $('#addCalendarForm').attr('action'), dataSend

                data.category = $('select#category').val()
                data.start = $('input#start').val()
                data.stop = $('input#stop').val()
                data.half = $('input#half').is(':checked')

                dataSend = btoa(JSON.stringify(data))

                axios.post(url, {data: dataSend}).then((r) => {
                    let delUrl = $('a#destroyInvalid').attr('href')
                    axios.delete(delUrl).then(() => {
                        location.reload()
                    }, () => {
                        location.reload()
                    })
                })
            })

            $('#pickDateBtn').click(function (e) {

                $("#form-load").toggleClass("d-flex d-none")

                (e || window.event).preventDefault()

                let data = {}, url = $('#pickDateBtn').attr('data-url')

                data.day = $('select#day').val()
                data.month = $('select#month').val()
                data.year = $('select#year').val()

                location.href = `${url}/${data.month}/${data.year}`

            })

            $('.export').click(function () {

                $("#form-load").toggleClass("d-flex d-none")

                let year = getUrlParameter('year') || moment().year(), url = $(this).attr('data-url')

                axios.get(url + '/' + year).then((r) => {
                    $("#form-load").toggleClass("d-flex d-none")

                    open(url + '/' + year, '_blank')

                    swal({
                        title: 'Succès',
                        text: 'L\'export est réussi',
                        type: 'success',
                        showCancelButton: false,
                        confirmButtonClass: 'bg-success',
                        confirmButtonText: '@lang('Ok')',
                    })

                })
            })

            $('.pick-date').click(function () {

                let that = $(this), id = (that.attr('id') || 'li-').split('li-')[1] || null,
                    modal = $('#pickDateModal'), cat = that.attr('data-cat')

                axios.get('/category/' + (cat || -1)).then(r => {
                    modal.find('select#category').val(r.data.id || -1)
                    modal.find('input#start').val(id)
                    modal.find('input#stop').val(id)
                    modal.modal('show')
                }, () => {
                    modal.find('input#start').val(id)
                    modal.find('input#stop').val(id)
                    modal.modal('show')
                })

            })
        })
    </script>

@endsection

@section('stylesheet')

    <style>

        #legend .type span {
            font-family: 'Raleway', sans-serif;
        }

        *:focus {
            outline: none;
        }

        .ring-left, .ring-right {
            position: absolute;
            top: 75px;
        }

        .ring-left {
            left: 2em;
        }

        .ring-right {
            right: 2em;
        }

        .ring-left:before, .ring-left:after, .ring-right:before, .ring-right:after {
            background: #fff;
            border-radius: 4px;
            box-shadow: 0 3px 1px rgba(0, 0, 0, 0.3), 0 -1px 1px rgba(0, 0, 0, 0.2);
            content: "";
            display: inline-block;
            margin: 8px;
            height: 32px;
            width: 8px;
        }

        #calendar .box .header {
            position: absolute;
            /*width: 50%;*/
            top: -100px;
            right: 2em;
        }

        #calendar .box-content li.active:not(.mask).F span,
        #legend .type.F span {
            background: #dc3545 !important;
        }

        #calendar .box-content li.active:not(.mask).CP span,
        #legend .type.CP span {
            background: #28a745 !important;
        }

        #calendar .box-content li.active:not(.mask).AM span,
        #legend .type.AM span {
            background: #00695c !important;
        }

        #calendar .box-content li.active:not(.mask).R span,
        #legend .type.R span {
            background: #ffc107 !important;
        }

        #calendar .box-content li.active:not(.mask).TT span,
        #legend .type.TT span {
            background: #aa66cc !important;
        }

        #calendar .box-content li.active:not(.mask).CS span,
        #legend .type.CS span {
            background: #ef6c00 !important;
        }

        .calendar-header {
            height: 100px;
        }

        .clock-container {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            height: 100%;
            width: 100%;
            background-color: rgba(255, 255, 255, 1);
            z-index: 3;
        }

        .clock,
        .event {
            position: relative;
            height: 200px;
            width: 200px;
            box-sizing: border-box;
            /*border-radius: 100%;*/
            /*border: 5px solid #A9A9A9;*/
            display: block;
            margin: auto;
            z-index: 1;
        }

        .event {
            max-width: 50%;
        }

        a:hover,
        a:focus {
            color: inherit;
            text-decoration: none !important;
        }

        .clock .hour,
        .clock .hours,
        .clock .minutes,
        .clock .seconds,
        .clock .date,
        .clock .center,
        .clock .center-empty {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
        }

        .clock .hours,
        .clock .minutes,
        .clock .seconds {
            -webkit-transform-origin: bottom;
            -moz-transform-origin: bottom;
            -ms-transform-origin: bottom;
            -o-transform-origin: bottom;
            transform-origin: bottom;
            -webkit-transition: all 1s;
            -moz-transition: transform 1s;
            -ms-transition: transform 1s;
            -o-transition: transform 1s;
            transition: transform 1s;
        }

        .hour {
            margin: auto;
            width: 1px;
            height: 100%;
            background-color: #A9A9A9;
            z-index: 2;
        }

        .hour.hour-3, .hour.hour-6 {
            width: 3px;
        }

        .hour.hour-1 {
            -webkit-transform: rotate(30deg);
            -moz-transform: rotate(30deg);
            -ms-transform: rotate(30deg);
            -o-transform: rotate(30deg);
            transform: rotate(30deg);
        }

        .hour.hour-2 {
            -webkit-transform: rotate(60deg);
            -moz-transform: rotate(60deg);
            -ms-transform: rotate(60deg);
            -o-transform: rotate(60deg);
            transform: rotate(60deg);
        }

        .hour.hour-3 {
            -webkit-transform: rotate(90deg);
            -moz-transform: rotate(90deg);
            -ms-transform: rotate(90deg);
            -o-transform: rotate(90deg);
            transform: rotate(90deg);
        }

        .hour.hour-4 {
            -webkit-transform: rotate(120deg);
            -moz-transform: rotate(120deg);
            -ms-transform: rotate(120deg);
            -o-transform: rotate(120deg);
            transform: rotate(120deg);
        }

        .hour.hour-5 {
            -webkit-transform: rotate(150deg);
            -moz-transform: rotate(150deg);
            -ms-transform: rotate(150deg);
            -o-transform: rotate(150deg);
            transform: rotate(150deg);
        }

        .clock .hours {
            bottom: 40px;
            height: 40px;
            width: 3px;
            z-index: 4;
        }

        .clock .minutes {
            bottom: 60px;
            height: 60px;
            width: 3px;
            z-index: 4;
        }

        .clock .seconds {
            bottom: 65px;
            height: 65px;
            width: 1px;
            z-index: 4;
        }

        .clock .date {
            height: 15px;
            width: 20px;
            right: 40px;
            z-index: 3;
        }

        .clock .center-empty {
            height: 125px;
            width: 125px;
            z-index: 3;
        }

        .clock .center {
            height: 5px;
            width: 5px;
            z-index: 3;
        }

        .mcol-7 {
            position: relative;
            width: 100%;
            min-height: 1px;
            flex: 0 0 14.285714%;
            max-width: 14.285714%;
        }

        #calendar li {
            list-style-type: none;
        }

        #calendar .box .header {

        }

        #calendar .box-content li,
        #legend .type {
            position: relative;
            height: 75px;
        }

        #calendar .box-content li span,
        #legend .type span {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            display: block;
            margin: auto;
            height: 28px;
            width: 28px;
            padding: 4px;
            border-radius: 100%;
            font-weight: 700;
            text-align: center;
            box-sizing: content-box;
        }

        #calendar .box-content li.active:not(.mask) span {
            background-color: #17a2b8;
            color: #fff;
        }

        #calendar .box-content li.we:not(.mask) span {
            background: #FF3B3F;
            background-image: repeating-linear-gradient(-45deg, #fff 3px, #fff 6px, #FF3B3F 7px, #FF3B3F 14px);
        }

        #calendar .box-content li.out:not(.mask) span {
            background: #ff0;
            background-image: repeating-linear-gradient(-45deg, #fff 3px, #fff 6px, #ff0 7px, #ff0 14px)
        }

        #calendar .box-content li.half:not(.mask) span {
            border: 2px dotted #000;
        }

    </style>

@endsection