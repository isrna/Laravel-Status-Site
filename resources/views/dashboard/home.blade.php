@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        @if($errors->any())
        <div class="col-12">
            <div class="alert alert-danger" role="alert">
                {!! implode('', $errors->all('<div>:message</div>')) !!}
            </div>
        </div>
        @endif
        <div class="col-3">
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <a class="nav-link mb-1 active" id="v-pills-home-tab" data-toggle="pill" href="#v-pills-home" role="tab" aria-controls="v-pills-home" aria-selected="true">Home</a>
                <a class="nav-link mb-1" id="v-pills-incidents-tab" data-toggle="pill" href="#v-pills-incidents" role="tab" aria-controls="v-pills-incidents" aria-selected="false">Incidents</a>
            </div>
        </div>
        <div class="col-9">
            <div class="tab-content" id="v-pills-tabContent">
                <div class="tab-pane fade show active" id="v-pills-home" role="tabpanel" aria-labelledby="v-pills-home-tab">
                    <div class="card">
                        <div class="card-header">Dashboard</div>

                        <div class="card-body">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif

                            You are logged in!
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="v-pills-incidents" role="tabpanel" aria-labelledby="v-pills-incidents-tab">
                    <form method="get" action="{{ url('loadincident') }}" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="input-group mb-3 col-md-5">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">Module</span>
                                </div>
                                <select class="custom-select" name="moduleid">
                                    <option selected disabled hidden value="0">Select</option>
                                    @foreach($modules as $module)
                                    <option value="{{ $module->id }}">{{ $module->Name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="input-group mb-3 col-md-5">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">Date</span>
                                </div>
                                <input class="date form-control"  type="text" id="datepicker" name="date" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="form-group col-md-5">
                                <button type="submit" class="btn btn-success w-100">Load</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
    <script type="text/javascript">
        $('#datepicker').datepicker({
            format:'mm/dd/yyyy',
            autoclose: true
        }).datepicker("setDate",'now');
    </script>
@endsection
