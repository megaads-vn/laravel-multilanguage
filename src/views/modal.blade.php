<div id="add-key-modal" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add new key</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="clear"></div>
            </div>
            <div class="modal-body">
                <form id="add-key-form">
                    <div class="form-group">
                        <label for="language_key">Language key</label>
                        <input type="text" class="form-control" id="language_key" placeholder="Enter language key">
                    </div>
                    <div class="form-group">
                        <label for="language_value">Language value</label>
                        <input type="text" class="form-control" id="language_value" placeholder="Enter language value">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="message"></div>
                <button type="button" class="btn btn-success js-btn-save">Save changes</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>