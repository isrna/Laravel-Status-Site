<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- For IE 9 and below. ICO should be 32x32 pixels in size -->
    <!--[if IE]><link rel="shortcut icon" href="/favicon.ico"><![endif]-->

    <!-- Touch Icons - iOS and Android 2.1+ 180x180 pixels in size. -->
    <link rel="apple-touch-icon-precomposed" href="/apple-touch-icon-precomposed.png">

    <!-- Firefox, Chrome, Safari, IE 11+ and Opera. 196x196 pixels in size. -->
    <link rel="icon" href="/favicon.png">

    <title>{{ config('app.name', 'Service Status') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.min.js') }}" defer></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.min.css') }}" rel="stylesheet">
</head>
<body>
<div class="container px-0">
    @include('layouts.navigation')
    <div class="container-fluid">
        {!! $notice !!}

        @foreach($modules as $module)
            <div class="card mb-3">
                <div class="card-body">
                    {!! $module->statusText() !!}
                    <h5 class="card-title {{ $module->timeline_plugin || $module->response_plugin ? '' : 'mb-0' }}">{{ $module->Name }}</h5>
                        @if($module->timeline_plugin)
                            <div class="container-fluid p-0">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1038 35" preserveAspectRatio="xMidYMid meet">
                                    @php
                                        $i = 0
                                    @endphp
                                    @foreach($module->timelineData() as $item)
                                        @if($item[0] == 0)
                                            <a data-toggle="popover" data-html="true" data-container="body" id="test"
                                               class="success"
                                               data-trigger="hover"
                                               data-content="<b>{{ $item[1] }}</b><hr class='m-1'>No downtime recorded on this day."
                                               data-placement="bottom">
                                                <rect x="{{ $i }}" y="0" width="8" height="35"></rect>
                                            </a>
                                        @else
                                            <a data-toggle="popover" data-html="true" data-container="body" id="test"
                                               class="{{ $item[2]->max('status') == 1 ? 'warning' : 'danger' }}"
                                               data-trigger="hover"
                                               data-content="<b>{{ $item[1] }}</b><hr class='m-0 mt-1'>@foreach($item[2] as $incident){{'<div class="d-flex justify-content-between p-2 mt-2 mb-2 bg-secondary text-dark">'.( $incident->status == 1 ? '<span>Partial outage</span>' : '<span>Major outage</span>').(($incident->resolved) ? ' <b>'.$incident->duration().'</b>' : '&nbsp;<b>at '.$incident->created_at->format('H:i').'</b>').'</div><div>'.$incident->title.'</div>'}}@endforeach"
                                               data-placement="bottom">
                                                <rect x="{{ $i }}" y="0" width="8" height="35"></rect>
                                            </a>

                                        @endif


                                            @php
                                                $i += 17.17
                                            @endphp
                                    @endforeach
                                </svg>
                            </div>

                        <div class="d-flex justify-content-between">
                            <div class="p-2 bd-secondary">60 days ago</div>
                            <div class="flex-fill align-self-center"><hr></div>
                            <div class="p-2 bd-secondary">100% up time</div>
                            <div class="flex-fill align-self-center"><hr></div>
                            <div class="p-2 bd-secondary">Today</div>
                        </div>
                        @endif

                        @if($module->timeline_plugin && $module->response_plugin)
                            <br>
                        @endif

                        @if($module->response_plugin)
                                <span class="float-right">{{ $module->responseTime().'ms' }}</span>
                                <h5 class="card-title">Response Time</h5>
                                <div style="height: 120px;">
                                    <canvas id="{{ $module->code() }}graph"></canvas>
                                </div>


                                @php
                                $responsetimes = $module->responseTimes;
                                @endphp
                            @section('scripts')
                                @parent
                                    var data = {
                                            labels: [@foreach($responsetimes as $time) '{!! $time->getTime() !!}', @endforeach],
                                            datasets: [{
                                                label: 'ms',
                                                data: [ @foreach($responsetimes as $response) {!! $response->response_time !!}, @endforeach],
                                                borderColor: '#007bff',
                                                pointBackgroundColor: '#32383e',
                                                pointBorderColor: '#AAAAAA',
                                                fill: false,
                                                borderWidth: 1
                                            }]
                                        }

                                    new Chart(document.getElementById('{!! $module->code() !!}graph'), {
                                        type: 'line',
                                        data: data,
                                        options: globalCharjsOptions
                                    });
                            @endsection
                        @endif
                </div>
            </div>
        @endforeach
        <div>
            <h5 class="text-secondary mt-4">PAST INCIDENTS</h5>
        @foreach($notices as $notice)
            <h4>{{ \Carbon\Carbon::createFromDate($notice[1])->format('M d Y') }}</h4>
            <hr class="mt-0">
            @if($notice[0] == 0)
                <p>No incidents reported today.</p>
            @else
            @foreach($notice[2] as $notice2)
                <h5 class="mb-0"><b>{{ $notice2->title }}</b></h5>
                        {!! $notice2->notice !!}
            @endforeach
            @endif
        @endforeach

            {{ $notices->links('layouts.pagination.homeIncidents') }}
        </div>
    </div>
</div>

@include('layouts.footer')

@hasSection('scripts')
<script type="application/javascript">
    window.onload = function () {
    @yield('scripts')
        };
</script>
@endif
</body>
</html>
