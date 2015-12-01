@extends('app')

@section('content')


    <div class="container-fluid table-top">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title pull-left">Service Status Report</h3>

                        {!! Form::open(['route' => 'service.store']) !!}

                        <!-- Submit Form Input -->
                        <div class="form-group remove-bottom-margin">
                            {!! Form::submit('Confirm', ['class' => 'btn btn-default pull-right ']) !!}
                        </div>

                        {!! Form::close() !!}

                        <div class="clearfix"></div>

                    </div>
                    <div class="panel-body">
                        This report will be run against the active CUCM Cluster and return a list of all nodes and service status.  Please select 'Confirm' to run the report.
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection