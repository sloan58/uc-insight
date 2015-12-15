@extends('app')

@section('content')

<div class="col-md-10 col-md-offset-1 table-top">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title pull-left">CDR List</h3>
        </div>
        <div class="box-body">
            <table id="cdr-table" class="table">
                <thead>
                <th data-field="dialed" data-sortable="true">Dialed Number</th>
                <th data-field="callerid" data-sortable="true">Caller ID</th>
                <th data-field="type" data-sortable="true">Call Type</th>
                <th data-field="message" data-sortable="true">Message</th>
                <th data-field="successful" data-sortable="true">Result</th>
                <th data-field="reason" data-sortable="true">Fail Reson</th>
                <th data-field="timestamp" data-sortable="true">Timestamp</th>
                </thead>
                <tbody>
                @if(isset($cdrs))
                @foreach($cdrs as $cdr)
                <tr>
                    <td>{{$cdr->dialednumber}}</td>
                    <td>{{$cdr->callerid}}</td>
                    <td>{{$cdr->calltype}}</td>
                    <td>{{$cdr->message}}</td>
                    <td>{{$cdr->successful ? 'Success' : 'Fail'}}</td>
                    <td>{{$cdr->failurereason}}</td>
                    <td>{{$cdr->created_at->format('m-d-Y H:i:s')}}</td>
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
            $("#cdr-table").DataTable({
                order: [[0, "asc"]],
                dom: '<"top">frt<"bottom"lip><"clear">',
            });
        });

    </script>

    @stop