@extends('app')

@section('content')


    <div class="container-fluid table-top">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h3 class="panel-title">Send Calls in Bulk</h3>
                    </div>
                    <div class="panel-body">

                        <div class="col-md-8 col-md-offset-2 text-center">
                            <form method="POST" action={!! route('autodialer.bulk.store') !!}
                                  class="form-horizontal" enctype="multipart/form-data">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="modal-header">
                                    <h4 class="modal-title"></h4>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="file" class="col-sm-3 control-label">
                                            File Selection
                                        </label>
                                        <div class="col-sm-8">
                                            <input type="file" id="file" name="file">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-warning" data-toggle="tooltip" data-placement="left" title="Please load a CSV file which includes (in order) a 10 digit number, your outgoing message and the call type specified as either 'voice' or 'text'. Ex: 2025551234,Hi Mom!,text">File Format</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal">
                                        Cancel
                                    </button>
                                    <button type="submit" class="btn btn-primary">
                                        Upload File
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@stop