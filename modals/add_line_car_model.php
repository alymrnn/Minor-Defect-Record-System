<div class="modal fade bd-example-modal-xl" id="add_line_car_model" tabindex="-1" role="dialog" data-backdrop="static"
    data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background:#f9f9f9;">
            <div class="modal-header bg-light">
                <h5 class="modal-title font-weight-normal text-primary text-md">
                    <i class="fas fa-plus-circle mr-1"></i>Add New Car Model
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-4">
                        <label class="m-0 p-0 font-weight-normal text-sm">Line No.</label>
                        <input class="form-control form-control-sm form-control-border" id="cm_line_no" type="text">
                    </div>
                    <div class="col-4">
                        <label class="m-0 p-0 font-weight-normal text-sm">Section</label>
                        <input class="form-control form-control-sm form-control-border" id="cm_section" type="text">
                    </div>
                    <div class="col-4">
                        <label class="m-0 p-0 font-weight-normal text-sm">Car Maker</label>
                        <input class="form-control form-control-sm form-control-border" id="cm_car_maker" type="text">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-4 offset-4">
                        <label class="m-0 p-0 font-weight-normal text-sm">Car Model</label>
                        <input class="form-control form-control-sm form-control-border" id="cm_car_model" type="text">
                    </div>
                    <div class="col-4">
                        <label class="m-0 p-0 font-weight-normal text-sm">Harness Type</label>
                        <select class="form-control form-control-sm form-control-border" id="cm_harness_type">
                            <option value="" disabled selected>Select Type</option>
                            <option value="B">BIG</option>
                            <option value="S">SMALL</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light d-flex justify-content-end">
                <button class="btn btn-primary btn-sm w-25" onclick="register_line_car_model()">Add</button>
            </div>
        </div>
    </div>
</div>