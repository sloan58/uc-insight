@extends('app')

@section('content')
<div class="col-md-8 col-md-offset-2 table-top">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title pull-left">New Cluster Settings</h3>
        </div>
        <div class="box-body">
            {!! Form::open(['route' => 'cluster.store']) !!}

            @include('cluster.partials.form', ['active' => false, 'version' => '10.5', 'userType' => 'application'])

            {!! Form::close() !!}
        </div>
    </div>
</div>
@endsection