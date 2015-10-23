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
    {!! Form::password('password', ['class' => 'form-control', 'placeholder' => '********************']) !!}
</div>
<!-- Version Form Input -->
<div class="form-group">
    {!! Form::label('Version','Version') !!}
    {!! Form::select('version', $versions , $version) !!}
</div>
<!-- Verify Peer Form Input -->
<div class="form-group">
    {!! Form::label('Verify Peer','Verify Peer') !!}
    {!! Form::checkbox('verify_peer', 'verify_peer') !!}
</div>
<!-- Active Form Input -->
<div class="form-group">
    {!! Form::label('Active','Active') !!}
    {!! Form::checkbox('active', 'active', $active) !!}
</div>
<!--  Form Input -->
<div class="form-group">
    {!! Form::submit('Submit', ['class' => 'btn btn-primary form-control']) !!}
</div>