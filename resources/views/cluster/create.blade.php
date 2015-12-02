@extends('app')

@section('content')

    <div class="container-fluid table-top">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">New Cluster Settings</h3>
                    </div>
                    <div class="panel-body">

                        {!! Form::open(['route' => 'cluster.store']) !!}

                            @include('cluster.partials.form', ['active' => false, 'version' => '10.5', 'userType' => 'application'])

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection