<?php
include ('process/conn.php');
include ('plugins/system_plugins/header.php');
include ('plugins/system_plugins/preloader.php');
include ('plugins/system_plugins/navbar/index_navbar.php');
?>

<div class="content-wrapper" style="background: #F9F9F9;">
  <!-- Main content -->
  <section class="content">
    <div class="col-12 col-md-12 m-0 p-0">
      <div class="mt-4"></div>

      <div class="card mx-3">
        <!-- SEARCH FIELD -->
        <di class="card-body">
          <div class="row">
            <div class="col-12 col-sm-6 col-md-4 mb-2">
              <!-- qr scan -->
              <label style="font-weight:normal;margin:0;padding:0;color:#000;">Scan here</label>
              <input type="text" id="scan_qr" class="form-control pl-3" autocomplete="off"
                style="color: #525252;font-size: 15px;border-radius: .25rem;border: 1px solid #888888;background: #FFF;height:35px; width:100%;">
            </div>
            <div class="col-12 col-sm-6 col-md-2 mb-2">
              <!-- product name -->
              <label style="font-weight:normal;margin:0;padding:0;color:#000;">Product Number</label>
              <input type="text" id="scan_product_name" class="form-control pl-3" placeholder="Product Number"
                autocomplete="off"
                style="color: #525252;font-size: 15px;border-radius: .25rem;border: 1px solid #888888;background: #FFF;height:35px; width:100%;">
            </div>
            <div class="col-12 col-sm-6 col-md-2 mb-2">
              <!-- lot no -->
              <label style="font-weight:normal;margin:0;padding:0;color:#000;">Lot No.</label>
              <input type="text" id="scan_lot_no" class="form-control pl-3" placeholder="Lot No." autocomplete="off"
                style="color: #525252;font-size: 15px;border-radius: .25rem;border: 1px solid #888888;background: #FFF;height:35px; width:100%;">
            </div>
            <div class="col-12 col-sm-6 col-md-2 mb-2">
              <!-- serial no -->
              <label style="font-weight:normal;margin:0;padding:0;color:#000;">Serial No.</label>
              <input type="text" id="scan_serial_no" class="form-control pl-3" placeholder="Serial No."
                autocomplete="off"
                style="color: #525252;font-size: 15px;border-radius: .25rem;border: 1px solid #888888;background: #FFF;height:35px; width:100%;">
            </div>
            <div class="col-12 col-sm-6 col-md-2 mb-2">
              <!-- process -->
              <label style="font-weight:normal;margin:0;padding:0;color:#000;">Process</label>
              <!-- <input type="text" id="search_process" class="form-control" placeholder="Process" autocomplete="off"
                class="pl-3"
                style="color: #525252;font-size: 15px;border-radius: .25rem;border: 1px solid #888888;background: #FFF;height:35px; width:100%;"> -->
              <select id="search_process" class="form-control"
                style="color: #525252;font-size: 15px;border-radius: .25rem;border: 1px solid #888888;background: #FFF;height:35px; width:100%;">
                <option value="" disabled selected>Select Process</option>
              </select>
            </div>
          </div>

          <div class="row">
            <div class="col-12 col-sm-6 col-md-2 mb-2">
              <!-- line no. -->
              <label style="font-weight:normal;margin:0;padding:0;color:#000;">Line No.</label>
              <input type="text" id="search_line_no" class="form-control" placeholder="Line No." autocomplete="off"
                class="pl-3"
                style="color: #525252;font-size: 15px;border-radius: .25rem;border: 1px solid #888888;background: #FFF;height:35px; width:100%;">
            </div>

            <div class="col-12 col-sm-4 col-md-2 mb-2">
              <!-- date from -->
              <label style="font-weight:normal;margin:0;padding:0;color:#000;">Date From</label>
              <input type="date" name="date_from" class="form-control" id="search_date_from" placeholder="Date From"
                onfocus="(this.type='date')"
                style="color: #525252;font-size: 15px;border-radius: .25rem;border: 1px solid #888888;background: #FFF;height:35px; width:100%;">
            </div>
            <div class="col-12 col-sm-4 col-md-2 mb-2">
              <!-- date to -->
              <label style="font-weight:normal;margin:0;padding:0;color:#000;">Date To</label>
              <input type="date" name="date_to" class="form-control" id="search_date_to" placeholder="Date To"
                onfocus="(this.type='date')"
                style="color: #525252;font-size: 15px;border-radius: .25rem;border: 1px solid #888888;background: #FFF;height:35px; width:100%;">
            </div>
            <div class="col-12 col-sm-6 col-md-2 mb-2">
              <!-- defect category -->
              <label style="font-weight:normal;margin:0;padding:0;color:#000;">Defect Category</label>
              <select id="search_defect_category" class="form-control"
                style="color: #525252;font-size: 15px;border-radius: .25rem;border: 1px solid #888888;background: #FFF;height:35px; width:100%;">
                <option value="" disabled selected>Select Defect Category</option>
              </select>
            </div>
            <div class="col-12 col-sm-6 col-md-2 mb-2">
              <!-- defect details -->
              <label style="font-weight:normal;margin:0;padding:0;color:#000;">Defect Details</label>
              <select id="search_defect_details" class="form-control"
                style="color: #525252;font-size: 15px;border-radius: .25rem;border: 1px solid #888888;background: #FFF;height:35px; width:100%;">
                <option value="" disabled selected>Select Defect Details</option>
              </select>
            </div>
            <div class="col-12 col-sm-4 col-md-1 mb-2">
              <!-- clear all button -->
              <label for=""></label>
              <button class="btn btn-block d-flex justify-content-left" id="clear_btn"
                onclick="clear_search_defect_record()"
                style="color:#fff;height:35px;background: #474747;font-size:15px;font-weight:normal;"
                onmouseover="this.style.backgroundColor='#2D2D2D'; this.style.color='#FFF';"
                onmouseout="this.style.backgroundColor='#474747'; this.style.color='#FFF';">
                <i class="fas fa-trash" style="margin-top: 2px;"></i>&nbsp;Clear All
              </button>
            </div>

            <div class="col-12 col-sm-4 col-md-1 mb-2">
              <!-- refresh button -->
              <label for=""></label>
              <button class="btn btn-block d-flex justify-content-left" id="refresh_btn" onclick="refresh_page()"
                style="color:#fff;height:35px;background: #474747;font-size:15px;font-weight:normal;"
                onmouseover="this.style.backgroundColor='#2D2D2D'; this.style.color='#FFF';"
                onmouseout="this.style.backgroundColor='#474747'; this.style.color='#FFF';">
                <i class="fas fa-sync-alt" style="margin-top: 2px;"></i>&nbsp;Refresh
              </button>
            </div>
          </div>
          <div class="row justify-content-end">
            <div class="col-12 col-sm-4 col-md-2 mt-3">
              <!-- search button -->
              <button class="btn btn-block d-flex justify-content-left" id="search_btn" onclick="load_defect_table()"
                style="color:#fff;height:35px;border-radius:.25rem;background: #474747;font-size:15px;font-weight:normal;"
                onmouseover="this.style.backgroundColor='#2D2D2D'; this.style.color='#FFF';"
                onmouseout="this.style.backgroundColor='#474747'; this.style.color='#FFF';">
                <i class="fas fa-search" style="margin-top: 2px;"></i>&nbsp;&nbsp;Search</button>
            </div>
            <div class=" col-12 col-sm-4 col-md-2 mt-3">
              <!-- export button -->
              <button class="btn btn-block d-flex justify-content-left" id="export_record"
                onclick="export_defect_record()"
                style="color:#fff;height:35px;background: #335c67;font-size:15px;font-weight:normal;"
                onmouseover="this.style.backgroundColor='#27464F'; this.style.color='#FFF';"
                onmouseout="this.style.backgroundColor='#335c67'; this.style.color='#FFF';"><i class="fas fa-download"
                  style="margin-top: 2px;"></i>&nbsp;&nbsp;Export</button>
            </div>
            <div class=" col-12 col-sm-4 col-md-2 mt-3">
              <!-- add button -->
              <button class="btn btn-block d-flex justify-content-left" data-toggle="modal"
                data-target="#add_defect_record"
                style="color:#fff;height:35px;background: #9e2a2b;font-size:15px;font-weight:normal;"
                onmouseover="this.style.backgroundColor='#792021'; this.style.color='#FFF';"
                onmouseout="this.style.backgroundColor='#9e2a2b'; this.style.color='#FFF';"><i class="fas fa-plus"
                  style="margin-top: 2px;"></i>&nbsp;&nbsp;Add Record</button>
            </div>
          </div>
        </di>
      </div>

      <!-- MAIN FIELD -->
      <div class="card mx-3">
        <div class="card-body">
          <p class="p-0 m-0" style="color:#525252"><i class="fas fa-th-list" style="color:#525252"></i>&nbsp;Minor
            Defect Record Table</p>
          <div class="col-sm-3">
            <!-- view total count of data from table -->
            <span id="count_view_defect"></span>
          </div>

          <!-- table -->
          <div id="list_of_defect_res" class="card-body table-responsive m-0 p-0" style="max-height: 450px;">
            <table class="table col-12 mt-3 table-head-fixed text-nowrap table-hover" id="defect_table"
              style="background: #F9F9F9;">
              <thead style="text-align: center;">
                <th>#</th>
                <th>Datetime Detected</th>
                <th>Car Maker</th>
                <th>Car Model</th>
                <th>Line No.</th>
                <th>Process</th>
                <th>Group</th>
                <th>Shift</th>

                <!-- for group and shift values -->
                <!-- <th colspan="2">Shift</th> -->

                <th>Product Number</th>
                <th>Lot Number</th>
                <th>Serial Number</th>
                <th>Defect Category</th>
                <th>Defect Details</th>
                <th>Sequence No.</th>
                <th>Connector No.</th>
                <th>Repaired By</th>
                <th>Verified By</th>
              </thead>
              <tbody class="mb-0" id="list_of_defect">
                <tr>
                  <td colspan="12" style="text-align: center;">
                    <div class="spinner-border text-dark" role="status">
                      <span class="sr-only">Loading...</span>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <br>
          <div class="d-flex justify-content-sm-end">
            <div class="dataTables_info" id="defect_table_info" role="status" aria-live="polite"></div>
          </div>
          <div class="d-flex justify-content-sm-center">
            <button type="button" class="btn" style="background: #032b43; color: #fff;" id="btnNextPage"
              onclick="get_next_page()" onmouseover="this.style.backgroundColor='#032031'; this.style.color='#FFF';"
              onmouseout="this.style.backgroundColor='#032b43'; this.style.color='#FFF';">Load more</button>
          </div>
        </div>
  </section>
</div>

<?php
include ('plugins/system_plugins/footer.php');
include ('plugins/system_plugins/js/index_script.php');
?>