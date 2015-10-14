@extends('app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">New Cluster Settings</div>
                    <div class="panel-body">

                        {!! Form::open(['route' => 'cluster.store']) !!}

                            @include('cluster.partials.form', ['active' => false])

                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
