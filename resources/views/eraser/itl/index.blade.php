@extends('app')

@section('content')
<div class="col-md-10 col-md-offset-1 table-top">
         <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title pull-left">ITL List</h3>
            </div>
            <div class="box-body"> 
                <table id="itls-table" class="table">
                <thead>
                <tr>
                    <th>Phone Name</th>
                    <th>Phone Description</th>
                    <th>IP Address</th>
                    <th>Result</th>
                    <th>Fail Reason</th>
                    <th>Last Updated At</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($itls as $itl)
                @if(!$itl->failure_reason)
                    {{$itl->failure_reason == 'Passed'}}
                @endif
                <tr>
                    <td>{{ $itl->phone->mac }}</td>
                    <td>{{ $itl->phone->description}}</td>
                    <td>{{ $itl->ip_address}}</td>
                    <td >
                        <i class="{{ $itl->result == 'Success' ? 'fa fa-check' : 'fa fa-times' }}"></i>
                    </td>
                    <td>{{ $itl->failure_reason}}</td>
                    <td>
                        {{ $itl->updated_at->toDayDateTimeString() }}
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@stop

@include('eraser.itl.layout.modal')

@section('scripts')
<script>
    // Confirm file delete
    function erase_itl() {
      $("#modal-erase-itl").modal("show");
    }
    // DataTable
    $(function() {
        $("#itls-table").DataTable({
            order: [[5, "desc"]],
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