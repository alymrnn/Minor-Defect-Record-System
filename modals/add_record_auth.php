<div class="modal fade" id="add_record_auth" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg">
            <div class="modal-header" style="background:#00375C; border-bottom: 1px solid #eee;">
                <h5 class="modal-title text-white text-sm">
                    New Record Authorization
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-times fa-xs text-white"></i></span>
                </button>
            </div>
            <div class="modal-body py-3" style="background: #00375C; color:#F1F1F1">
                <div class="row">
                    <div class="col-12">
                        <p class="text-center text-xs">Enter your registered ID no. to proceed with adding a record.</p>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-12 col-md-10">
                        <input type="hidden" id="auth_name" class="form-control">
                        <input class="form-control form-control-sm text-center border-0 shadow-sm p-2 text-xs"
                            id="auth_id_no" placeholder="Input Employee ID here" type="text"
                            oncopy="return false;" onpaste="return false;">

                        <style>
                            .auth-scan-btn {
                                display: none;
                            }

                            /* Show button on small screens (mobile) */
                            @media (max-width: 767px) {
                                .auth-scan-btn {
                                    display: inline-block;
                                    /* or block if you want full width */
                                }
                            }
                        </style>
                        <!-- QR Scanner Button -->
                        <button type="button" class="btn btn-sm btn-outline-warning text-xs mt-2 auth-scan-btn d-none" id="openAuthScanner">
                            <i class="fas fa-camera mr-1"></i> Scan QR Employee ID No.
                        </button>

                        <!-- QR Reader container -->
                        <div id="auth-qr-reader" style="width:100%; display:none; margin-top:10px;"></div>

                        <small class="text-muted d-block mt-2 text-center text-xs">
                            Click enter button or scan QR to proceed.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>