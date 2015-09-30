{!! Form::open(['route' => 'registration.store']) !!}

<!--  Form Input -->
<div class="form-group">
    {!! Form::label('Cluster Selector','Cluster Selector') !!}
    {!! Form::select('cluster', $clusters) !!}
</div>

<!-- Submit Form Input -->
<div class="form-group">
    {!! Form::submit('Submit', ['class' => 'btn btn-primary form-control']) !!}
</div>

{!! Form::close() !!}
