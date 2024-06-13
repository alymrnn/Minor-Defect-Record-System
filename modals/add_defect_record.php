<div class="modal fade bd-example-modal-xl" id="add_defect_record" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content" style="background:#f9f9f9;">
            <div class="modal-header" style="background:#9e2a2b;">
                <h5 class="modal-title" id="exampleModalLabel" style="font-weight: normal;color: #fff;"><i
                        class="fas fa-plus"></i>&nbsp;
                    Add Record
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: #fff;">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <label style="font-weight: normal;color: #000;font-size:25px"><b>Minor Defect Record</b></label>

                <!-- <div class="card"> -->
                <!-- <div class="card-body"> -->
                <div class="row mb-4">
                    <div class="col-12 col-md-2">
                        <!-- defect id hidden -->
                        <input type="hidden" id="defect_id_no" class="form-control">

                        <label style="font-weight: normal;color: #000;">Date Detected</label>
                        <label style="color:#CA3F3F">*</label>
                        <input type="date" id="a_date_detected" class="form-control" autocomplete="off"
                            style="color: #525252;font-size: 15px;border-radius: .25rem;background: #FFF;height: 35px; width:100%;"
                            required>
                        <span id="dateDetectedError" class="error-message" style="display:none; color:#CA3F3F;">Date
                            Detected field is required.</span>
                    </div>
                    <div class="col-12 col-md-2">
                        <label style="font-weight: normal;color: #000;">Car Model</label>
                        <label style="color:#CA3F3F">*</label>
                        <select id="a_car_model" class="form-control" onchange="handleCarMakerChange(this)"
                            style="color: #525252; font-size: 15px; border-radius: .25rem; background: #FFF; height: 35px; width: 100%;"
                            required>
                            <option value="" disabled selected>Select Car Model</option>
                            <option value="Mazda">Mazda</option>
                            <option value="Daihatsu">Daihatsu</option>
                            <option value="Honda">Honda</option>
                            <option value="Toyota">Toyota</option>
                            <option value="Suzuki">Suzuki</option>
                            <option value="Nissan">Nissan</option>
                            <option value="Subaru">Subaru</option>
                        </select>

                        <span id="carModelError" class="error-message" style="display:none; color:#CA3F3F;">Car
                            Model field is required.</span>
                    </div>
                    <div class="col-12 col-md-2">
                        <label style="font-weight: normal;color: #000;">Line No.</label>
                        <label style="color:#CA3F3F">*</label>
                        <input type="text" id="a_line_no" class="form-control" autocomplete="off" placeholder=""
                            style="color: #525252;font-size: 15px;border-radius: .25rem;background: #FFF;height: 35px; width:100%;"
                            required>
                        <span id="lineNoError" class="error-message" style="display:none; color:#CA3F3F;">Line
                            No. field is required.</span>
                    </div>
                    <div class="col-12 col-md-2">
                        <label style="font-weight: normal;color: #000;">Process</label>
                        <label style="color:#CA3F3F">*</label>
                        <input type="text" id="a_process" class="form-control" autocomplete="off" placeholder=""
                            style="color: #525252;font-size: 15px;border-radius: .25rem;background: #FFF;height: 35px; width:100%;"
                            required>
                        <span id="processError" class="error-message" style="display:none; color:#CA3F3F;">Process field
                            is required.</span>
                    </div>
                    <div class="col-12 col-md-2">
                        <label style="font-weight: normal;color: #000;">Group</label>
                        <label style="color:#CA3F3F">*</label>
                        <select id="a_group" class="form-control"
                            style="color: #525252; font-size: 15px; border-radius: .25rem; background: #FFF; height: 35px; width: 100%;"
                            required>
                            <option value="" disabled selected>Select Group</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                        </select>
                        <span id="groupError" class="error-message" style="display:none; color:#CA3F3F;">Group
                            field is required.</span>
                    </div>
                    <div class="col-12 col-md-2">
                        <label style="font-weight: normal;color: #000;">Shift</label>
                        <label style="color:#CA3F3F">*</label>
                        <select id="a_shift" class="form-control"
                            style="color: #525252; font-size: 15px; border-radius: .25rem; background: #FFF; height: 35px; width: 100%;"
                            required>
                            <option value="" disabled selected>Select Shift</option>
                            <option value="Day Shift">Day Shift</option>
                            <option value="Night Shift">Night Shift</option>
                        </select>
                        <span id="shiftError" class="error-message" style="display:none; color:#CA3F3F;">Shift
                            field is required.</span>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <label style="font-weight: normal;color: #000;">Scan QR-Code</label>
                        <input type="text" id="a_scan_qr" class="form-control" autocomplete="off"
                            style="color: #525252;font-size: 15px;border-radius: .25rem;background: #FFF;height: 35px; width:100%;">
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-12 col-md-4 mb-2">
                        <label style="font-weight: normal;color: #000;">Product Number</label>
                        <label style="color:#CA3F3F">*</label>
                        <input type="text" id="a_product_name" class="form-control" autocomplete="off"
                            style="color: #525252;font-size: 15px;border-radius: .25rem;background: #F1F1F1;height: 35px; width:100%;"
                            readonly>
                        <span id="productNoError" class="error-message" style="display:none; color:#CA3F3F;">Product
                            Number field is required.</span>
                        <br>
                    </div>
                    <div class="col-12 col-md-4 mb-2">
                        <label style="font-weight: normal;color: #000;">Lot No.</label>
                        <label style="color:#CA3F3F">*</label>
                        <input type="text" id="a_lot_no" class="form-control" autocomplete="off"
                            style="color: #525252;font-size: 15px;border-radius: .25rem;background: #F1F1F1;height: 35px; width:100%;"
                            readonly>
                        <span id="lotNoError" class="error-message" style="display:none; color:#CA3F3F;">Lot No.
                            field is required.</span>
                    </div>
                    <div class="col-12 col-md-4 mb-2">
                        <label style="font-weight: normal;color: #000;">Serial No.</label>
                        <label style="color:#CA3F3F">*</label>
                        <input type="text" id="a_serial_no" class="form-control" autocomplete="off"
                            style="color: #525252;font-size: 15px;border-radius: .25rem;background: #F1F1F1;height: 35px; width:100%;"
                            readonly>
                        <span id="serialNoError" class="error-message" style="display:none; color:#CA3F3F;">Serial No.
                            field is required.</span>
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-12 col-md-4 mb-2">
                        <label style="font-weight: normal;color: #000;">Defect Category</label>
                        <label style="color:#CA3F3F">*</label>
                        <select id="a_defect_category" class="form-control"
                            style="color: #525252; font-size: 15px; border-radius: .25rem; background: #FFF; height: 35px; width: 100%;"
                            required>
                            <option value="" disabled selected>Select Defect Category</option>
                        </select>
                        <span id="defectCategoryError" class="error-message" style="display:none; color:#CA3F3F;">Defect
                            Category field is required.</span>
                    </div>
                    <div class="col-12 col-md-4 mb-2">
                        <label style="font-weight: normal;color: #000;">Defect Details</label>
                        <label style="color:#CA3F3F">*</label>
                        <select id="a_defect_details" class="form-control"
                            style="color: #525252; font-size: 15px; border-radius: .25rem; background: #FFF; height: 35px; width: 100%;"
                            required>
                            <option value="" disabled selected>Select Defect Details</option>
                        </select>
                        <span id="defectDetailsError" class="error-message" style="display:none; color:#CA3F3F;">Defect
                            Details field is required.</span>
                    </div>
                    <div class="col-12 col-md-2 mb-2">
                        <label style="font-weight: normal;color: #000;">Sequence No.</label>
                        <label style="color:#CA3F3F">*</label>
                        <input type="text" id="a_sequence_no" class="form-control" autocomplete="off" placeholder=""
                            style="color: #525252;font-size: 15px;border-radius: .25rem;background: #FFF;height: 35px; width:100%;"
                            required>
                        <span id="sequenceNoError" class="error-message" style="display:none; color:#CA3F3F;">Sequence
                            No. field is required.</span>
                    </div>
                    <div class="col-12 col-md-2 mb-2">
                        <label style="font-weight: normal;color: #000;">Connector No.</label>
                        <label style="color:#CA3F3F">*</label>
                        <input type="text" id="a_connector_no" class="form-control" autocomplete="off" placeholder=""
                            style="color: #525252;font-size: 15px;border-radius: .25rem;background: #FFF;height: 35px; width:100%;"
                            required>
                        <span id="connectorNoError" class="error-message" style="display:none; color:#CA3F3F;">Connector
                            No. field is required.</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-md-2 mb-2">
                        <label style="font-weight: normal;color: #000;">Repaired By</label>
                        <label style="color:#CA3F3F">*</label>
                        <input type="text" id="a_repaired_by" class="form-control" autocomplete="off" placeholder=""
                            style="color: #525252;font-size: 15px;border-radius: .25rem;background: #FFF;height: 35px; width:100%;"
                            required>
                        <span id="repairedByError" class="error-message" style="display:none; color:#CA3F3F;">Repaired
                            By field is required.</span>
                    </div>
                    <div class="col-12 col-md-2 mb-2">
                        <label style="font-weight: normal;color: #000;">Verified By</label>
                        <label style="color:#CA3F3F">*</label>
                        <input type="text" id="a_verified_by" class="form-control" autocomplete="off" placeholder=""
                            style="color: #525252;font-size: 15px;border-radius: .25rem;background: #FFF;height: 35px; width:100%;"
                            required>
                        <span id="verifiedByError" class="error-message" style="display:none; color:#CA3F3F;">Verified
                            By field is required.</span>
                    </div>
                </div>
                <!-- </div> -->
                <!-- </div> -->
            </div>

            <div class="modal-footer" style="background:#e9e9e9;">
                <div class="col-12">
                    <div class="float-left">
                        <button class="btn btn-block" id="clear_btn" onclick="clear_defect_record()"
                            style="color:#fff;height: 35px;width:180px;border-radius:.25rem;background: #474747;font-size:15px;font-weight:normal;"
                            onmouseover="this.style.backgroundColor='#2D2D2D'; this.style.color='#FFF';"
                            onmouseout="this.style.backgroundColor='#474747'; this.style.color='#FFF';">
                            Clear All
                        </button>
                    </div>
                    <div class="float-right">
                        <button class="btn btn-block" onclick="add_defect_record()"
                            style="color:#fff;height: 35px;width:180px;border-radius:.25rem;background: #226F54;font-size:15px;font-weight:normal;"
                            onmouseover="this.style.backgroundColor='#1C5944'; this.style.color='#FFF';"
                            onmouseout="this.style.backgroundColor='#226F54'; this.style.color='#FFF';">
                            Add Record
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>