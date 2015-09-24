@extends('app')

@section('content')

    <div class="row page-title-row">
        <div class="col-md-6">
            <h3>Cluster <small>» Listing</small></h3>
        </div>
        <div class="col-md-6 text-right">
            <a type="button" class="btn btn-success btn-md" href="cluster/create" role="button">
                <i class="fa fa-plus-circle fa-lg"></i>
                Add Cluster
            </a>
        </div>
    </div>

    @if(isset($clusters))
        <div class="row">
            <div class="col-sm-12">
                <table id="cluster-table" class="table table-striped row-border">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cluster Name</th>
                        <th>Publisher IP</th>
                        <th>Username</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($clusters as $row)
                        <tr>
                           <td>{{$row->id}}</td>
                           <td>{{$row->name}}</td>
                           <td>{{$row->ip}}</td>
                           <td>{{$row->username}}</td>
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
                dom: '<"top">Bfrt<"bottom"lip><"clear">',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                "aoColumnDefs": [{
                    "aTargets": [0],
                    "sClass": "hiddenID"
                }, {
                    "aTargets": [1],
                    "bSearchable": false,
                    "bSortable": false,
                    "sClass": "center",
                    "mRender":function(data,type,full){
                        return '<a href="/cluster/' + full[0] + '/edit">' + data + '</a>';
                    }
                }, {
                    "aTargets": [2]
                }, ]
            });

        })
    </script>
@stop