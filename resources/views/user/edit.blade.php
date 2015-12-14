@extends('app')

@section('content')

    <div class="col-md-10 col-md-offset-1 table-top">
         <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title pull-left">{{$user->name}} User Settings</h3>
            </div>
            {!! Form::model($user, ['route' => ['user.update', $user->name], 'method' => 'PUT']) !!}

            {!! Form::open(['route' => 'user.store']) !!}
            <div class="box-body"> 

                @include('user.partials.form')

            {!! Form::close() !!}
            </div>
        </div>
    </div>

@include('user.partials.delete-modal', ['object' => 'User', 'name' => $user->name])

@endsection