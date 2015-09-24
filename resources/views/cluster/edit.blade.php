@extends('app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">{{$cluster->name}} Settings</div>
                    <div class="panel-body">

                        {!! Form::model($cluster, ['route' => ['cluster.update', $cluster->id], 'method' => 'PUT']) !!}

                            <!-- Cluster Name Form Input -->
                            <div class="form-group">
                                {!! Form::label('Cluster Name','Cluster Name') !!}
                                {!! Form::text('name', null, ['class' => 'form-control']) !!}
                            </div>
                            <!-- IP Address Form Input -->
                            <div class="form-group">
                                {!! Form::label('IP Address','IP Address') !!}
                                {!! Form::text('ip', null, ['class' => 'form-control']) !!}
                            </div>
                            <!-- Username Form Input -->
                            <div class="form-group">
                                {!! Form::label('Username','Username') !!}
                                {!! Form::text('username', null, ['class' => 'form-control']) !!}
                            </div>
                            <!-- Password Form Input -->
                            <div class="form-group">
                                {!! Form::label('Password','Password') !!}
                                {!! Form::password('password', ['class' => 'form-control']) !!}
                            </div>
                            <!--  Form Input -->
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
