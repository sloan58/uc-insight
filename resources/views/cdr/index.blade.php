@extends('app')

@section('content')

    <div class="col-md-10 col-md-offset-1">

        <div class="fresh-table toolbar-color-blue table-top">
            <!--    Available colors for the full background: full-color-blue, full-color-azure, full-color-green, full-color-red, full-color-orange
                    Available colors only for the toolbar: toolbar-color-blue, toolbar-color-azure, toolbar-color-green, toolbar-color-red, toolbar-color-orange
            -->

            <table id="fresh-table" class="table">
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
                            <td>{{$cdr->created_at->format('Y-m-d H:i:s')}}</td>
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
                pageSize: 50,
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