@extends('app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">{{$user->name}} User Settings</div>
                    <div class="panel-body">

                        {!! Form::model($user, ['route' => ['user.update', $user->name], 'method' => 'PUT']) !!}

                        {!! Form::open(['route' => 'user.store']) !!}

                                @include('user.partials.form')

                        {!! Form::close() !!}



                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
