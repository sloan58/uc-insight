@extends('app')

@section('title')
    UC-Insight - SQL Favorites
@stop

@section('content')
    <div class="container-fluid">
        <div class="row page-tctle-row">
            <div class="col-md-6">
                <h3>SQL <small>» Favorites</small></h3>
            </div>
        </div>
    </div>

    @if(isset($favorites))
        <div class="row">
            <div class="col-sm-12">
                <table id="history-table" class="table table-striped row-border">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>SQL Statement: Click to re-run queries</th>
                        <th>Manage Favorites</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($favorites as $sql)
                        <tr>
                            <td>{{ $sql->id }}</td>
                            <td>{{ $sql->sql }}</td>
                            <td>
                                <div class="col-md-4">
                                    @if(\Auth::user()->sqls->contains($sql->id))
                                        {!! Form::open(['route' => 'favorite.destroy']) !!}
                                        {!! Form::hidden('_method', 'DELETE') !!}
                                        {!! Form::hidden('favorite', $sql->id) !!}
                                        {!! Form::submit('Remove from Favorites', ['class' => 'btn btn-small btn-warning']) !!}
                                        {!! Form::close() !!}
                                    @else
                                        {!! Form::open(['route' => 'favorite.store']) !!}
                                        {!! Form::hidden('favorite', $sql->id) !!}
                                        {!! Form::submit('Add to Favorites', ['class' => 'btn btn-small btn-success']) !!}
                                        {!! Form::close() !!}
                                    @endif
                                </div>
                            </td>
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