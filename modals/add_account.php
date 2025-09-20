<div class="modal fade bd-example-modal-xl" id="add_account" tabindex="-1" role="dialog" data-backdrop="static"
    data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background:#f9f9f9;">
            <div class="modal-header bg-light">
                <h5 class="modal-title font-weight-normal text-primary text-md">
                    <i class="fas fa-plus-circle mr-1"></i>Add New Account
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-4">
                        <label class="m-0 p-0 font-weight-normal text-sm">Username</label>
                        <input class="form-control form-control-sm form-control-border" id="username_m" type="text">
                    </div>
                    <div class="col-4">
                        <label class="m-0 p-0 font-weight-normal text-sm">Role</label>
                        <select class="form-control form-control-sm form-control-border" id="role_m">
                            <option value="ADMIN" selected>ADMIN</option>
                        </select>
                    </div>

                </div>
            </div>

            <div class="modal-footer bg-light d-flex justify-content-end">
                <button class="btn btn-primary btn-sm w-25" onclick="register_account()">Add</button>
            </div>
        </div>
    </div>
</div>