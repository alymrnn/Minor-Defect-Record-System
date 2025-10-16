<div class="modal fade bd-example-modal-xl" id="edit_defect_record" tabindex="-1" role="dialog" data-backdrop="static"
    data-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content bg-light">
            <div class="modal-header" style="border-left: 8px solid #17a2b8;">
                <h6 class="modal-title font-weight-normal"><i
                        class="fas fa-edit mr-2"></i>Edit Record
                </h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-3 mt-2">
                    <div class="col-12 col-md-3">
                        <!-- ip address hidden -->
                        <input type="hidden" name="edit_ip_address" id="edit_ip_address"
                            value="<?= $_SERVER['REMOTE_ADDR']; ?>">

                        <!-- defect id hidden -->
                        <input type="hidden" id="edit_defect_id" class="form-control">

                        <label class="m-0 p-0 text-sm font-weight-normal">Date Detected</label>
                        <label class="m-0 p-0 text-info text-xs">*</label>

                        <input type="date" id="edit_date_detected" class="form-control form-control-sm form-control-border" autocomplete="off" required>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="m-0 p-0 text-sm font-weight-normal">Line No.</label>
                        <label class="m-0 p-0 text-info text-xs">*</label>
                        <input type="text" id="edit_line_no" class="form-control form-control-sm form-control-border" autocomplete="off" required>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="m-0 p-0 text-sm font-weight-normal">Car Maker</label>
                        <label class="m-0 p-0 text-info text-xs">*</label>
                        <input id="edit_car_maker" class="form-control form-control-sm form-control-border" onchange="handleCarMakerChange(this)" required>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="m-0 p-0 text-sm font-weight-normal">Car Model</label>
                        <label class="m-0 p-0 text-info text-xs">*</label>
                        <input type="text" id="edit_car_model" class="form-control form-control-sm form-control-border" autocomplete="off" required>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-12 col-md-3">
                        <label class="m-0 p-0 text-sm font-weight-normal">Category</label>
                        <label class="m-0 p-0 text-info text-xs">*</label>
                        <select id="edit_category" class="form-control form-control-sm form-control-border" required>
                            <option value="" disabled selected>Select Category</option>
                            <option value="N/A">N/A</option>
                            <option value="Prime">Prime</option>
                            <option value="Re-assy">Re-assy</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="m-0 p-0 text-sm font-weight-normal">Process</label>
                        <label class="m-0 p-0 text-info text-xs">*</label>
                        <input type="text" id="edit_process" class="form-control form-control-sm form-control-border" autocomplete="off" required>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="m-0 p-0 text-sm font-weight-normal">Group</label>
                        <label class="m-0 p-0 text-info text-xs">*</label>
                        <select id="edit_group_d" class="form-control form-control-sm form-control-border" required>
                            <option value="" disabled selected>Select Group</option>
                            <option value="ADS">ADS</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="m-0 p-0 text-sm font-weight-normal">Shift</label>
                        <label class="m-0 p-0 text-info text-xs">*</label>
                        <select id="edit_shift" class="form-control form-control-sm form-control-border" required>
                            <option value="" disabled>Select Shift</option>
                            <option value="N/A" selected>N/A</option>
                            <option value="DS">DS</option>
                            <option value="NS">NS</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-12 col-md-3">
                        <label class="m-0 p-0 text-sm font-weight-normal">Harness Type</label>
                        <label class="m-0 p-0 text-info text-xs">*</label>
                        <select id="edit_harness_type" class="form-control form-control-sm form-control-border" required>
                            <option value="" disabled selected>Select Type</option>
                            <option value="B">BIG</option>
                            <option value="S">SMALL</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-2 d-none">
                    <div class="col-sm-12">
                        <label class="m-0 p-0 text-sm font-weight-normal">Scan QR-Code</label>
                        <input type="text" id="edit_scan_qr" class="form-control form-control-sm form-control-border" autocomplete="off">
                        <input type="hidden" id="nameplate_value">
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-4">
                        <label class="m-0 p-0 text-sm font-weight-normal">Product Number</label>
                        <label class="m-0 p-0 text-info text-xs">*</label>
                        <input type="text" id="edit_product_name" class="form-control form-control-sm form-control-border" autocomplete="off" oninput="this.value = this.value.toUpperCase();">
                        <br>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="m-0 p-0 text-sm font-weight-normal">Lot No.</label>
                        <label class="m-0 p-0 text-info text-xs">*</label>
                        <input type="text" id="edit_lot_no" class="form-control form-control-sm form-control-border" autocomplete="off"
                            maxlength="6"
                            oninput="this.value = this.value.toUpperCase();">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="m-0 p-0 text-sm font-weight-normal">Serial No.</label>
                        <label class="m-0 p-0 text-info text-xs">*</label>
                        <input type="text" id="edit_serial_no" class="form-control form-control-sm form-control-border" autocomplete="off"
                            oninput="this.value = this.value.toUpperCase();">
                    </div>
                </div>
                <hr class="m-0 p-0">
                <div class="row mb-3 mt-3">
                    <div class="col-12 col-md-3" style="display: none;">
                        <label class="m-0 p-0 text-sm font-weight-normal">Defect Category Code</label>
                        <label class="m-0 p-0 text-info text-xs">*</label>
                        <input type="text" id="edit_defect_category_code" class="form-control form-control-sm form-control-border"
                            autocomplete="off"
                            oninput="this.value = this.value.toUpperCase();">
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="m-0 p-0 text-sm font-weight-normal">Defect Details Code</label>
                        <label class="m-0 p-0 text-info text-xs">*</label>
                        <input type="text" id="edit_defect_details_code" class="form-control form-control-sm form-control-border"
                            autocomplete="off" maxlength="3"
                            oninput="this.value = this.value.toUpperCase();">
                    </div>
                    <div class="col-12 col-md-5">
                        <label class="m-0 p-0 text-sm font-weight-normal">Defect Category</label>
                        <label class="m-0 p-0 text-info text-xs">*</label>
                        <input id="edit_defect_category" class="form-control form-control-sm form-control-border"
                            required>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="m-0 p-0 text-sm font-weight-normal">Defect Details</label>
                        <label class="m-0 p-0 text-info text-xs">*</label>
                        <input id="edit_defect_details" class="form-control form-control-sm form-control-border"
                            required>
                    </div>
                </div>
                <div class="row mb-3 mt-2">
                    <div class="col-12 col-md-5 offset-3">
                        <label class="m-0 p-0 text-sm font-weight-normal">Treatment Content of Defect</label>
                        <label class="m-0 p-0 text-info text-xs">*</label>
                        <input id="edit_treatment_content" class="form-control form-control-sm form-control-border"
                            required>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="m-0 p-0 text-sm font-weight-normal">Total Time (mins)</label>
                        <label class="m-0 p-0 text-info text-xs">*</label>
                        <input type="number" id="edit_total_time" class="form-control form-control-sm form-control-border"
                            required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-3">
                        <label class="m-0 p-0 text-sm font-weight-normal">Sequence No.</label>
                        <label class="m-0 p-0 text-info text-xs">*</label>
                        <input type="text" id="edit_sequence_no" class="form-control form-control-sm form-control-border" autocomplete="off"
                            oninput="this.value = this.value.toUpperCase();"
                            required>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="m-0 p-0 text-sm font-weight-normal">Connector No.</label>
                        <label class="m-0 p-0 text-info text-xs">*</label>
                        <input type="text" id="edit_connector_no" class="form-control form-control-sm form-control-border" autocomplete="off"
                            oninput="this.value = this.value.toUpperCase();"
                            required>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="m-0 p-0 text-sm font-weight-normal">Repaired By <i style="font-size: 10px">(PD ID No.)</i></label>
                        <label class="m-0 p-0 text-info text-xs">*</label>
                        <input type="text" id="edit_repaired_by" class="form-control form-control-sm form-control-border" autocomplete="off" value="N/A"
                            required>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="m-0 p-0 text-sm font-weight-normal">Verified By <i style="font-size: 10px">(QA ID No.)</i></label>
                        <label class="m-0 p-0 text-info text-xs">*</label>
                        <input type="text" id="edit_verified_by" class="form-control form-control-sm form-control-border" autocomplete="off" value="N/A"
                            required>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light">
                <div class="col-12 d-flex justify-content-end">
                    <button class="btn btn-info btn-sm w-25" onclick="edit_defect_record()">
                        Update Record
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>