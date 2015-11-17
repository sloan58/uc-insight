@extends('app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">{{$cluster->name}} Settings</div>
                    <div class="panel-body">
                        {!! Form::model($cluster, ['route' => ['cluster.update', $cluster->id], 'method' => 'PUT']) !!}

                            {!! Form::open(['route' => 'cluster.store']) !!}
                                @include('cluster.partials.form', [
                                'active' => \Auth::user()->clusters_id == $cluster->id ? true : false,
                                'version' => $cluster->version,
                                'userType' => $cluster->user_type
                                ])

                            {!! Form::close() !!}

                        {!! Form::close() !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
