@extends('app')

@section('content')

<div class="container-fluid table-top">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3>New User Settings</h3>
                </div>
                <div class="panel-body">
                    {!! Form::open(['route' => 'user.store']) !!}

                    @include('user.partials.form')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
