<div class="modal" id="kycCheckingModal" tabindex="-1" role="dialog" aria-labelledby="kycCheckingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="kycCheckingModalLabel">KYC Checking Result</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Total KTP Used -->
                <div class="border rounded mb-3">
                    <div class="bg-light text-white p-2 d-flex justify-content-between align-items-center">
                        Total KTP Used
                        <span id="checkTotalKTP" class="badge"><i class="icon-checkmark text-success"></i></span>
                    </div>
                </div>

                <!-- Internal Blacklist -->
                <div class="bg-dark rounded mb-3">
                    <div class="bg-light text-white p-2 d-flex justify-content-between align-items-center rounded">
                        Internal Blacklist
                        <span id="iconInternalBlacklist " class="badge"><i class="icon-cross text-danger" style="font-size: 20px;"></i></span>
                    </div>
                    <div class="bg-dark text-white p-2 d-flex rounded">
                        <p class="mb-0" id="internalBlacklistMessage">KTP is blacklisted. Please use other KTP</p>

                    </div>
                </div>


                <!-- DTTOT -->
                <div class="bg-dark rounded mb-3">
                    <div class="bg-light text-white p-2 d-flex justify-content-between align-items-center rounded">
                        DTTOT
                        <span id="iconDTTOT" class="badge"><i class="icon-checkmark text-success"></i></span>
                    </div>
                    <div class="bg-dark text-white p-2 d-flex rounded">

                        <p class="mb-0" id="dttotMessage">CLEAR. Tidak ada persamaan dengan terduga DTTOT.</p>
                    </div>
                </div>


                <!-- Verihubs -->
                <div class="bg-dark rounded mb-3">
                    <div class="bg-light text-white p-2 d-flex justify-content-between align-items-center rounded">
                        Verihubs
                        <span id="iconVerihubs" class="badge"><i class="icon-cross text-danger" style="font-size: 20px;"></i></span>
                    </div>


                    <div class="bg-dark text-white p-2 d-flex rounded">
                        <p class="mb-0" id="verihubsMessage">not_verified. Selfie tidak sesuai.</p>
                    </div>
                </div>

            </div>

            <div class="text-center pb-3">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" style="background-color: transparent; border: none;">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveDataBtn">Save data</button>
            </div>
        </div>
    </div>
</div>
