@extends('app')

@section('content')

    <div class="col-md-10 col-md-offset-1">

        <div class="fresh-table toolbar-color-blue table-top">
            <!--    Available colors for the full background: full-color-blue, full-color-azure, full-color-green, full-color-red, full-color-orange
                    Available colors only for the toolbar: toolbar-color-blue, toolbar-color-azure, toolbar-color-green, toolbar-color-red, toolbar-color-orange
            -->

            <div class="toolbar">
                <a type="button" class="btn btn-md" href="user/create" role="button">
                    <i class="fa fa-plus-circle fa-lg"></i>
                    Add User
                </a>
            </div>

            <table id="fresh-table" class="table">
                <thead>
                <th data-field="username">Username</th>
                <th data-field="email" data-sortable="true">Email Address</th>
                <th>Actions</th>
                </thead>
                <tbody>
                @if(isset($users))
                    @foreach($users as $user)
                        <tr>
                           <td>{{$user->name}}</td>
                           <td>{{$user->email}}</td>
                            <td>
                                <!-- edit this User -->
                                <a href="{!! route('user.edit', $user->name) !!}" title="Edit User"><i class="fa fa-pencil-square-o fa-2x enabled editable"></i></a>

                                <!-- delete this User -->
                                <a href="{!! route('user.destroy', $user->name) !!}" data-toggle="modal" data-target="#modal-delete" title="Delete User"><i class="fa fa-trash-o fa-2x enabled deletable"></i></a>
                            </td>
                        </tr>
                    @endforeach
                @endif
                    </tbody>
                </table>
            </div>
        </div>

    @if(isset($users))

        {{-- Confirm Delete --}}
    <div class="modal fade" id="modal-delete" tabIndex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">
                        &times;
                    </button>
                    <h4 class="modal-title">Please Confirm</h4>
                </div>
                <div class="modal-body">
                    <p class="lead">
                        <i class="fa fa-question-circle fa-lg"></i> &nbsp;
                        Are you sure you want to delete the User '{{$user->name}}'?
                    </p>
                </div>
                <div class="modal-footer">
                    <form method="POST" action="{{ route('user.destroy', $user->name) }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="button" class="btn btn-default"
                                data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fa fa-times-circle"></i> Yes
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @endif

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