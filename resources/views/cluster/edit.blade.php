@extends('app')

@section('content')

<div class="col-md-8 col-md-offset-2 table-top">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title pull-left">{{$cluster->name}} Settings</h3>
        </div>
        {!! Form::model($cluster, ['route' => ['cluster.update', $cluster->id], 'method' => 'PUT']) !!}

        {!! Form::open(['route' => 'cluster.store']) !!}
        <div class="box-body">

            {!! Form::open(['route' => 'cluster.store']) !!}
                @include('cluster.partials.form', [
                    'active' => \Auth::user()->clusters_id == $cluster->id ? true : false,
                    'version' => $cluster->version,
                    'userType' => $cluster->user_type
                ])
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
                    Are you sure you want to delete this Cluster?
                </p>
            </div>
            <div class="modal-footer">
                <form method="POST" action="{{ route('cluster.destroy', $cluster->id) }}">
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