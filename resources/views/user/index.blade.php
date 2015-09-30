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
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                           <td>{{$user->name}}</td>
                           <td>{{$user->email}}</td>
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
                ],
                "aoColumnDefs": [{
                    "aTargets": [0]
                }, {
                    "aTargets": [0],
                    "bSearchable": false,
                    "bSortable": false,
                    "sClass": "center",
                    "mRender":function(data,type,full){
                        return '<a href="/user/' + full[0] + '/edit">' + data + '</a>';
                    }
                }, {
                    "aTargets": [2]
                }, ]
            });

        })
    </script>
@stop