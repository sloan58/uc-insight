@extends('app')

@section('content')

<div class="col-md-10 col-md-offset-1 table-top">
    <div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title pull-left"></h3>
        </div>
        <div class="box-body">
            <table id="sql-table" class="table">
                <thead>
                <th data-field="active" data-sortable="true">SQL Statement: Click to re-run queries</th>
                <th data-field="ip" data-sortable="true">Manage Favorites</th>
                </thead>
                <tbody>
            @if(isset($sql))
                @foreach($sql as $sql)
                <tr>
                    <td>
                        <a class="sql-link" href="{!! route('sql.show', $sql->id) !!}">
                            {{ $sql->sql }}
                        </a>
                    </td>
                    <td>
                        <div class="col-md-4">
                        @if(\Auth::user()->sqls->contains($sql->id))
                            {!! Form::open(['route' => 'favorite.destroy']) !!}
                            {!! Form::hidden('_method', 'DELETE') !!}
                            {!! Form::hidden('favorite', $sql->id) !!}
                            {!! Form::submit('Remove Favorite', ['class' => 'btn btn-small btn-warning']) !!}
                            {!! Form::close() !!}
                        @else
                            {!! Form::open(['route' => 'favorite.store']) !!}
                            {!! Form::hidden('favorite', $sql->id) !!}
                            {!! Form::submit('Add Favorite', ['class' => 'btn btn-small btn-success']) !!}
                            {!! Form::close() !!}
                        @endif
                        </div>
                    </td>
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
        $("#sql-table").DataTable({
            order: [[0, "asc"]],
            dom: '<"top">Bfrt<"bottom"lip><"clear">',
            buttons: [
                'copy', 'csv', 'excel', 'pdf', 'print'
            ]
        });
    });

</script>
@stop