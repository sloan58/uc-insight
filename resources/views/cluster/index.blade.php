@extends('app')

@section('content')

    <div class="col-md-10 col-md-offset-1 table-top">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title pull-left">Clusters List</h3>
                <a type="button" class="btn btn-md btn-success pull-right" href="cluster/create" role="button">
                    <i class="fa fa-plus-circle fa-lg"></i>
                    Add Cluster
                </a>
            </div>
            <div class="box-body"> 
                <table id="user-table" class="table">
                <thead>
                <th data-field="name" data-sortable="true">Name</th>
                <th data-field="active" data-sortable="true">Active</th>
                <th data-field="ip" data-sortable="true">IP Address</th>
                <th data-field="username">Username</th>
                <th>Actions</th>
                </thead>
                <tbody>
                @if(isset($clusters))
                    @foreach($clusters as $cluster)
                    <tr>
                        <td>{{$cluster->name}}</td>
                        <td>{{\Auth::user()->clusters_id == $cluster->id ? 'Active' : ''}}</td>
                        <td>{{$cluster->ip}}</td>
                        <td>{{$cluster->username}}</td>
                        <td>
                            <!-- edit this Cluster -->
                            <a href="{!! route('cluster.edit', $cluster->id) !!}" title="Edit Cluster"><i class="fa fa-pencil-square-o fa-2x enabled editable"></i></a>

                            <!-- delete this User -->
                            <a href="{!! route('cluster.destroy', $cluster->id) !!}" data-toggle="modal" data-target="#modal-delete" title="Delete Clustetr"><i class="fa fa-trash-o fa-2x enabled deletable"></i></a>
                        </td>
                    </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('scripts')

    <script type="text/javascript">
         // DataTable
    $(function() {
        $("#cluster-table").DataTable({
            order: [[0, "asc"]],
            dom: '<"top">frt<"bottom"lip><"clear">',
        });
    });

    </script>

@stop