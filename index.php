<?php
include('process/conn.php');
include('plugins/system_plugins/header.php');
include('plugins/system_plugins/preloader.php');
include('plugins/system_plugins/navbar/index_navbar.php');
?>

<div class="content-wrapper" style="background: #f5f6fa;">
  <!-- Main content -->
  <section class="content pt-3">
    <div class="card mx-2 border-0 shadow-sm" style="background-color: #f8f9fa;">
      <!-- SEARCH FIELD -->
      <div class="card-body">
        <div class="row">
          <div class="col-12 col-sm-6 col-md-2 mb-2">
            <!-- line no. -->
            <label class="m-0 p-0 text-sm font-weight-normal">Line No.</label>
            <input type="text" id="search_line_no" class="form-control form-control-sm form-control-border" placeholder="Line No." autocomplete="off"
              class="pl-3">
          </div>
          <div class="col-12 col-sm-6 col-md-4 mb-2 d-none">
            <!-- qr scan -->
            <label class="m-0 p-0 text-sm font-weight-normal">Scan here</label>
            <input type="text" id="scan_qr" class="form-control form-control-sm form-control-border" autocomplete="off">
          </div>
          <div class="col-12 col-sm-6 col-md-2 mb-2 d-none">
            <!-- product name -->
            <label class="m-0 p-0 text-sm font-weight-normal">Product Number</label>
            <input type="text" id="scan_product_name" class="form-control form-control-sm form-control-border" placeholder="Product Number"
              autocomplete="off">
          </div>
          <div class="col-12 col-sm-6 col-md-2 mb-2">
            <!-- car maker -->
            <label class="m-0 p-0 text-sm font-weight-normal">Car Maker</label>
            <select id="search_car_maker" class="form-control form-control-sm form-control-border">
              <option value="" disabled selected>Select Car Maker</option>
              <option value="MAZDA">MAZDA</option>
              <option value="DAIHATSU">DAIHATSU</option>
              <option value="HONDA">HONDA</option>
              <option value="TOYOTA">TOYOTA</option>
              <option value="SUZUKI">SUZUKI</option>
              <option value="SUBARU">SUBARU</option>
              <option value="MARELLI">MARELLI</option>
            </select>
          </div>
          <div class="col-12 col-sm-6 col-md-2 mb-2">
            <!-- lot no -->
            <label class="m-0 p-0 text-sm font-weight-normal">Lot No.</label>
            <input type="text" id="scan_lot_no" class="form-control form-control-sm form-control-border" placeholder="Lot No." autocomplete="off">
          </div>
          <div class="col-12 col-sm-6 col-md-2 mb-2">
            <!-- serial no -->
            <label class="m-0 p-0 text-sm font-weight-normal">Serial No.</label>
            <input type="text" id="scan_serial_no" class="form-control form-control-sm form-control-border" placeholder="Serial No." autocomplete="off">
          </div>
          <div class="col-12 col-sm-6 col-md-2">
            <!-- search button -->
            <label></label>
            <button class="btn btn-outline-info btn-sm w-100" id="search_btn" onclick="load_defect_table(1)">
              <i class="fas fa-search"></i>&nbsp;Search</button>
          </div>
          <div class="col-12 col-sm-4 col-md-2">
            <!-- export button -->
            <label></label>
            <button class="btn btn-outline-secondary btn-sm w-100" id="export_record"
              onclick="export_defect_record()"><i class="fas fa-download"></i>&nbsp;Export</button>
          </div>
        </div>

        <div class="row mt-1">
          <div class="col-12 col-sm-4 col-md-2 mb-2">
            <!-- date from -->
            <label class="m-0 p-0 text-sm font-weight-normal">Date Detected From</label>
            <input type="date" name="date_from" class="form-control form-control-sm form-control-border" id="search_date_from" placeholder="Date From"
              onfocus="(this.type='date')">
          </div>
          <div class="col-12 col-sm-4 col-md-2 mb-2">
            <!-- date to -->
            <label class="m-0 p-0 text-sm font-weight-normal">Date Detected To</label>
            <input type="date" name="date_to" class="form-control form-control-sm form-control-border" id="search_date_to" placeholder="Date To"
              onfocus="(this.type='date')">
          </div>
          <div class="col-12 col-sm-6 col-md-2 mb-2">
            <!-- process -->
            <label class="m-0 p-0 text-sm font-weight-normal">Process</label>
            <select id="search_process" class="form-control form-control-sm form-control-border">
              <option value="" disabled selected>Select Process</option>
            </select>
          </div>
          <div class="col-12 col-sm-6 col-md-2 mb-2">
            <!-- defect category -->
            <label class="m-0 p-0 text-sm font-weight-normal">Defect Category</label>
            <select id="search_defect_category" class="form-control form-control-sm form-control-border">
              <option value="" disabled selected>Select Defect Category</option>
            </select>
          </div>
          <div class="col-12 col-sm-6 col-md-2 mb-2">
            <!-- defect details -->
            <label class="m-0 p-0 text-sm font-weight-normal">Defect Details</label>
            <select id="search_defect_details" class="form-control form-control-sm form-control-border">
              <option value="" disabled selected>Select Defect Details</option>
            </select>
          </div>
          <div class="col-12 col-sm-4 col-md-2">
            <!-- add button -->
            <label></label>
            <button id="add_record_btn" class="btn btn-primary btn-sm w-100" data-toggle="modal"
              data-target="#add_record_auth"><i class="fas fa-plus"></i>&nbsp;Add Record</button>
          </div>
        </div>
        <div class="row">
          <div class="col-12 col-sm-4 col-md-2 mt-2 offset-6 d-none">
            <!-- clear all button -->
            <button class="btn btn-block d-flex justify-content-left" id="clear_btn"
              onclick="clear_search_defect_record()"
              style="color:#fff;height:35px;background: #474747;font-size:14px;font-weight:normal;"
              onmouseover="this.style.backgroundColor='#2D2D2D'; this.style.color='#FFF';"
              onmouseout="this.style.backgroundColor='#474747'; this.style.color='#FFF';">
              <i class="fas fa-trash" style="margin-top: 2px;"></i>&nbsp;Clear
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- MAIN FIELD -->
    <div class="card mx-2 border-0 shadow-sm" style="background-color: #f8f9fa;">
      <div class="card-body">
        <p class="p-0 m-0" style="color:#525252; font-size: 15px;"><i class="far fa-folder"></i>&nbsp;Minor Defect Record Table</p>
        <div class="col-sm-3">
          <!-- view total count of data from table -->
          <span id="count_view_defect"></span>
        </div>

        <!-- table -->
        <div id="list_of_defect_res" class="card-body table-responsive m-0 p-0" style="max-height: 450px;">
          <table class="table col-12 mt-3 table-sm table-head-fixed text-nowrap table-hover" id="defect_table">
            <thead class="text-center text-sm">
              <th>#</th>
              <th>Date Detected</th>
              <th>Car Maker</th>
              <th>Car Model</th>
              <th>Line No.</th>
              <th>Harness Type</th>
              <th>Category</th>
              <th>Process</th>
              <th>Group</th>
              <th>Shift</th>
              <th>Product Name</th>
              <th>Lot No.</th>
              <th>Serial No.</th>
              <th>Defect Category Code</th>
              <th>Defect Category</th>
              <th>Defect Details Code</th>
              <th>Defect Details</th>
              <th>Occurrence Shift</th>
              <th>Occurrence Board No.</th>
              <th>Occurrence Station No.</th>
              <th>Treatment Content of Defect</th>
              <th>Sequence No.</th>
              <th>Connector No.</th>
              <th>Total Time (mins)</th>
              <th>Repaired By</th>
              <th>Verified By</th>
              <th>Added By</th>
            </thead>
            <tbody class="mb-0 text-xs" id="list_of_defect"></tbody>
          </table>
        </div>
        <br>
        <div class="d-flex justify-content-sm-start">
          <div class="dataTables_info" id="defect_table_info" role="status" aria-live="polite"></div>
        </div>
        <div class="d-flex justify-content-sm-center">
          <button type="button" class="btn btn-outline-dark btn-sm" id="btnNextPage"
            onclick="get_next_page()">Load more</button>
        </div>
      </div>
    </div>
  </section>
</div>

<?php
include('plugins/system_plugins/footer.php');
include('plugins/system_plugins/js/index_script.php');
?>