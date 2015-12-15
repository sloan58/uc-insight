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

{{-- Confirm Delete Modal--}}
<div class="modal fade" id="modal-delete" tabIndex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    &times;
                </button>
                <h4 class="modal-title">Please Confirm</h4>
            </div>
            <div class="modal-body">
                <p class="lead">
                    <i class="fa fa-question-circle fa-lg"></i> &nbsp;
                    Are you sure you want to delete this User?
                </p>
            </div>
            <div class="modal-footer">
                <form method="POST" action="{{ route('user.destroy', $user->id) }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="button" class="btn btn-default"
                            data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fa fa-times-circle"></i> Yes
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection