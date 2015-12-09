@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row page-tctle-row">
        <div class="col-md-6">
            <h3>CTL <small>» Listing</small></h3>
        </div>
        <div class="col-md-6 text-right">
            <button type="button" class="btn btn-success btn-md"
                      onclick="erase_ctl()">
                <i class="fa fa-plus-circle fa-lg"></i>
                Erase CTLs
          </button>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">

            <table id="ctls-table" class="table table-striped row-border">
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

@include('ctl.layout.modal')

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