{{-- Create Folder Modal --}}
<div class="modal fade" id="modal-erase-ctl">
  <div class="modal-dialog">
    <div class="modal-content">
      <form method="POST" action="{{route('ctl.store')}}"
            class="form-horizontal">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="folder" value="">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">
            ×
          </button>
          <h4 class="modal-title">Erase CTL File</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="new_folder_name" class="col-sm-3 control-label">
              MAC Address
            </label>
            <div class="col-sm-8">
              <input type="text" id="macAddress" name="macAddress"
                     class="form-control">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">
            Cancel
          </button>
          <button type="submit" class="btn btn-primary">
            Submit
          </button>
        </div>
      </form>
    </div>
  </div>
</div>