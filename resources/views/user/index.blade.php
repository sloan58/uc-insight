@extends('app')

@section('content')

    <div class="row page-title-row">
        <div class="col-md-6">
            <h3>Cluster <small>» Listing</small></h3>
        </div>
        <div class="col-md-6 text-right">
            <a type="button" class="btn btn-success btn-md" href="user/create" role="button">
                <i class="fa fa-plus-circle fa-lg"></i>
                Add User
            </a>
        </div>
    </div>

    @if(isset($users))
        <div class="row">
            <div class="col-sm-12">
                <table id="cluster-table" class="table table-striped row-border">
                    <thead>
                    <tr>
                        <th>Username</th>
                        <th>Email Address</th>
                        <th>Actions</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                           <td>{{$user->name}}</td>
                           <td>{{$user->email}}</td>
                            <!-- we will also add show, edit, and delete buttons -->
                            <td>

                                <div class="col-md-4">
                                {!! Form::open(['url' => 'user/' . $user->name, 'class' => 'pull-right']) !!}
                                {!! Form::hidden('_method', 'DELETE') !!}
                                {!! Form::submit('Delete this User', ['class' => 'btn btn-small btn-danger']) !!}
                                {!! Form::close() !!}

                                </div>
                                <td>
                                    <!-- edit this nerd (uses the edit method found at GET /nerds/{id}/edit -->
                                    <a class="btn btn-small btn-info" href="{{ URL::to('user/' . $user->name . '/edit') }}">Edit this User</a>
                                </td>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
@stop

@section('scripts')
    <script>
        $(function() {
            $('#cluster-table').dataTable({
                order: [[0, "asc"]],
                dom: '<"top">frt<"bottom"lip><"clear">',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });

        })
    </script>
@stop