
<style>
    .loading {
        position: fixed;
        z-index: 999;
        height: 2em;
        width: 2em;
        overflow: show;
        margin: auto;
        top: 0;
        left: 0;
        bottom: 0;
        right: 0;
    }
    /* Transparent Overlay */
    .loading::before {
        content: '';
        display: block;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.58);
    }
</style>
<!-- modal ktp details -->
<div class="modal fade" id="ktpDetailsModal" tabindex="-1" role="dialog" aria-labelledby="ktpDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="ktpDetailsModalLabel">KTP Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="ktpDetailsContent">
                    <div class="row">
                        <label class="col-sm-3 control-label">NIK</label>
                        <div class="col-sm-9">
                            <input type="text" id="nikModal" name="nikModal" class="form-control">
                            <p class="error" id="nik_error" style="color: red;"></p>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <label class="col-sm-3 control-label">Name</label>
                        <div class="col-sm-9">
                            <input type="text" id="nameModal" name="identity_name" class="form-control">
                            <p class="error" id="name_error" style="color: red;"></p>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <label class="col-sm-3 control-label">Birth Date</label>
                        <div class="col-sm-9">
                            <input type="text" id="birthdateModal" name="birthdate" class="form-control">
                            <p class="error" id="birthdate_error" style="color: red;"></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="btn-group" style="width: 100%;">
                    <button id="change_details" class="btn btn-primary">Change</button>
                    <button id="cancel_details" class="btn btn-secondary">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>