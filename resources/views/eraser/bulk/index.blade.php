@extends('app')

@section('content')
<div class="container-fluid">
    <div class="row page-tctle-row">
        <div class="col-md-6">
            <h3>Bulk <small>» Listing</small></h3>
        </div>
        <div class="col-md-6 text-right">
          <a type="button" class="btn btn-success btn-md" href="{{ route('eraser.bulk.create') }}" role="button">
            <i class="fa fa-plus-circle fa-lg"></i>
            Erase in Bulk
          </a>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">

            <table id="bulks-table" class="table table-striped row-border">
                <thead>
                <tr>
                    <th>Filename</th>
                    <th>Process ID</th>
                    <th>Phones Processed</th>
                    <th>Result</th>
                    <th>Submitted</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($bulks as $bulk)
                <tr>
                    <td>{{ $bulk->file_name }}</td>
                    <td>{{ $bulk->process_id }}</td>
                    <td>{{ $bulk->erasers()->count() }}</td>
                    <td>{{ $bulk->result }}</td>
                    <td>{{ $bulk->created_at->toDayDateTimeString() }}</td>
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
        $("#bulks-table").DataTable({
            order: [[4, "desc"]],
            "aoColumnDefs": [
                {
                    "aTargets": [ 0 ], // Column to target
                    "mRender": function ( data, type, full ) {
                        // 'full' is the row's data object, and 'data' is this column's data
                        // e.g. 'full is the row object, and 'data' is the phone mac
                        return '<a href="/bulk/' + full[1] + '">' + data + '</a>';
                    }
                }
            ]
        });
    });
</script>
@stop