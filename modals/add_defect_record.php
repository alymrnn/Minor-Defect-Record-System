<div class="modal fade bd-example-modal-xl" id="add_defect_record" tabindex="-1" role="dialog" data-backdrop="static"
    data-keyboard="false">
    <div class="modal-dialog modal-xl" role="document">
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
                <label style="font-weight: normal;color: #000;font-size:22px"><b>Minor Defect Record</b></label>
                <div class="row mb-3">
                    <div class="col-12 col-md-3">
                        <!-- ip address hidden -->
                        <input type="hidden" name="a_ip_address" id="a_ip_address"
                            value="<?= $_SERVER['REMOTE_ADDR']; ?>">

                        <!-- defect id hidden -->
                        <input type="hidden" id="defect_id_no" class="form-control">

                        <label class="m-0 p-0" style="font-weight: normal;color: #000;font-size:14px;">Date Detected</label>
                        <label class="m-0 p-0" style="color:#CA3F3F">*</label>

                        <input type="date" id="a_date_detected" class="form-control" autocomplete="off"
                            style="color: #525252;font-size: 14px;;border-radius: .25rem;background: #FFF;height: 35px; width:100%;"
                            required>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="m-0 p-0" style="font-weight: normal;color: #000;font-size:14px;">Line No.</label>
                        <label class="m-0 p-0" style="color:#CA3F3F">*</label>
                        <input type="text" id="a_line_no" class="form-control" autocomplete="off" placeholder=""
                            style="color: #525252;font-size: 14px;;border-radius: .25rem;background: #FFF;height: 35px; width:100%;"
                            required>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="m-0 p-0" style="font-weight: normal;color: #000;font-size:14px;">Car Maker</label>
                        <label class="m-0 p-0" style="color:#CA3F3F">*</label>
                        <input id="a_car_maker" class="form-control" onchange="handleCarMakerChange(this)"
                            style="color: #525252; font-size: 14px;; border-radius: .25rem; background: #F1F1F1; height: 35px; width: 100%;"
                            required disabled>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="m-0 p-0" style="font-weight: normal;color: #000;font-size:14px;">Car Model</label>
                        <label class="m-0 p-0" style="color:#CA3F3F">*</label>
                        <input type="text" id="a_car_model" class="form-control" autocomplete="off" placeholder=""
                            style="color: #525252;font-size: 14px;;border-radius: .25rem;background: #FFF;height: 35px; width:100%;"
                            required>
                    </div>
                </div>
                <div class="row mb-4">
                    <div class="col-12 col-md-3 offset-3">
                        <label class="m-0 p-0" style="font-weight: normal;color: #000;font-size:14px;">Process</label>
                        <label class="m-0 p-0" style="color:#CA3F3F">*</label>
                        <select id="a_process" class="form-control"
                            style="color: #525252;font-size: 14px;;border-radius: .25rem;background: #FFF;height:35px; width:100%;">
                            <option value="" disabled selected>Select Process</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="m-0 p-0" style="font-weight: normal;color: #000;font-size:14px;">Group</label>
                        <label class="m-0 p-0" style="color:#CA3F3F">*</label>
                        <select id="a_group" class="form-control"
                            style="color: #525252; font-size: 14px;; border-radius: .25rem; background: #FFF; height: 35px; width: 100%;"
                            required>
                            <option value="" disabled selected>Select Group</option>
                            <option value="ADS">ADS</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="m-0 p-0" style="font-weight: normal;color: #000;font-size:14px;">Shift</label>
                        <label class="m-0 p-0" style="color:#CA3F3F">*</label>
                        <select id="a_shift" class="form-control"
                            style="color: #525252; font-size: 14px;; border-radius: .25rem; background: #FFF; height: 35px; width: 100%;"
                            required>
                            <option value="" disabled>Select Shift</option>
                            <option value="N/A" selected>N/A</option>
                            <option value="DS">DS</option>
                            <option value="NS">NS</option>
                        </select>
                    </div>
                </div>
                <div class="row mb-2" style="display: none;">
                    <div class="col-sm-12">
                        <label class="m-0 p-0" style="font-weight: normal;color: #000;font-size:14px;">Scan QR-Code</label>
                        <input type="text" id="a_scan_qr" class="form-control" autocomplete="off"
                            style="color: #525252;font-size: 14px;;border-radius: .25rem;background: #FFF;height: 35px; width:100%;">

                        <input type="hidden" id="nameplate_value">
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-4">
                        <label class="m-0 p-0" style="font-weight: normal;color: #000;font-size:14px;">Product Number</label>
                        <label class="m-0 p-0" style="color:#CA3F3F">*</label>
                        <input type="text" id="a_product_name" class="form-control" autocomplete="off"
                            style="color: #525252; font-size: 14px; border-radius: .25rem; background: #FFF; height: 35px; width:100%; text-transform: uppercase;"
                            oninput="this.value = this.value.toUpperCase();">
                        <br>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="m-0 p-0" style="font-weight: normal;color: #000;font-size:14px;">Lot No.</label>
                        <label class="m-0 p-0" style="color:#CA3F3F">*</label>
                        <input type="text" id="a_lot_no" class="form-control" autocomplete="off"
                            style="color: #525252; font-size: 14px; border-radius: .25rem; background: #FFF; height: 35px; width:100%; text-transform: uppercase;"
                            oninput="this.value = this.value.toUpperCase();">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="m-0 p-0" style="font-weight: normal;color: #000;font-size:14px;">Serial No.</label>
                        <label class="m-0 p-0" style="color:#CA3F3F">*</label>
                        <input type="text" id="a_serial_no" class="form-control" autocomplete="off"
                            style="color: #525252; font-size: 14px; border-radius: .25rem; background: #FFF; height: 35px; width:100%; text-transform: uppercase;"
                            oninput="this.value = this.value.toUpperCase();">
                    </div>
                </div>
                <hr class="m-0 p-0">
                <div class="row mb-3 mt-3">
                    <div class="col-12 col-md-3">
                        <label class="m-0 p-0" style="font-weight: normal;color: #000;font-size:14px;">Defect Category Code</label>
                        <label class="m-0 p-0" style="color:#CA3F3F">*</label>
                        <input type="text" id="a_defect_category_code" class="form-control"
                            autocomplete="off"
                            style="color: #525252; font-size: 14px; border-radius: .25rem; background: #FFF; height: 35px; width:100%; text-transform: uppercase;"
                            oninput="this.value = this.value.toUpperCase();">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="m-0 p-0" style="font-weight: normal;color: #000;font-size:14px;">Defect Category</label>
                        <label class="m-0 p-0" style="color:#CA3F3F">*</label>
                        <!-- <select id="a_defect_category" class="form-control"
                            style="color: #525252; font-size: 14px;; border-radius: .25rem; background: #FFF; height: 35px; width: 100%;"
                            required>
                            <option value="" disabled selected>Select Defect Category</option>
                        </select> -->

                        <input id="a_defect_category" class="form-control"
                            style="color: #525252; font-size: 14px;; border-radius: .25rem; height: 35px; width: 100%;"
                            required disabled>
                    </div>
                </div>
                <div class="row mb-3 mt-3">
                    <div class="col-12 col-md-3">
                        <label class="m-0 p-0" style="font-weight: normal;color: #000;font-size:14px;">Defect Details Code</label>
                        <label class="m-0 p-0" style="color:#CA3F3F">*</label>
                        <input type="text" id="a_defect_details_code" class="form-control"
                            autocomplete="off"
                            style="color: #525252; font-size: 14px; border-radius: .25rem; background: #FFF; height: 35px; width:100%; text-transform: uppercase;"
                            oninput="this.value = this.value.toUpperCase();">
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="m-0 p-0" style="font-weight: normal;color: #000;font-size:14px;">Defect Details</label>
                        <label class="m-0 p-0" style="color:#CA3F3F">*</label>
                        <!-- <select id="a_defect_details" class="form-control"
                            style="color: #525252; font-size: 14px;; border-radius: .25rem; background: #DDD; height: 35px; width: 100%;"
                            required>
                            <option value="" disabled selected>Select Defect Details</option>
                        </select> -->

                        <input id="a_defect_details" class="form-control"
                            style="color: #525252; font-size: 14px;; border-radius: .25rem; height: 35px; width: 100%;"
                            required disabled>
                    </div>
                    <div class="col-12 col-md-5">
                        <label class="m-0 p-0" style="font-weight: normal;color: #000;font-size:14px;">Treatment Content of Defect</label>
                        <label class="m-0 p-0" style="color:#CA3F3F">*</label>
                        <input id="a_treatment_content_defect" class="form-control"
                            style="color: #525252; font-size: 14px;; border-radius: .25rem; height: 35px; width: 100%;"
                            required disabled>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-3">
                        <label class="m-0 p-0" style="font-weight: normal;color: #000;font-size:14px;">Sequence No.</label>
                        <label class="m-0 p-0" style="color:#CA3F3F">*</label>
                        <input type="text" id="a_sequence_no" class="form-control" autocomplete="off" placeholder=""
                            style="color: #525252; font-size: 14px; border-radius: .25rem; background: #FFF; height: 35px; width:100%; text-transform: uppercase;"
                            oninput="this.value = this.value.toUpperCase();"
                            required>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="m-0 p-0" style="font-weight: normal;color: #000;font-size:14px;">Connector No.</label>
                        <label class="m-0 p-0" style="color:#CA3F3F">*</label>
                        <input type="text" id="a_connector_no" class="form-control" autocomplete="off" placeholder=""
                            style="color: #525252; font-size: 14px; border-radius: .25rem; background: #FFF; height: 35px; width:100%; text-transform: uppercase;"
                            oninput="this.value = this.value.toUpperCase();"
                            required>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="m-0 p-0" style="font-weight: normal;color: #000;font-size:14px;">Repaired By <i style="font-size: 10px">(PD ID No.)</i></label>
                        <label class="m-0 p-0" style="color:#CA3F3F">*</label>
                        <input type="text" id="a_repaired_by" class="form-control" autocomplete="off" placeholder="" value="N/A"
                            style="color: #525252;font-size: 14px;;border-radius: .25rem;background: #FFF;height: 35px; width:100%;"
                            required>
                    </div>
                    <div class="col-12 col-md-3">
                        <label class="m-0 p-0" style="font-weight: normal;color: #000;font-size:14px;">Verified By <i style="font-size: 10px">(QA ID No.)</i></label>
                        <label class="m-0 p-0" style="color:#CA3F3F">*</label>
                        <input type="text" id="a_verified_by" class="form-control" autocomplete="off" placeholder="" value="N/A"
                            style="color: #525252;font-size: 14px;;border-radius: .25rem;background: #FFF;height: 35px; width:100%;"
                            required>
                    </div>
                </div>
            </div>

            <div class="modal-footer" style="background:#e9e9e9;">
                <div class="col-12">
                    <div class="float-left">
                        <button class="btn btn-block" id="clear_btn" onclick="clear_add_defect_record()"
                            style="color:#fff;height: 35px;width:180px;border-radius:.25rem;background: #474747;font-size:15px;font-weight:normal;"
                            onmouseover="this.style.backgroundColor='#2D2D2D'; this.style.color='#FFF';"
                            onmouseout="this.style.backgroundColor='#474747'; this.style.color='#FFF';">
                            Clear All
                        </button>
                    </div>
                    <div class="float-right">
                        <button class="btn btn-block" onclick="add_defect_record()"
                            style="color:#fff;height: 35px;width:180px;border-radius:.25rem;background: #8d0801;font-size:15px;font-weight:normal;"
                            onmouseover="this.style.backgroundColor='#792021'; this.style.color='#FFF';"
                            onmouseout="this.style.backgroundColor='#8d0801'; this.style.color='#FFF';">
                            Add Record
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>