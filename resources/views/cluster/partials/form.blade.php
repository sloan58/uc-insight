<!-- Cluster Name Form Input -->
<div class="form-group">
    {!! Form::label('Cluster Name','Cluster Name') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>
<!-- IP Address Form Input -->
<div class="form-group">
    {!! Form::label('Publisher IP Address','Publisher IP Address') !!}
    {!! Form::text('ip', null, ['class' => 'form-control']) !!}
</div>
<!-- Username Form Input -->
<div class="form-group">
    {!! Form::label('Username','Username') !!}
    {!! Form::text('username', null, ['class' => 'form-control']) !!}
</div>
<!-- Password Form Input -->
<div class="form-group">
    {!! Form::label('Password','Password') !!}
    {!! Form::password('password', ['class' => 'form-control']) !!}
</div>
<!--  Form Input -->
<div class="form-group">
    {!! Form::submit('Submit', ['class' => 'btn btn-primary form-control']) !!}
</div>