@extends('app')

@section('title')
    UC-Insight - SQL Favorites
@stop

@section('content')

    <div class="col-md-10 col-md-offset-1">

        <div class="fresh-table toolbar-color-blue table-top">
            <!--    Available colors for the full background: full-color-blue, full-color-azure, full-color-green, full-color-red, full-color-orange
                    Available colors only for the toolbar: toolbar-color-blue, toolbar-color-azure, toolbar-color-green, toolbar-color-red, toolbar-color-orange
            -->

            <table id="fresh-table" class="table">
                <thead>
                <th data-field="active" data-sortable="true">SQL Statement: Click to re-run queries</th>
                <th data-field="ip" data-sortable="true">Manage Favorites</th>
                </thead>
                <tbody>
                @if(isset($favorites))
                    @foreach($favorites as $sql)
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
        var $table = $('#fresh-table'),
                $alertBtn = $('#alertBtn'),
                full_screen = false;

        $().ready(function(){
            $table.bootstrapTable({
                toolbar: ".toolbar",

                showRefresh: true,
                search: true,
                showToggle: true,
                showColumns: true,
                pagination: true,
                striped: true,
                pageSize: 8,
                pageList: [8,10,25,50,100],

                formatShowingRows: function(pageFrom, pageTo, totalRows){
                    //do nothing here, we don't want to show the text "showing x of y from..."
                },
                formatRecordsPerPage: function(pageNumber){
                    return pageNumber + " rows visible";
                },
                icons: {
                    refresh: 'fa fa-refresh',
                    toggle: 'fa fa-th-list',
                    columns: 'fa fa-columns',
                    detailOpen: 'fa fa-plus-circle',
                    detailClose: 'fa fa-minus-circle'
                }
            });

            $(window).resize(function () {
                $table.bootstrapTable('resetView');
            });


            window.operateEvents = {
                'click .like': function (e, value, row, index) {
                    alert('You click like icon, row: ' + JSON.stringify(row));
                    console.log(value, row, index);
                },
                'click .edit': function (e, value, row, index) {
                    alert('You click edit icon, row: ' + JSON.stringify(row));
                    console.log(value, row, index);
                },
                'click .remove': function (e, value, row, index) {
                    $table.bootstrapTable('remove', {
                        field: 'id',
                        values: [row.id]
                    });

                }
            };

        });

        function operateFormatter(value, row, index) {
            return [

            ].join('');
        }

    </script>
@stop