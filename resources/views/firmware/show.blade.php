@extends('app')

@section('content')

    @if(isset($finalReport))
        <div class="row">
        <div class="col-sm-12">
            <table id="sql-table" class="table table-striped row-border">
                <thead>
                    <tr>
                       <th>Device Name</th>
                       <th>Product</th>
                       <th>Description</th>
                       <th>Registration</th>
                       <th>IP Address</th>
                       <th>Model</th>
                       <th>Firmware</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($finalReport as $device)
                        <tr>
                            <td>{{$device['DeviceName']}}</td>
                            <td>{{$device['Product']}}</td>
                            <td>{{$device['Description']}}</td>
                            <td>{{$device['IsRegistered'] ? 'Registered' : 'Unregistered/Unknown'}}</td>
                            <td>{{$device['IpAddress']}}</td>
                            <td>{{$device['Model'] or 'Unavailable'}}</td>
                            <td>{{$device['Firmware']}}</td>
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