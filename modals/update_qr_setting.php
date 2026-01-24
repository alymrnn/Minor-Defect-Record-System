<div class="modal fade bd-example-modal-xl" id="update_qr_setting" tabindex="-1" role="dialog" data-backdrop="static"
    data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background:#f9f9f9;">
            <div class="modal-header bg-light">
                <h6 class="modal-title font-weight-normal text-primary text-md">
                    <i class="fas fa-plus-circle mr-1"></i>Update QR Settings
                </h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="id_qr_update">

                <div class="row">
                    <div class="col-4">
                        <label class="m-0 p-0 font-weight-normal text-xs">Car Maker</label>
                        <select class="form-control form-control-sm form-control-border text-xs" id="car_maker_qr_update">
                            <option value="" selected disabled>Select car maker</option>
                            <option value="MAZDA">MAZDA</option>
                            <option value="DAIHATSU">DAIHATSU</option>
                            <option value="HONDA">HONDA</option>
                            <option value="TOYOTA">TOYOTA</option>
                            <option value="SUZUKI">SUZUKI</option>
                            <option value="NISSAN">NISSAN</option>
                            <option value="SUBARU">SUBARU</option>
                        </select>
                    </div>
                    <div class="col-4">
                        <label class="m-0 p-0 font-weight-normal text-xs">Car Model Setting</label>
                        <input class="form-control form-control-sm form-control-border text-xs" id="car_model_qr_update" type="text">
                    </div>
                    <div class="col-4">
                        <label class="m-0 p-0 font-weight-normal text-xs">Car Value</label>
                        <select class="form-control form-control-sm form-control-border text-xs" id="car_value_qr_update">
                            <option value="" selected disabled>Select car value</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                            <option value="7">7</option>
                        </select>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-4">
                        <label class="m-0 p-0 font-weight-normal text-xs">Total Length</label>
                        <input class="form-control form-control-sm form-control-border text-xs" id="total_length_qr_update" type="int">
                    </div>
                    <div class="col-4">
                        <label class="m-0 p-0 font-weight-normal text-xs">Product Name Start</label>
                        <input class="form-control form-control-sm form-control-border text-xs" id="pro_name_start_qr_update" type="int">
                    </div>
                    <div class="col-4">
                        <label class="m-0 p-0 font-weight-normal text-xs">Product Name Length</label>
                        <input class="form-control form-control-sm form-control-border text-xs" id="pro_name_length_qr_update" type="int">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-4"></div>
                    <div class="col-4">
                        <label class="m-0 p-0 font-weight-normal text-xs">Lot No. Start</label>
                        <input class="form-control form-control-sm form-control-border text-xs" id="lot_no_start_qr_update" type="int">
                    </div>
                    <div class="col-4">
                        <label class="m-0 p-0 font-weight-normal text-xs">Lot No. Length</label>
                        <input class="form-control form-control-sm form-control-border text-xs" id="lot_no_length_qr_update" type="int">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-4"></div>
                    <div class="col-4">
                        <label class="m-0 p-0 font-weight-normal text-xs">Serial No. Start</label>
                        <input class="form-control form-control-sm form-control-border text-xs" id="serial_no_start_qr_update" type="int">
                    </div>
                    <div class="col-4">
                        <label class="m-0 p-0 font-weight-normal text-xs">Serial No. Length</label>
                        <input class="form-control form-control-sm form-control-border text-xs" id="serial_no_length_qr_update" type="int">
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light d-flex justify-content-between">
                <button class="btn btn-outline-danger btn-sm w-25 text-xs" onclick="delete_setting()">Delete</button>
                <button class="btn btn-primary btn-sm w-25 text-xs" onclick="update_setting()">Update</button>
            </div>
        </div>
    </div>
</div>