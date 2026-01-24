<div class="modal fade bd-example-modal-xl" id="add_defect_details" tabindex="-1" role="dialog" data-backdrop="static"
    data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background:#f9f9f9;">
            <div class="modal-header bg-light">
                <h6 class="modal-title font-weight-normal text-primary text-md">
                    <i class="fas fa-plus-circle mr-1"></i>Add New Defect Details
                </h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-4">
                        <label class="m-0 p-0 font-weight-normal text-xs">Defect Code</label>
                        <input class="form-control form-control-sm form-control-border text-xs" id="defect_code_m" type="text">
                    </div>
                    <div class="col-4">
                        <label class="m-0 p-0 font-weight-normal text-xs">Defect Category</label>
                        <input class="form-control form-control-sm form-control-border text-xs" id="defect_category_m" type="text">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-4">
                        <label class="m-0 p-0 font-weight-normal text-xs">Defect Sub Code</label>
                        <input class="form-control form-control-sm form-control-border text-xs" id="defect_sub_code_m" type="text">
                    </div>
                    <div class="col-4">
                        <label class="m-0 p-0 font-weight-normal text-xs">Defect Details</label>
                        <textarea class="form-control form-control-sm form-control-border text-xs" id="defect_details_m" rows="3"></textarea>
                    </div>
                    <div class="col-4">
                        <label class="m-0 p-0 font-weight-normal text-xs">Defect Treatment</label>
                        <textarea class="form-control form-control-sm form-control-border text-xs" id="defect_treatment_m" rows="3"></textarea>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light d-flex justify-content-end">
                <button class="btn btn-primary btn-sm w-25 text-xs" onclick="register_defect_details()">Add</button>
            </div>
        </div>
    </div>
</div>