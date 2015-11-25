@extends('app')

@section('content')

    <div class="row page-title-row">
        <div class="col-md-6">
            <h3>User <small>» Listing</small></h3>
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
                <table id="cluster-table" class="table table-striped table-bordered dt-responsive nowrap">
                    <thead>
                    <tr>
                        <th>Username</th>
                        <th>Email Address</th>
                        <th>Actions</th>
                        {{--<th></th>--}}
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                           <td>{{$user->name}}</td>
                           <td>{{$user->email}}</td>
                            <td>
                                <!-- edit this User -->
                                <a href="{!! route('user.edit', $user->name) !!}" title="Edit User"><i class="fa fa-pencil-square-o fa-2x enabled"></i></a>

                                <!-- delete this User -->
                                <a href="" data-toggle="modal" data-target="#modal-delete" title="Delete User"><i class="fa fa-trash-o fa-2x enabled deletable"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

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
                        Are you sure you want to delete the user {{$user->name}}?
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

@stop

@section('scripts')
    <script>
        $(function() {
            $('#cluster-table').dataTable({
                order: [[0, "asc"]],
                dom: '<"top">frt<"bottom"lip><"clear">',
            });

        })
    </script>
@stop