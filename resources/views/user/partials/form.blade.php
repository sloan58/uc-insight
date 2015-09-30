<!-- Username Form Input -->
<div class="form-group">
    {!! Form::label('Username','Username') !!}
    {!! Form::text('name', null, ['class' => 'form-control']) !!}
</div>
<!-- Email Address Form Input -->
<div class="form-group">
    {!! Form::label('Email Address','Email Address') !!}
    {!! Form::text('email', null, ['class' => 'form-control']) !!}
</div>
<!-- Password Form Input -->
<div class="form-group">
    {!! Form::label('Password','Password') !!}
    {!! Form::password('password', ['class' => 'form-control']) !!}
</div>
<!-- Confirm Password Form Input -->
<div class="form-group">
    {!! Form::label('Password','Password') !!}
    {!! Form::password('password', ['class' => 'form-control']) !!}
</div>
<!-- Roles Form Input -->
<div class="form-group">
    {!! Form::label('Roles','Roles') !!}
    {!! Form::select('roles', $roles, null, ['class' => 'form-control', 'multiple']) !!}
</div>
<!--  Form Input -->
<div class="form-group">
    {!! Form::submit('Submit', ['class' => 'btn btn-primary form-control']) !!}
</div>