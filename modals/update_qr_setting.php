<div class="modal fade bd-example-modal-xl" id="update_qr_setting" tabindex="-1" role="dialog" data-backdrop="static"
    data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background:#f9f9f9;">
            <div class="modal-header" style="background:#343a40;">
                <h5 class="modal-title" id="exampleModalLabel" style="font-weight: normal;color: #fff;">
                    Update Car Maker QR Settings
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff;">
                    <span aria-hidden="true"><i class="fas fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body" style="max-height: 550px; overflow-y: auto;">
                <input type="hidden" id="id_qr_update" class="form-control">

                <div class="row">
                    <div class="col-4">
                        <label class="m-0 p-0" style="font-weight: normal;">Car Maker</label>
                        <select class="form-control" id="car_maker_qr_update" style="width: 100%;">
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
                        <label class="m-0 p-0" style="font-weight: normal;">Car Model Setting</label>
                        <input class="form-control" id="car_model_qr_update" style="width: 100%; text-align: center;"
                            type="text">
                    </div>
                    <div class="col-4">
                        <label class="m-0 p-0" style="font-weight: normal;">Car Value</label>
                        <select class="form-control" id="car_value_qr_update" style="width: 100%;">
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
                        <label class="m-0 p-0" style="font-weight: normal;">Total Length</label>
                        <input class="form-control" id="total_length_qr_update" style="width: 100%; text-align: center;"
                            type="int">
                    </div>
                    <div class="col-4">
                        <label class="m-0 p-0" style="font-weight: normal;">Product Name Start</label>
                        <input class="form-control" id="pro_name_start_qr_update" style="width: 100%; text-align: center;"
                            type="int">
                    </div>
                    <div class="col-4">
                        <label class="m-0 p-0" style="font-weight: normal;">Product Name Length</label>
                        <input class="form-control" id="pro_name_length_qr_update" style="width: 100%; text-align: center;"
                            type="int">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-4"></div>
                    <div class="col-4">
                        <label class="m-0 p-0" style="font-weight: normal;">Lot No. Start</label>
                        <input class="form-control" id="lot_no_start_qr_update" style="width: 100%; text-align: center;"
                            type="int">
                    </div>
                    <div class="col-4">
                        <label class="m-0 p-0" style="font-weight: normal;">Lot No. Length</label>
                        <input class="form-control" id="lot_no_length_qr_update" style="width: 100%; text-align: center;"
                            type="int">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-4"></div>
                    <div class="col-4">
                        <label class="m-0 p-0" style="font-weight: normal;">Serial No. Start</label>
                        <input class="form-control" id="serial_no_start_qr_update" style="width: 100%; text-align: center;"
                            type="int">
                    </div>
                    <div class="col-4">
                        <label class="m-0 p-0" style="font-weight: normal;">Serial No. Length</label>
                        <input class="form-control" id="serial_no_length_qr_update" style="width: 100%; text-align: center;"
                            type="int">
                    </div>
                </div>
            </div>

            <div class="modal-footer" style="background:#c2c2c2;">
                <div class="col-12">
                    <div class="float-left">
                        <button class="btn btn-block" onclick="delete_setting()"
                            style="color:#fff;height:34px;width:150px;border-radius:.25rem;background: #CA3F3F;font-size:15px;font-weight:normal;"
                            onmouseover="this.style.backgroundColor='#AC3737'; this.style.color='#FFF';"
                            onmouseout="this.style.backgroundColor='#CA3F3F'; this.style.color='#FFF';">Delete
                            Settings</button>
                    </div>
                    <div class="float-right">
                        <button class="btn btn-block" onclick="update_setting()"
                            style="color:#fff;height:34px;width:150px;border-radius:.25rem;background: #226F54;font-size:15px;font-weight:normal;">Update
                            Settings</button>
                    </div>
                </div>
            </div>
            <!-- end -->
        </div>
    </div>
</div>