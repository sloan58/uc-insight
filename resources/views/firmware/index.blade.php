@extends('app')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Choose a Cluster for Firmware Reporting</div>
                    <div class="panel-body text-center">

                        {!! Form::open(['route' => 'firmware.store']) !!}

                        <!--  Form Input -->
                        <div class="form-group">
                            {!! Form::label('Cluster Selector','Cluster Selector') !!}
                            {!! Form::select('cluster', $clusters) !!}
                        </div>

                        <!-- Submit Form Input -->
                        <div class="form-group">
                            {!! Form::submit('Submit', ['class' => 'btn btn-primary form-control']) !!}
                        </div>

                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection