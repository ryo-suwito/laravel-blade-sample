<div class="modal fade" id="submit-to-pten-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">
                    <h4>@lang('cms.Submit to PTEN')</h4>
                </div>
                <button
                    type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close"
                >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">Proceed to submit data of "<span id="merchant_branch_name"></span>" to PTEN?</div>
            <div class="modal-footer">
                <button
                    type="button"
                    class="btn btn-default"
                    data-dismiss="modal"
                >
                    @lang('cms.Close')
                </button>
                <button
                    class="btn btn-success"
                    type="button"
                    id="btn-proceed-submit-to-pten"
                >
                    @lang('cms.Proceed')
                </button>
            </div>
        </div>
    </div>
</div>
