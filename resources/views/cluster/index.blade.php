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
                <table id="cluster-table" class="table table-striped table-bordered dt-responsive nowrap">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Cluster Name</th>
                        <th>Active Cluster</th>
                        <th>Publisher IP</th>
                        <th>Username</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($clusters as $cluster)
                        <tr>
                           <td>{{$cluster->id}}</td>
                           <td>{{$cluster->name}}</td>
                            <td>{{\Auth::user()->clusters_id == $cluster->id ? 'Active' : ''}}</td>
                            <td>{{$cluster->ip}}</td>
                            <td>{{$cluster->username}}</td>
                            <!-- we will also add show, edit, and delete buttons -->
                            <td>
                                <!-- edit this Cluster -->
                                <a href="{!! route('cluster.edit', $cluster->id) !!}" title="Edit Cluster"><i class="fa fa-pencil-square-o fa-2x enabled"></i></a>

                                <!-- delete this User -->
                                <a href="" data-toggle="modal" data-target="#modal-delete" title="Delete Clustetr"><i class="fa fa-trash-o fa-2x enabled deletable"></i></a>
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
                        Are you sure you want to delete the cluster {{$cluster->name}}?
                    </p>
                </div>
                <div class="modal-footer">
                    <form method="POST" action="{{ route('cluster.destroy', $cluster->id) }}">
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
                "aoColumnDefs": [{
                    "aTargets": [0],
                    "sClass": "hiddenID"
                },
                {
                    "aTargets": [2]
                }, ]
            });

        })
    </script>
@stop