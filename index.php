<?php
include('process/conn.php');
include('plugins/system_plugins/header.php');
include('plugins/system_plugins/preloader.php');
include('plugins/system_plugins/navbar/index_navbar.php');
?>

<div class="content-wrapper" style="background: #fff;">
  <div class="content-header m-0">
    <div class="container-fluid">
      <div class="row align-items-center">

        <div class="col-12 col-sm-6">
          <h5 class="m-0 text-md text-start text-sm-start">
            Minor Defect Record Monitoring
          </h5>
        </div>

        <div class="col-12 col-sm-6 d-none d-sm-block">
          <ol class="breadcrumb justify-content-sm-end mb-0">
            <li class="breadcrumb-item text-xs">
              <a href="index.php">Minor Defect Record System</a>
            </li>
            <li class="breadcrumb-item text-xs active">
              Minor Defect Record Monitoring
            </li>
          </ol>
        </div>

      </div>
    </div>
  </div>

  <!-- Main content -->
  <section class="content py-2">
    <div class="chart-border mx-1">
      <!-- SEARCH FIELD -->
      <div class="card-body m-0 p-0 p-2">
        <div class="row">
          <div class="col-12 col-sm-6 col-md-2 mb-2">
            <!-- line no. -->
            <label class="m-0 p-0 label-xs text-muted font-weight-normal">Line No.</label>
            <!-- <input type="text" id="search_line_no" class="form-control form-control-sm form-control-border text-xs" placeholder="Line No." autocomplete="off"
              class="pl-3"> -->

            <select id="search_line_no" class="form-control form-control-sm form-control-border text-xs">
              <option value="" disabled selected>Select Line No.</option>
            </select>
          </div>
          <div class="col-12 col-sm-6 col-md-4 mb-2 d-none">
            <!-- qr scan -->
            <label class="m-0 p-0 text-xs font-weight-normal">Scan here</label>
            <input type="text" id="scan_qr" class="form-control form-control-sm form-control-border text-xs" autocomplete="off">
          </div>
          <div class="col-12 col-sm-6 col-md-2 mb-2 d-none">
            <!-- product name -->
            <label class="m-0 p-0 label-xs text-muted font-weight-normal">Product Number</label>
            <input type="text" id="scan_product_name" class="form-control form-control-sm form-control-border text-xs" placeholder="Product Number"
              autocomplete="off">
          </div>
          <div class="col-12 col-sm-6 col-md-2 mb-2 d-none d-md-block">
            <!-- car maker -->
            <label class="m-0 p-0 label-xs text-muted font-weight-normal">Car Maker</label>
            <select id="search_car_maker" class="form-control form-control-sm form-control-border text-xs">
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
          <div class="col-12 col-sm-6 col-md-2 mb-2 d-none d-md-block">
            <!-- lot no -->
            <label class="m-0 p-0 label-xs text-muted font-weight-normal">Lot No.</label>
            <input type="text" id="scan_lot_no" class="form-control form-control-sm form-control-border text-xs" placeholder="Lot No." autocomplete="off">
          </div>
          <div class="col-12 col-sm-6 col-md-2 mb-2 d-none d-md-block">
            <!-- serial no -->
            <label class="m-0 p-0 label-xs text-muted font-weight-normal">Serial No.</label>
            <input type="number" id="scan_serial_no" class="form-control form-control-sm form-control-border text-xs" placeholder="Serial No." autocomplete="off">
          </div>
          <div class="col-12 col-sm-6 col-md-2 mb-2 d-none d-md-block">
            <!-- process -->
            <label class="m-0 p-0 label-xs text-muted font-weight-normal">Process</label>
            <select id="search_process" class="form-control form-control-sm form-control-border text-xs">
              <option value="" disabled selected>Select Process</option>
            </select>
          </div>
          <div class="col-12 col-sm-6 col-md-2 mb-2 d-none d-md-block">
            <!-- export button -->
            <label></label>
            <button class="btn btn-outline-secondary btn-sm w-100 text-xs" id="export_record"
              onclick="export_defect_record()"><i class="fas fa-download"></i>&nbsp;Export</button>
          </div>
        </div>

        <div class="row mt-1">
          <div class="col-12 col-sm-4 col-md-2 mb-2">
            <!-- date from -->
            <label class="m-0 p-0 label-xs text-muted font-weight-normal">Date Detected From</label>
            <input type="date" name="date_from" class="form-control form-control-sm form-control-border text-xs" id="search_date_from" placeholder="Date From"
              onfocus="(this.type='date')">
          </div>
          <div class="col-12 col-sm-4 col-md-2 mb-2">
            <!-- date to -->
            <label class="m-0 p-0 label-xs text-muted font-weight-normal">Date Detected To</label>
            <input type="date" name="date_to" class="form-control form-control-sm form-control-border text-xs" id="search_date_to" placeholder="Date To"
              onfocus="(this.type='date')">
          </div>
          <div class="col-12 col-sm-6 col-md-2 mb-2 d-none d-md-block">
            <!-- defect category -->
            <label class="m-0 p-0 label-xs text-muted font-weight-normal">Defect Category</label>
            <select id="search_defect_category" class="form-control form-control-sm form-control-border text-xs">
              <option value="" disabled selected>Select Defect Category</option>
            </select>
          </div>

          <div class="col-12 col-sm-6 col-md-2 mb-2 d-none d-md-block">
            <!-- defect details -->
            <label class="m-0 p-0 label-xs text-muted font-weight-normal">Defect Details</label>
            <select id="search_defect_details" class="form-control form-control-sm form-control-border text-xs">
              <option value="" disabled selected>Select Defect Details</option>
            </select>
          </div>
          <div class="col-12 col-sm-6 col-md-2">
            <!-- search button -->
            <label></label>
            <button class="btn btn-outline-info btn-sm w-100 text-xs" id="search_btn" onclick="load_defect_table(1)">
              <i class="fas fa-search"></i>&nbsp;Search</button>
          </div>
          <div class="col-12 col-sm-4 col-md-2">
            <!-- add button -->
            <label></label>
            <button id="add_record_btn" class="btn btn-primary btn-sm w-100 text-xs" data-toggle="modal"
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
    <div class="chart-border mx-1 mt-2">
      <div class="card-body m-0 p-0 p-1">
        <!-- <p class="p-0 m-0 text-secondary text-xs"><i class="far fa-folder"></i>&nbsp;Minor Defect Record Table</p> -->
        <div class="col-sm-3">
          <!-- view total count of data from table -->
          <span id="count_view_defect"></span>
        </div>

        <div class="row">
          <div class="col-12 m-0 p-0 px-1">
            <p class="p-1 mb-2 label-xs" style="background: #f8f9fa; border-left: 3px solid #ddd; color: #22577a;">
              <i>Note:</i>
              Records for the current month are shown in descending order. Update the date range as needed.
            </p>
          </div>
        </div>

        <!-- table -->
        <div id="list_of_defect_res" class="card-body table-responsive m-0 p-0" style="max-height: 450px;">
          <table class="table col-12 table-sm table-head-fixed text-nowrap table-hover" id="defect_table">
            <thead class="text-center label-xs text-uppercase">
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
            <tbody class="mb-0 label-xs" id="list_of_defect"></tbody>
          </table>
        </div>
        <br>
        <div class="d-flex justify-content-sm-start">
          <div class="dataTables_info" id="defect_table_info" role="status" aria-live="polite"></div>
        </div>
        <div class="d-flex justify-content-sm-center">
          <button type="button" class="btn btn-outline-secondary btn-sm label-xs" id="btnNextPage"
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