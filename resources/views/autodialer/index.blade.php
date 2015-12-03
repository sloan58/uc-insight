@extends('app')

@section('content')

    <div class="container-fluid table-top">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Send an Automated Call</h3>
                    </div>
                    <div class="panel-body">

                        {!! Form::open(['route' => 'autodialer.store']) !!}

                            <div class="form-group">
                                {!! Form::label('Phone Number','Phone Number') !!}
                                {!! Form::text('number', null, ['class' => 'form-control']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('What Should We Say?','What Should We Say?') !!}
                                {!! Form::textarea('say', null, ['class' => 'form-control']) !!}
                            </div>

                            <!--  Form Submit -->
                            <div class="form-group">
                                {!! Form::submit('Submit', ['class' => 'btn btn-primary form-control']) !!}
                            </div>

                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop