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
                    <th>ID</th>
                    <th>SQL Statement: Click to re-run queries</th>
                </tr>
            </thead>
            <tbody>
            @foreach($sqls as $sql)
                <tr>
                    <td>{{ $sql->id }}</td>
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
            "aoColumnDefs": [{
                "aTargets": [0],
                "sClass": "hiddenID"
            }, {
                "aTargets": [1],
                "bSearchable": false,
                "bSortable": false,
                "sClass": "center",
                "mRender":function(data,type,full){
                        return '<a href="/sql/' + full[0] + '">' + data + '</a>';
                    }
                }
            ]
        });
    });
</script>
@stop