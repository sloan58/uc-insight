@extends('app')

@section('content')

    <div class="col-md-10 col-md-offset-1 table-top">
         <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title pull-left">Users List</h3>
                <a type="button" class="btn btn-md btn-success pull-right" href="user/create" role="button">
                    <i class="fa fa-plus-circle fa-lg"></i>
                    Add User
                </a>
            </div>
            <div class="box-body"> 
                <table id="user-table" class="table">
                    <thead>
                    <th data-field="username">Username</th>
                    <th data-field="email" data-sortable="true">Email Address</th>
                    <th>Actions</th>
                    </thead>
                    <tbody>
                    @if(isset($users))
                        @foreach($users as $user)
                            <tr>
                               <td>{{$user->name}}</td>
                               <td>{{$user->email}}</td>
                                <td>
                                    <!-- edit this User -->
                                    <a href="{!! route('user.edit', $user->name) !!}" title="Edit User"><i class="fa fa-pencil-square-o fa-2x enabled editable"></i></a>

                                    <!-- delete this User -->
                                    <a href="{!! route('user.destroy', $user->name) !!}" data-toggle="modal" data-target="#modal-delete" title="Delete User"><i class="fa fa-trash-o fa-2x enabled deletable"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                        </tbody>
                </table>
            </div> 
        </div> 

            

    @if(isset($users))
    {{-- Confirm Delete --}}
    <div class="modal fade" id="modal-delete" tabIndex="-1">
        <div class="modal-dialog">
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
                        Are you sure you want to delete this user?
                    </p>
                </div>
                <div class="modal-footer">
                    <form method="POST" action="{{ route('user.destroy', $user->name) }}">
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
    @endif
@stop

@section('scripts')
    <script type="text/javascript">
    // DataTable
    $(function() {
        $("#user-table").DataTable({
            order: [[0, "asc"]],
            dom: '<"top">frt<"bottom"lip><"clear">',
        });
    });
    </script>
@stop