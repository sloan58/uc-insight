@extends('app')

@section('title')
    UC-Insight - SQL History
@stop

@section('sub-header')
<!-- Content Header (Page header) -->
    <section class="content-header">
      {{--<h1>--}}
        {{--Page Header--}}
        {{--<small>Optional description</small>--}}
      {{--</h1>--}}
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li class="active">SQL History</li>
      </ol>
    </section>
@stop

@section('content')
<div class="container-fluid">
    <div class="row page-tctle-row">
        <div class="col-md-6">
            <h3>SQL <small>» History</small></h3>
        </div>
    </div>
</div>

@if(isset($sqls))
<div class="row">
    <div class="col-sm-12">
        <table id="history-table" class="table table-striped row-border">
            <thead>
                <tr>
                    <th>SQL Statement: Click to re-run queries</th>
                </tr>
            </thead>
            <tbody>
            @foreach($sqls as $sql)
                <tr>
                    <td>{{ $sql->sql }}</td>
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
        $("#history-table").DataTable({
            order: [[0, "asc"]],
            "aoColumnDefs": [
                {
                    "aTargets": [ 0 ], // Column to target
                    "mRender": function ( data, type, full ) {
                        // 'full' is the row's data object, and 'data' is this column's data
                        // e.g. 'full is the row object, and 'data' is the phone mac
                        return '<a href="/sql/' + full[0] + '">' + data + '</a>';
                    }
                }
            ]
        });
    });
</script>
@stop