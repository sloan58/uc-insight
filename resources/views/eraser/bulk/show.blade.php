@extends('app')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">

            <table id="bulk-table" class="table table-striped row-border">
                <thead>
                <tr>
                    <th>Phone Name</th>
                    <th>IP Address</th>
                    <th>Type</th>
                    <th>Result</th>
                    <th>Fail Reason</th>
                    <th>Sent On</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($bulk->erasers as $eraser)
                <tr>
                    <td>{{ $eraser->phone->mac }}</td>
                    <td>{{ $eraser->ip_address }}</td>
                    <td>{{ $eraser->eraser_type }}</td>
                    <td >
                        <i class="{{ $eraser->result == 'Success' ? 'fa fa-check' : 'fa fa-times' }}"></i>
                    </td>
                    <td>{{ $eraser->failure_reason}}</td>
                    <td>{{ $eraser->created_at->toDayDateTimeString()}}</td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@stop

@section('scripts')
<script>

    // DataTable
    $(function() {
        $("#bulk-table").DataTable({
            order: [[2, "desc"]],
            "aoColumnDefs": [
                {
                    "aTargets": [ 0 ], // Column to target
                    "mRender": function ( data, type, full ) {
                        // 'full' is the row's data object, and 'data' is this column's data
                        // e.g. 'full is the row object, and 'data' is the phone mac
                        return '<a href="/phone/' + full[0] + '">' + data + '</a>';
                    }
                }
            ]
        });
    });
</script>
@stop