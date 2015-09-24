@extends('app')

@section('title')
    UC-Insight - SQL Query Tool
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
        <li class="active">SQL Query</li>
      </ol>
    </section>
@stop

@section('content')
<div class="container-fluid">
    <div class="row sql-box">
        <div class="col-md-8 col-md-offset-3">
            <form method="POST" action="/sql"
                    class="form-horizontal">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="folder" value="">
                  <div class="form-group">
                    <div class="col-sm-8">
                      <textarea type="textarea" id="sqlStatement" name="sqlStatement" placeholder="Enter SQL Statement Here..."
                             class="form-control">{{{ $sql or '' }}}</textarea>
                    </div>
                      <button type="submit" class="btn btn-primary">
                        Submit
                      </button>
                  </div>
          </form>
         </div>
    </div>
</div>

@if(isset($data))
<div class="row">
    <div class="col-sm-12">
        <table id="sql-table" class="table table-striped row-border">
            <thead>
            <tr>
            @foreach($format as $header)
                <th>{{ ucfirst($header) }}</th>
            @endforeach
            </tr>
            </thead>
            <tbody>
            @foreach($data as $row)
                <tr>
                @foreach($format as $header)
                    <td>{{ $row->$header }}</td>
                @endforeach
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
            ]
        });
    });

    //Codemirror
       var myCodeMirror = CodeMirror.fromTextArea(sqlStatement, {
         mode: "text/x-mysql",
         lineNumbers: true,
         lineWrapping: true
       });

       myCodeMirror.setSize(425, 100);

</script>
@stop