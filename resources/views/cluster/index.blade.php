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
                        <th></th>
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
                                <!-- edit this nerd (uses the edit method found at GET /nerds/{id}/edit -->
                                <a class="btn btn-small btn-info" href="{{ URL::to('cluster/' . $cluster->id . '/edit') }}">Edit this Cluster</a>
                            </td>
                            <td>
                                <div class="col-md-4">
                                    {!! Form::open(['url' => 'cluster/' . $cluster->id, 'class' => 'pull-right']) !!}
                                    {!! Form::hidden('_method', 'DELETE') !!}
                                    {!! Form::submit('Delete this Cluster', ['class' => 'btn btn-small btn-danger']) !!}
                                    {!! Form::close() !!}
                                </div>
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