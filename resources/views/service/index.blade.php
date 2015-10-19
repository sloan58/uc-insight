@extends('app')

@section('content')

    <div class="container-fluid push-down">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Service Status Report</h3>
                    </div>
                    <div class="panel-body">
                        This report will be run against the active CUCM Cluster and return a list of all nodes and service status.  Please select 'Confirm' below to run the report.
                    </div>
                </div>
                {!! Form::open(['route' => 'service.store']) !!}

                        <!-- Submit Form Input -->
                <div class="form-group">
                    {!! Form::submit('Confirm', ['class' => 'btn btn-primary btn-lg btn-block']) !!}
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>

@endsection