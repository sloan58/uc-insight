@extends('app')

@section('title')
    UC-Insight - SQL Query Tool
@stop

@section('content')

<div class="container-fluid table-top">
    <div class="row sql-box">
        <div class="col-md-8 col-md-offset-3">
            <form method="POST" action="/sql"
                    class="form-horizontal">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                  <div class="form-group">
                    <div class="col-sm-8">
                      <textarea type="textarea" id="sqlStatement" name="sqlStatement" placeholder="Enter SQL Statement Here..."
                             class="form-control">{{ $sql or '' }}</textarea>
                        @if(\Auth::user()->hasRole(['Administrator', 'SQL Admin']))
                            <button type="submit" class="btn btn-primary btn-lg btn-block">
                                Submit Query
                            </button>
                        @endif
                    </div>
                  </div>
            </form>
         </div>
    </div>
</div>

@if(isset($data) && $data != '')
<div class="col-md-10 col-md-offset-1 ">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title pull-left"></h3>
        </div>
        <div class="box-body">
            <table id="sql-table" class="table">
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

//       myCodeMirror.setSize(425, 100);
       myCodeMirror.setSize("100%", 300);

</script>
@stop