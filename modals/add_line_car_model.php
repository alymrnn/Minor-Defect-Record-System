<div class="modal fade bd-example-modal-xl" id="add_line_car_model" tabindex="-1" role="dialog" data-backdrop="static"
    data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background:#f9f9f9;">
            <div class="modal-header" style="background:#343a40;">
                <h5 class="modal-title" id="exampleModalLabel" style="font-weight: normal;color: #fff;"><i
                        class="fas fa-plus-circle"></i>&nbsp;
                    Add New Line / Car Model
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff;">
                    <span aria-hidden="true"><i class="fas fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body" style="max-height: 550px; overflow-y: auto;">
                <div class="row">
                    <div class="col-4">
                        <label class="m-0 p-0" style="font-weight: normal;">Line No.</label>
                        <input class="form-control" id="cm_line_no" style="width: 100%; text-align: center;"
                            type="text">
                    </div>
                    <div class="col-4">
                        <label class="m-0 p-0" style="font-weight: normal;">Section</label>
                        <input class="form-control" id="cm_section" style="width: 100%; text-align: center;"
                            type="text">
                    </div>
                    <div class="col-4">
                        <label class="m-0 p-0" style="font-weight: normal;">Car Maker</label>
                        <input class="form-control" id="cm_car_maker" style="width: 100%; text-align: center;"
                            type="text">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-8 offset-4">
                        <label class="m-0 p-0" style="font-weight: normal;">Car Model</label>
                        <input class="form-control" id="cm_car_model" style="width: 100%; text-align: center;"
                            type="text">
                    </div>
                </div>
            </div>

            <div class="modal-footer" style="background:#e9e9e9;">
                <div class="col-12">
                    <div class="float-left w-25">
                        <button class="btn btn-outline-danger btn-block" data-dismiss="modal">Cancel</button>
                    </div>
                    <div class="float-right w-25">
                        <button class="btn btn-success btn-block" onclick="register_line_car_model()">Add</button>
                    </div>
                </div>
            </div>
            <!-- end -->
        </div>
    </div>
</div>