<?php include 'plugins/navbar.php'; ?>
<?php include 'plugins/sidebar/minor_record_m_bar.php'; ?>

<div class="content-wrapper bg-white">
   <div class="content-header">
      <div class="container-fluid">
         <div class="row">
            <div class="col-sm-6">
               <h4 class="m-0">Minor Record List</h4>
            </div>
            <div class="col-sm-6">
               <ol class="breadcrumb float-sm-right text-sm">
                  <li class="breadcrumb-item"><a href="minor_record_m.php">Minor Defect Record System</a></li>
                  <li class="breadcrumb-item active">Minor Record List</li>
               </ol>
            </div>
         </div>
      </div>
   </div>

   <section class="content">
      <div class="row mx-2">
         <div class="col-12 col-sm-4 col-md-2 mb-2">
            <!-- date from -->
            <label class="m-0 p-0 text-sm font-weight-normal">Date Detected From</label>
            <input type="date" name="date_from" class="form-control form-control-sm form-control-border" id="m_search_date_from" placeholder="Date From"
               onfocus="(this.type='date')">
         </div>
         <div class="col-12 col-sm-4 col-md-2 mb-2">
            <!-- date to -->
            <label class="m-0 p-0 text-sm font-weight-normal">Date Detected To</label>
            <input type="date" name="date_to" class="form-control form-control-sm form-control-border" id="m_search_date_to" placeholder="Date To"
               onfocus="(this.type='date')">
         </div>
         <div class="col-12 col-sm-6 col-md-2 mb-2">
            <!-- line no. -->
            <label class="m-0 p-0 text-sm font-weight-normal">Line No.</label>
            <input type="text" id="m_search_line_no" class="form-control form-control-sm form-control-border" placeholder="Line No." autocomplete="off">
         </div>
          <div class="col-12 col-sm-6 col-md-2 mb-2">
            <!-- lot no. -->
            <label class="m-0 p-0 text-sm font-weight-normal">Lot No.</label>
            <input type="text" id="m_search_lot_no" class="form-control form-control-sm form-control-border" placeholder="Lot No." autocomplete="off">
         </div>
         <div class="col-12 col-sm-6 col-md-2 mb-2">
            <!-- process -->
            <label class="m-0 p-0 text-sm font-weight-normal">Process</label>
            <input type="text" id="m_search_process" class="form-control form-control-sm form-control-border" placeholder="Process" autocomplete="off">
         </div>
         <div class="col-12 col-sm-6 col-md-2">
            <!-- search button -->
            <label></label>
            <button class="btn btn-outline-info btn-sm w-100" id="search_btn" onclick="load_minor_defect_list(1)">
               <i class="fas fa-search"></i>&nbsp;Search</button>
         </div>
      </div>

      <!-- MAIN FIELD -->
      <div class="card mx-2">
         <div class="card-body">
            <div class="table-responsive m-0 p-0 mt-3" style="max-height: 500px;">
               <table class="table table-sm table-head-fixed text-nowrap table-hover table-bordered" id="defect_table">
                  <thead class="text-center text-sm">
                     <th>#</th>
                     <th>Action</th>
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
                     <th>Total Time</th>
                     <th>Repaired By</th>
                     <th>Verified By</th>
                     <th>Added By</th>
                  </thead>
                  <tbody class="mb-0 text-xs" id="list_of_minor_defect"></tbody>
               </table>
            </div>
            <div id="pagination_controls" class="mt-2"></div>
         </div>
      </div>
   </section>
</div>

<?php include 'plugins/footer.php'; ?>
<?php include 'plugins/js/minor_record_m_script.php'; ?>