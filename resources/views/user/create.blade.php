@extends('app')

@section('content')
<div class="col-md-8 col-md-offset-2 table-top">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title pull-left">New User Setting</h3>
        </div>
        <div class="box-body">
            {!! Form::open(['route' => 'user.store']) !!}

                @include('user.partials.form')

            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection