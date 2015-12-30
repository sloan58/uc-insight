@extends('app')

@section('content')

    <div class="col-md-10 col-md-offset-1 table-top">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title pull-left">Firmware Reporting</h3>
            </div>
            <div class="box-body"> 
            <p>
                This app will check each phone in a device pool and report back on the firmware version it is running.</br>
                Please select a Device Pool below and submit the request.
            </p>
            {!! Form::open(['route' => 'firmware.store']) !!}

                @include('firmware.partials.form', [])

            {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop