@extends('app')

@section('content')

    @if(isset($clusterStatus))
        <div class="row">
        <div class="col-sm-12">
            <table id="sql-table" class="table table-striped row-border">
                <thead>
                    <tr>
                       <th>Node</th>
                       <th>Service</th>
                       <th>Status</th>
                       <th>StartTime</th>
                       <th>UpTime</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clusterStatus as $key => $val)
                        @foreach($val as $service)
                            <tr>
                                <td>{{$key}}</td>
                                <td>{{$service->ServiceName}}</td>
                                <td>{{$service->ServiceStatus}}</td>
                                <td>{{$service->StartTime}}</td>
                                <td>{{gmdate("H:i:s", $service->UpTime)}}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

@stop

@section('scripts')
    <script>
        // DataTable
        $(function() {
            $("#sql-table").DataTable({
                order: [[0, "asc"]],
                dom: '<"top">Bfrt<"bottom"lip><"clear">',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ],
                "pageLength": 50
            });
        });
    </script>
@stop