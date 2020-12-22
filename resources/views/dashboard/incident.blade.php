@extends('layouts.app')

@section('content')

<div class="container">
    @if($errors->any())
        <div class="col-12">
            <div class="alert alert-danger" role="alert">
                {!! implode('', $errors->all('<div>:message</div>')) !!}
            </div>
        </div>
    @endif

    @if (session('success'))
    @foreach(session('success') as $y)
        <div class="alert alert-success">
        {{ $y }}
        </div>
    @endforeach
    @endif
@if($found == false)
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Do you want to create a new incident?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    No incidents on {{ $date }} have been found. Do you want to create a new incident?
                </div>
                <div class="modal-footer justify-content-center">
                    <a class="btn btn-secondary" href="{{ url('dashboard') }}">Decline</a>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Accept</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $('#exampleModal').modal('show');
    </script>
@endif
    <div class="row justify-content-center">
        <div class="col-3">
            <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                @if($found == false)
                    <a class="nav-link mb-1 active" id="v-pills-add-incident-tab" data-toggle="pill" href="#v-pills-add-incident" role="tab" aria-controls="v-pills-add-incident" aria-selected="true">Add Incident</a>
                @endif
                @if($found)
                        <a class="nav-link mb-1 active" id="v-pills-incident-tab" data-toggle="pill" href="#v-pills-incident" role="tab" aria-controls="v-pills-incident" aria-selected="true">Incident</a>
                    @if(count($notices) > 0)
                    <a class="nav-link mb-1" id="v-pills-notices-tab" data-toggle="pill" href="#v-pills-notices" role="tab" aria-controls="v-pills-notices" aria-selected="false">Notices</a>
                    @endif
                    @if($found && count($notices) == 0)
                     <a class="nav-link mb-1" id="v-pills-add-notice-tab" data-toggle="pill" href="#v-pills-add-notice" role="tab" aria-controls="v-pills-add-notice" aria-selected="false">Add Notice</a>
                    @endif
                @endif
            </div>
        </div>

        <div class="col-9">
            <div class="tab-content" id="v-pills-tabContent">
                @if($found == false)
                    <div class="tab-pane fade show active" id="v-pills-add-incident" role="tabpanel" aria-labelledby="v-pills-add-incident-tab">
                        <form method="post" action="{{ url('newincident') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="input-group mb-1">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon1">Status</span>
                                </div>
                                <select class="custom-select" name="status">
                                    <option selected disabled hidden value="0">Select</option>
                                    <option value="1">Partial Outage</option>
                                    <option value="2">Major Outage</option>
                                </select>
                            </div>
                            <div class="input-group form-check">
                                <input class="form-check-input" type="checkbox" value="" id="defaultCheck1" name="resolved">
                                <label class="form-check-label" for="defaultCheck1">
                                    Resolved
                                </label>
                            </div>
                            <div class="input-group" id="enddatediv">
                                <div class="input-group-prepend mt-1">
                                    <span class="input-group-text" id="basic-addon1">End date</span>
                                </div>
                                <input class="date form-control mt-1"  type="text" id="datepicker" name="enddate">
                            </div>
                            <input type="number" hidden value="{{ $moduleid }}" name="moduleid">
                            <input type="text" hidden value="{{ $date }}" name="date">
                            <div class="bg-white text-dark">
                                <textarea name="text" name="text"></textarea>
                            </div>
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-success">Add</button>
                            </div>
                        </form>
                    </div>

                    <script>
                        $('#datepicker').datepicker({
                            format:'mm/dd/yyyy',
                            autoclose: true
                        });
                    </script>
                @endif


                @if($found)
                <div class="tab-pane fade show active" id="v-pills-incident" role="tabpanel" aria-labelledby="v-pills-incident-tab">
                    <form method="post" action="{{ url('updateincident') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="number" hidden value="{{ $incidents[0]->id }}" name="incidentid">
                        <div class="input-group mb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">Status</span>
                            </div>
                            <select class="custom-select" name="status">
                                <option {{ $incidents[0]->status == 0 ? 'selected' : '' }} disabled hidden value="0">Select</option>
                                <option {{ $incidents[0]->status == 1 ? 'selected' : '' }} value="1">Partial Outage</option>
                                <option {{ $incidents[0]->status == 2 ? 'selected' : '' }} value="2">Major Outage</option>
                            </select>
                        </div>
                        <div class="input-group form-check">
                            <input class="form-check-input" type="checkbox" value="" id="defaultCheck1" name="resolved" {{ $incidents[0]->resolved ? 'checked' : '' }}>
                            <label class="form-check-label" for="defaultCheck1">
                                Resolved
                            </label>
                        </div>
                        <div class="input-group" id="enddatediv">
                            <div class="input-group-prepend mt-1">
                                <span class="input-group-text" id="basic-addon1">End date</span>
                            </div>
                            <input class="date form-control mt-1"  type="text" id="datepicker" value="{{ \Carbon\Carbon::create($incidents[0]->ended_at)->format('m/d/Y') }}" name="enddate">
                        </div>
                        <div class="bg-white text-dark">
                            <textarea name="text">{{ $incidents[0]->title }}</textarea>
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </form>
                    <script>
                        $('#datepicker').datepicker({
                            format:'mm/dd/yyyy',
                            autoclose: true
                        });
                    </script>
                </div>

                @if(count($notices) > 0)
                <div class="tab-pane fade" id="v-pills-notices" role="tabpanel" aria-labelledby="v-pills-notices-tab">
                    <form method="post" action="{{ url('updatenotice') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="number" hidden value="{{ $notices[0]->id}}" name="noticeid">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">Title</span>
                            </div>
                            <input type="text" class="form-control" value="{{ $notices[0]->title}}" name="title">
                        </div>
                        <div class="bg-white text-dark">
                            <textarea name="text">{{ $notices[0]->notice }}</textarea>
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </form>
                </div>
                @endif
                @if($found)
                <div class="tab-pane fade" id="v-pills-add-notice" role="tabpanel" aria-labelledby="v-pills-add-notice-tab">
                    <form method="post" action="{{ url('newnotice') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="number" hidden value="{{ $moduleid }}" name="moduleid">
                        <input type="number" hidden value="{{ $incidents[0]->id }}" name="incidentid">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">Title</span>
                            </div>
                            <input type="text" class="form-control" name="title">
                        </div>
                        <div class="bg-white text-dark">
                            <textarea name="text"></textarea>
                        </div>
                        <div class="form-group text-center">
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </form>
                </div>
                @endif



                @endif
            </div>
        </div>

    </div>

<script>
    $('textarea').trumbowyg({
        btns: [
            ['undo', 'redo'],
            ['bold', 'italic'],
            ['link']
        ]
    });
</script>
</div>
@endsection
