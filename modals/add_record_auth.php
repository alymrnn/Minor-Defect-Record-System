<div class="modal fade" id="add_record_auth" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content shadow-lg">
            <div class="modal-header" style="background:#00375C; border-bottom: 2px solid #ddd;">
                <h5 class="modal-title text-white text-md">
                    <i class="fas fa-plus"></i> New Record Authorization
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #FFF;">
                    <span aria-hidden="true"><i class="fas fa-times"></i></span>
            </div>
            <div class="modal-body py-4" style="background: #00375C; color:#F1F1F1">
                <div class="row">
                    <div class="col-12">
                        <p class="text-center text-sm">Enter your registered ID no. to proceed with adding a record.</p>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-6">
                        <input type="hidden" id="auth_name" class="form-control">
                        <input class="form-control form-control-md text-center border-0 shadow-sm p-2" id="auth_id_no"
                            placeholder="Input ID no. here" type="text" oncopy="return false;" onpaste="return false;">
                        <small class="text-muted d-block mt-2 text-center">Click enter button to proceed.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>