<div class="modal fade bd-example-modal-xl" id="add_defect_details" tabindex="-1" role="dialog" data-backdrop="static"
    data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background:#f9f9f9;">
            <div class="modal-header" style="background:#343a40;">
                <h5 class="modal-title" id="exampleModalLabel" style="font-weight: normal;color: #fff;"><i
                        class="fas fa-plus-circle"></i>&nbsp;
                    Add New Defect Details
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff;">
                    <span aria-hidden="true"><i class="fas fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body" style="max-height: 550px; overflow-y: auto;">
                <div class="row">
                    <div class="col-4">
                        <label class="m-0 p-0" style="font-weight: normal;">Defect Code</label>
                        <input class="form-control" id="defect_code_m" style="width: 100%; text-align: center;"
                            type="text">
                    </div>
                    <div class="col-4">
                        <label class="m-0 p-0" style="font-weight: normal;">Defect Category</label>
                        <input class="form-control" id="defect_category_m" style="width: 100%; text-align: center;"
                            type="text">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-4">
                        <label class="m-0 p-0" style="font-weight: normal;">Defect Sub Code</label>
                        <input class="form-control" id="defect_sub_code_m" style="width: 100%; text-align: center;"
                            type="text">
                    </div>
                    <div class="col-4">
                        <label class="m-0 p-0" style="font-weight: normal;">Defect Details</label>
                        <textarea class="form-control" id="defect_details_m" style="width: 100%; text-align: center;"
                            rows="3"></textarea>
                    </div>
                    <div class="col-4">
                        <label class="m-0 p-0" style="font-weight: normal;">Defect Treatment</label>
                        <textarea class="form-control" id="defect_treatment_m" style="width: 100%; text-align: center;"
                            rows="3"></textarea>
                    </div>
                </div>
            </div>

            <div class="modal-footer" style="background:#e9e9e9;">
                 <div class="col-12">
                    <div class="float-left w-25">
                        <button class="btn btn-outline-danger btn-block" data-dismiss="modal">Cancel</button>
                    </div>
                    <div class="float-right w-25">
                        <button class="btn btn-success btn-block" onclick="register_defect_details()">Add</button>
                    </div>
                </div>
            </div>
            <!-- end -->
        </div>
    </div>
</div>