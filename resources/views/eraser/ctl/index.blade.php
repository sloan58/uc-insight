@extends('app')

@section('content')
<div class="col-md-10 col-md-offset-1 table-top">
         <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title pull-left">CTL List</h3>
            </div>
            <div class="box-body"> 
                <table id="ctls-table" class="table">
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
                @foreach ($ctls as $ctl)
                @if(!$ctl->failure_reason)
                    {{$ctl->failure_reason == 'Passed'}}
                @endif
                <tr>
                    <td>{{ $ctl->phone->mac }}</td>
                    <td>{{ $ctl->phone->description}}</td>
                    <td>{{ $ctl->ip_address}}</td>
                    <td >
                        <i class="{{ $ctl->result == 'Success' ? 'fa fa-check' : 'fa fa-times' }}"></i>
                    </td>
                    <td>{{ $ctl->failure_reason}}</td>
                    <td>
                        {{ $ctl->updated_at->toDayDateTimeString() }}
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@stop

@include('eraser.ctl.layout.modal')

@section('scripts')
<script>

    function erase_ctl() {
      $("#modal-erase-ctl").modal("show");
    }
    // DataTable
    $(function() {
        $("#ctls-table").DataTable({
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