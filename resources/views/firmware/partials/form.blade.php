<!-- Device Pool Form Input -->
<div class="form-group">
    {!! Form::label('Device Pool','Device Pool') !!}
    {!! Form::select('devicepool', $devicePoolList , $devicePoolList) !!}
</div>
<!--  Form Input -->
<div class="form-group">
    {!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
</div>