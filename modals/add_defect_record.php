<div class="modal fade bd-example-modal-xl" id="add_defect_record" tabindex="-1" role="dialog" data-backdrop="static"
    data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="background:#f9f9f9;">
            <div class="modal-header" style="background:#1b263b; border-bottom: 3px solid #8d0801;">
                <h5 class="modal-title" id="exampleModalLabel" style="font-weight: normal;color: #fff;"><i
                        class="fas fa-plus"></i>&nbsp;
                    Add Record
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <label style="font-weight: normal; color: #000; font-size: 22px;">
                        <b>Minor Defect Record</b>
                    </label>
                    <label class="p-1" style="font-weight: normal; color: #000; font-size: 15px; border-bottom: 2px solid #8d0801;">
                        <i class="far fa-user"></i> User: <b><span id="authNameDisplay"></span></b>
                    </label>
                </div>

                <div class="row mb-3 mt-2">
                    <div class="col-12 col-md-3">
                        <!-- ip address hidden -->
                        <input type="hidden" name="a_ip_address" id="a_ip_address"
                            value="<?= $_SERVER['REMOTE_ADDR']; ?>">

                        <!-- defect id hidden -->
                        <input type="hidden" id="defect_id_no" class="form-control">

                        <label class="m-0 p-0" style="font-weight: normal;color: #000;font-size:14px;">Date Detected</label>
                        <label class="m-0 p-0 text-danger">*</label>

                        <input type="date" id="a_date_detected" class="form-control form-control-sm form-control-border" autocomplete="off" required>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="m-0 p-0" style="font-weight: normal;color: #000;font-size:14px;">Line No.</label>
                        <label class="m-0 p-0 text-danger">*</label>
                        <input type="text" id="a_line_no" class="form-control form-control-sm form-control-border" autocomplete="off" required>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="m-0 p-0" style="font-weight: normal;color: #000;font-size:14px;">Car Maker</label>
                        <label class="m-0 p-0 text-danger">*</label>
                        <input id="a_car_maker" class="form-control form-control-sm form-control-border" onchange="handleCarMakerChange(this)" required disabled>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="m-0 p-0" style="font-weight: normal;color: #000;font-size:14px;">Car Model</label>
                        <label class="m-0 p-0 text-danger">*</label>
                        <input type="text" id="a_car_model" class="form-control form-control-sm form-control-border" autocomplete="off" required>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-12 col-md-3 offset-3">
                        <label class="m-0 p-0" style="font-weight: normal;color: #000;font-size:14px;">Process</label>
                        <label class="m-0 p-0 text-danger">*</label>
                        <select id="a_process" class="form-control form-control-sm form-control-border" required>
                            <option value="" disabled selected>Select Process</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="m-0 p-0" style="font-weight: normal;color: #000;font-size:14px;">Group</label>
                        <label class="m-0 p-0 text-danger">*</label>
                        <select id="a_group" class="form-control form-control-sm form-control-border" required>
                            <option value="" disabled selected>Select Group</option>
                            <option value="ADS">ADS</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="m-0 p-0" style="font-weight: normal;color: #000;font-size:14px;">Shift</label>
                        <label class="m-0 p-0 text-danger">*</label>
                        <select id="a_shift" class="form-control form-control-sm form-control-border" required>
                            <option value="" disabled>Select Shift</option>
                            <option value="N/A" selected>N/A</option>
                            <option value="DS">DS</option>
                            <option value="NS">NS</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-2 d-none">
                    <div class="col-sm-12">
                        <label class="m-0 p-0" style="font-weight: normal;color: #000;font-size:14px;">Scan QR-Code</label>
                        <input type="text" id="a_scan_qr" class="form-control form-control-sm form-control-border" autocomplete="off">
                        <input type="hidden" id="nameplate_value">
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-4">
                        <label class="m-0 p-0" style="font-weight: normal;color: #000;font-size:14px;">Product Number</label>
                        <label class="m-0 p-0 text-danger">*</label>
                        <input type="text" id="a_product_name" class="form-control form-control-sm form-control-border" autocomplete="off" value="N/A" oninput="this.value = this.value.toUpperCase();">
                        <br>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="m-0 p-0" style="font-weight: normal;color: #000;font-size:14px;">Lot No.</label>
                        <label class="m-0 p-0 text-danger">*</label>
                        <input type="text" id="a_lot_no" class="form-control form-control-sm form-control-border" autocomplete="off"
                            maxlength="6"
                            oninput="this.value = this.value.toUpperCase();">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="m-0 p-0" style="font-weight: normal;color: #000;font-size:14px;">Serial No.</label>
                        <label class="m-0 p-0 text-danger">*</label>
                        <input type="text" id="a_serial_no" class="form-control form-control-sm form-control-border" autocomplete="off"
                            oninput="this.value = this.value.toUpperCase();">
                    </div>
                </div>
                <hr class="m-0 p-0">
                <div class="row mb-3 mt-3">
                    <div class="col-12 col-md-3" style="display: none;">
                        <label class="m-0 p-0" style="font-weight: normal;color: #000;font-size:14px;">Defect Category Code</label>
                        <label class="m-0 p-0 text-danger">*</label>
                        <input type="text" id="a_defect_category_code" class="form-control form-control-sm form-control-border"
                            autocomplete="off"
                            oninput="this.value = this.value.toUpperCase();">
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="m-0 p-0" style="font-weight: normal;color: #000;font-size:14px;">Defect Details Code</label>
                        <label class="m-0 p-0 text-danger">*</label>
                        <input type="text" id="a_defect_details_code" class="form-control form-control-sm form-control-border"
                            autocomplete="off" maxlength="3"
                            oninput="this.value = this.value.toUpperCase();">
                    </div>
                    <div class="col-12 col-md-5">
                        <label class="m-0 p-0" style="font-weight: normal;color: #000;font-size:14px;">Defect Category</label>
                        <label class="m-0 p-0 text-danger">*</label>
                        <input id="a_defect_category" class="form-control form-control-sm form-control-border"
                            required disabled>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="m-0 p-0" style="font-weight: normal;color: #000;font-size:14px;">Defect Details</label>
                        <label class="m-0 p-0 text-danger">*</label>
                        <input id="a_defect_details" class="form-control form-control-sm form-control-border"
                            required disabled>
                    </div>
                </div>
                <div class="row mb-3 mt-3">
                    <div class="col-12 col-md-5 offset-3">
                        <label class="m-0 p-0" style="font-weight: normal;color: #000;font-size:14px;">Treatment Content of Defect</label>
                        <label class="m-0 p-0 text-danger">*</label>
                        <input id="a_treatment_content_defect" class="form-control form-control-sm form-control-border"
                            required disabled>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-3">
                        <label class="m-0 p-0" style="font-weight: normal;color: #000;font-size:14px;">Sequence No.</label>
                        <label class="m-0 p-0 text-danger">*</label>
                        <input type="text" id="a_sequence_no" class="form-control form-control-sm form-control-border" autocomplete="off"
                            oninput="this.value = this.value.toUpperCase();"
                            required>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="m-0 p-0" style="font-weight: normal;color: #000;font-size:14px;">Connector No.</label>
                        <label class="m-0 p-0 text-danger">*</label>
                        <input type="text" id="a_connector_no" class="form-control form-control-sm form-control-border" autocomplete="off"
                            oninput="this.value = this.value.toUpperCase();"
                            required>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="m-0 p-0" style="font-weight: normal;color: #000;font-size:14px;">Repaired By <i style="font-size: 10px">(PD ID No.)</i></label>
                        <label class="m-0 p-0 text-danger">*</label>
                        <input type="text" id="a_repaired_by" class="form-control form-control-sm form-control-border" autocomplete="off" value="N/A"
                            required>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="m-0 p-0" style="font-weight: normal;color: #000;font-size:14px;">Verified By <i style="font-size: 10px">(QA ID No.)</i></label>
                        <label class="m-0 p-0 text-danger">*</label>
                        <input type="text" id="a_verified_by" class="form-control form-control-sm form-control-border" autocomplete="off" value="N/A"
                            required>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light">
                <div class="col-12 d-flex justify-content-between">
                    <button class="btn btn-outline-secondary btn-sm w-25" id="clear_btn" onclick="clear_add_defect_record()">
                        Clear All
                    </button>
                    <button class="btn btn-danger btn-sm w-25" onclick="add_defect_record()">
                        Add Record
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>