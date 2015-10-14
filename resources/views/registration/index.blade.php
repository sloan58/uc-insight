@extends('app')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Device Registration Report</h3>
                    </div>
                    <div class="panel-body">
                        This report will be run against the active CUCM Cluster and return a list of all devices and their registration status.  Please select 'Confirm' below to run the report.
                    </div>
                </div>
                <div class=" col-md-4 col-md-offset-4">
                    {!! Form::open(['route' => 'registration.store']) !!}

                            <!-- Submit Form Input -->
                    <div class="form-group">
                        {!! Form::submit('Confirm', ['class' => 'btn btn-primary form-control']) !!}
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

@endsection