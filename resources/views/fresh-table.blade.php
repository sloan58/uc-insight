@extends('app')

@section('content')

<div class="col-md-10 col-md-offset-1">

    <div class="fresh-table full-color-blue">
        <!--    Available colors for the full background: full-color-blue, full-color-azure, full-color-green, full-color-red, full-color-orange
                Available colors only for the toolbar: toolbar-color-blue, toolbar-color-azure, toolbar-color-green, toolbar-color-red, toolbar-color-orange
        -->

        <div class="toolbar">
            <button id="alertBtn" class="btn btn-default">Alert</button>
        </div>

        <table id="fresh-table" class="table">
            <thead>
            <th data-field="id">ID</th>
            <th data-field="name" data-sortable="true">Name</th>
            <th data-field="active" data-sortable="true">Active</th>
            <th data-field="ip" data-sortable="true">IP Address</th>
            <th data-field="username">Username</th>
            <th data-field="actions" data-formatter="operateFormatter" data-events="operateEvents">Actions</th>
            </thead>
            <tbody>
            @foreach($clusters as $cluster)
                <tr>
                    <td>{{$cluster->id}}</td>
                    <td>{{$cluster->name}}</td>
                    <td>{{\Auth::user()->clusters_id == $cluster->id ? 'Active' : ''}}</td>
                    <td>{{$cluster->ip}}</td>
                    <td>{{$cluster->username}}</td>
                    <td></td>
                </tr>
            @endforeach
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

        $alertBtn.click(function () {
            alert("You pressed on Alert");
        });

    });


    function operateFormatter(value, row, index) {
        return [
            '<a href="{!! route('cluster.edit', $cluster->id) !!}" title="Edit Cluster">',
            '<i class="fa fa-edit"></i>',
            '</a>',
            '<a href="" data-toggle="modal" data-target="#modal-delete" title="Delete Clustetr">',
            '<i class="fa fa-remove"></i>',
            '</a>'
        ].join('');
    }


</script>

@stop