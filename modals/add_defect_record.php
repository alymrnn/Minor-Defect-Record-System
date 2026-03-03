<div class="modal fade bd-example-modal-xl" id="add_defect_record" tabindex="-1" role="dialog" data-backdrop="static"
    data-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content bg-light">
            <div class="modal-header" style="border-bottom: 1px solid #ddd;">
                <h6 class="modal-title font-weight-normal text-primary">
                    <i class="fas fa-plus mr-2 text-primary"></i>Add Record
                </h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="fas fa-times"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <label class="font-weight-bold text-sm">
                        Minor Defect Record
                    </label>
                    <label class="p-1 font-weight-normal text-xs">
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

                        <label class="m-0 p-0 text-xs font-weight-normal">Date Detected</label>
                        <label class="m-0 p-0 text-danger text-xs">*</label>

                        <input type="date" id="a_date_detected" class="form-control form-control-sm form-control-border text-xs" autocomplete="off" required>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="m-0 p-0 text-xs font-weight-normal">Line No.</label>
                        <label class="m-0 p-0 text-danger text-xs">*</label>
                        <!-- <input
                            type="text"
                            id="a_line_no"
                            class="form-control form-control-sm form-control-border text-xs"
                            autocomplete="off"
                            required
                            maxlength="4"
                            pattern="\d{4}"> -->

                        <select id="a_line_no" class="form-control form-control-sm form-control-border text-xs" required>
                            <option value="" disabled selected>Select Line No.</option>
                        </select>

                        <input type="hidden" id="a_harness_type">
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="m-0 p-0 text-xs font-weight-normal">Car Maker</label>
                        <label class="m-0 p-0 text-danger text-xs">*</label>
                        <input id="a_car_maker" class="form-control form-control-sm form-control-border text-xs" onchange="handleCarMakerChange(this)" required disabled>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="m-0 p-0 text-xs font-weight-normal">Car Model</label>
                        <label class="m-0 p-0 text-danger text-xs">*</label>
                        <input type="text" id="a_car_model" class="form-control form-control-sm form-control-border text-xs" autocomplete="off" required>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-12 col-md-3" id="category_section">
                        <label class="m-0 p-0 text-xs font-weight-normal">Category</label>
                        <label class="m-0 p-0 text-danger text-xs">*</label>

                        <div class="m-0 p-0">
                            <div class="form-check form-check-inline m-0 p-0">
                                <input class="form-check-input form-control-sm text-xs" type="radio" name="category_type" id="prime" value="Prime">
                                <label class="form-check-label form-control-sm text-xs" for="prime">Prime</label>
                            </div>
                            <div class="form-check form-check-inline m-0 p-0">
                                <input class="form-check-input form-control-sm text-xs" type="radio" name="category_type" id="re_assy" value="Re-assy">
                                <label class="form-check-label form-control-sm text-xs" for="re_assy">Re-assy</label>
                            </div>
                        </div>

                        <input type="hidden" id="a_category">
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="m-0 p-0 text-xs font-weight-normal">Process</label>
                        <label class="m-0 p-0 text-danger text-xs">*</label>
                        <select id="a_process" class="form-control form-control-sm form-control-border text-xs" required>
                            <option value="" disabled selected>Select Process</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="m-0 p-0 text-xs font-weight-normal">Group</label>
                        <label class="m-0 p-0 text-danger text-xs">*</label>
                        <select id="a_group" class="form-control form-control-sm form-control-border text-xs" required>
                            <option value="" disabled selected>Select Group</option>
                            <option value="ADS">ADS</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="m-0 p-0 text-xs font-weight-normal">Shift</label>
                        <label class="m-0 p-0 text-danger text-xs">*</label>
                        <select id="a_shift" class="form-control form-control-sm form-control-border text-xs" required>
                            <option value="" disabled>Select Shift</option>
                            <option value="N/A" selected>N/A</option>
                            <option value="DS">DS</option>
                            <option value="NS">NS</option>
                        </select>
                    </div>
                </div>
                <div class="d-block d-md-none">
                    <div class="row">
                        <div class="col-12">
                            <label class="m-0 p-0 text-xs font-weight-normal">QR Setting</label>
                            <select id="qr_settings" class="form-control form-control-sm form-control-border text-xs" required>
                                <option value="" disabled selected>Select Setting</option>
                            </select>
                        </div>
                        <button type="button" class="btn btn-primary btn-sm mt-2" id="openScanner">
                            Open Camera Scanner
                        </button>
                        <div class="col-12 mt-2">
                            <div id="qr-reader" style="width:100%;"></div>
                        </div>
                        <div class="col-12">
                            <label class="m-0 p-0 text-xs font-weight-normal">Scan QR-Code</label>
                            <input type="text" id="a_scan_qr" class="form-control form-control-sm form-control-border text-xs" autocomplete="off">
                            <input type="hidden" id="nameplate_value">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-4">
                        <label class="m-0 p-0 text-xs font-weight-normal">Product Number</label>
                        <label class="m-0 p-0 text-danger text-xs">*</label>
                        <input type="text" id="a_product_name" class="form-control form-control-sm form-control-border text-xs" autocomplete="off" value="N/A" oninput="this.value = this.value.toUpperCase();">
                        <br>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="m-0 p-0 text-xs font-weight-normal">Lot No.</label>
                        <label class="m-0 p-0 text-danger text-xs">*</label>
                        <input type="text" id="a_lot_no" class="form-control form-control-sm form-control-border text-xs" autocomplete="off"
                            maxlength="6"
                            oninput="this.value = this.value.toUpperCase();" value="59">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="m-0 p-0 text-xs font-weight-normal">Serial No.</label>
                        <label class="m-0 p-0 text-danger text-xs">*</label>
                        <input type="text" id="a_serial_no" class="form-control form-control-sm form-control-border text-xs" autocomplete="off"
                            oninput="this.value = this.value.toUpperCase();">
                    </div>
                </div>
                <hr class="m-0 p-0">
                <div class="row mb-3 mt-3">
                    <div class="col-12 col-md-3" style="display: none;">
                        <label class="m-0 p-0 text-xs font-weight-normal">Defect Category Code</label>
                        <label class="m-0 p-0 text-danger text-xs">*</label>
                        <input type="text" id="a_defect_category_code" class="form-control form-control-sm form-control-border text-xs"
                            autocomplete="off"
                            oninput="this.value = this.value.toUpperCase();">
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="m-0 p-0 text-xs font-weight-normal">Defect Details Code</label>
                        <label class="m-0 p-0 text-danger text-xs">*</label>
                        <!-- <input type="text" id="a_defect_details_code" class="form-control form-control-sm form-control-border text-xs"
                            autocomplete="off" maxlength="3"
                            oninput="this.value = this.value.toUpperCase();"> -->

                        <select id="a_defect_details_code" class="form-control form-control-sm form-control-border text-xs" required>
                            <option value="" disabled selected>Select Code</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-5">
                        <label class="m-0 p-0 text-xs font-weight-normal">Defect Category</label>
                        <label class="m-0 p-0 text-danger text-xs">*</label>
                        <input id="a_defect_category" class="form-control form-control-sm form-control-border text-xs"
                            required disabled>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="m-0 p-0 text-xs font-weight-normal">Defect Details</label>
                        <label class="m-0 p-0 text-danger text-xs">*</label>
                        <input id="a_defect_details" class="form-control form-control-sm form-control-border text-xs"
                            required disabled>
                    </div>
                </div>
                <div class="row mb-3 mt-3">
                    <div class="col-12 col-md-5 offset-md-3">
                        <label class="m-0 p-0 text-xs font-weight-normal">Treatment Content of Defect</label>
                        <label class="m-0 p-0 text-danger text-xs">*</label>
                        <input id="a_treatment_content_defect" class="form-control form-control-sm form-control-border text-xs" required disabled>
                    </div>
                </div>
                <div class="row mb-3 mt-3">
                    <div class="col-12 col-md-3">
                        <label class="m-0 p-0 text-xs font-weight-normal">Sequence No.</label>
                        <label class="m-0 p-0 text-danger text-xs">*</label>
                        <input type="text" id="a_sequence_no" class="form-control form-control-sm form-control-border text-xs" autocomplete="off"
                            oninput="this.value = this.value.toUpperCase();"
                            required>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="m-0 p-0 text-xs font-weight-normal">Connector No.</label>
                        <label class="m-0 p-0 text-danger text-xs">*</label>
                        <input type="text" id="a_connector_no" class="form-control form-control-sm form-control-border text-xs" autocomplete="off"
                            oninput="this.value = this.value.toUpperCase();"
                            required>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="m-0 p-0 text-xs font-weight-normal">Occurrence Shift</label>
                        <label class="m-0 p-0 text-danger text-xs">*</label>
                        <select id="a_occurrence_shift" class="form-control form-control-sm form-control-border text-xs" required>
                            <option value="" disabled selected>Select Shift</option>
                            <option value="ADS">ADS</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="m-0 p-0 text-xs font-weight-normal">Occurrence Board No.</label>
                        <label class="m-0 p-0 text-danger text-xs">*</label>
                        <input type="text" id="a_occurrence_board_no" class="form-control form-control-sm form-control-border text-xs" autocomplete="off"
                            oninput="this.value = this.value.toUpperCase();"
                            required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-3">
                        <label class="m-0 p-0 text-xs font-weight-normal">Occurrence Station No.</label>
                        <label class="m-0 p-0 text-danger text-xs">*</label>
                        <input type="text" id="a_occurrence_station_no" class="form-control form-control-sm form-control-border text-xs" autocomplete="off"
                            oninput="this.value = this.value.toUpperCase();"
                            required>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="m-0 p-0 text-xs font-weight-normal">Total Time (mins)</label>
                        <label class="m-0 p-0 text-danger text-xs">*</label>
                        <input type="number" id="a_total_time" class="form-control form-control-sm form-control-border text-xs"
                            required>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="m-0 p-0 text-xs font-weight-normal">Repaired By <i style="font-size: 10px">(PD ID No.)</i></label>
                        <label class="m-0 p-0 text-danger text-xs">*</label>
                        <input type="text" id="a_repaired_by" class="form-control form-control-sm form-control-border text-xs" autocomplete="off" value="N/A"
                            required>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="m-0 p-0 text-xs font-weight-normal">Verified By <i style="font-size: 10px">(QA ID No.)</i></label>
                        <label class="m-0 p-0 text-danger text-xs">*</label>
                        <input type="text" id="a_verified_by" class="form-control form-control-sm form-control-border text-xs" autocomplete="off" value="N/A"
                            required>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light">
                <div class="col-12 d-flex justify-content-between">
                    <button class="btn btn-outline-secondary btn-sm w-25 text-xs" id="clear_btn" onclick="clear_add_defect_record()">
                        Clear All
                    </button>
                    <button class="btn btn-primary btn-sm w-25 text-xs" onclick="add_defect_record()">
                        Add Record
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>