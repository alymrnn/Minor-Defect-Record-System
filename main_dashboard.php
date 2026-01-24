<?php
include('process/conn.php');
include('plugins/system_plugins/header.php');
include('plugins/system_plugins/preloader.php');
include('plugins/system_plugins/navbar/index_navbar.php');
?>

<div class="content-wrapper" style="background: #f8f9fa;">
   <!-- Main content -->
   <section class="content">
      <div class="d-side-nav collapse" id="sidebar">
         <div class="row">
            <div class="col-12">
               <div class="mt-2 d-flex justify-content-end align-items-center">
                  <p class="m-0 p-0 btn btn-primary btn-sm"
                     style="font-size: 12px; transition: 0.3s; cursor: pointer;"
                     onclick="generate_dashboard_filter()">
                     &nbsp;Generate&nbsp;
                  </p>
               </div>

               <p class="m-0 p-0 text-left d-none" style="font-size: 12px;">Line No.</p>
               <select name="d_pd_type" id="d_line_no" autocomplete="off"
                  class="pl-2 form-control form-control-sm form-control-border mb-1 d-none"
                  style="font-size: 13px;" required>
                  <option value="" disabled selected>Line No.</option>
               </select>

               <p class="m-0 p-0 text-left text-dark text-xs">Date From</p>
               <input type="text" name="date_from"
                  class="form-control form-control-sm form-control-border mb-1"
                  id="d_date_from" placeholder="Date From"
                  onfocus="this.type='date';" onblur="if(this.value==''){this.type='text';}">
               <p class="m-0 p-0 text-left text-dark text-xs">Date To</p>
               <input type="text" name="date_to"
                  class="form-control form-control-sm form-control-border mb-1"
                  id="d_date_to" placeholder="Date To"
                  onfocus="this.type='date';" onblur="if(this.value==''){this.type='text';}">
            </div>
         </div>
      </div>

      <!-- Main content -->
      <div class="flex-grow-1" id="mainContent">
         <div id="stickyHeader" class="sticky-header d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center m-1">
               <button id="toggleBtn" class="btn btn-default btn-sm">
                  <i class="fas fa-bars"></i>
               </button>
               <span style="color: #ddd;">&nbsp; | &nbsp;</span>
               <p class="mb-0 me-2 text-sm text-muted">Daily Monitoring</p>
            </div>
         </div>

         <div class="col-12">
            <div class="row">
               <div class="col-2">
                  <div class="count-border">
                     <p class="m-0 p-0" style="font-size: 13px;">Defect Record</p>
                     <h2 id="total_record_count" class="text-bold"></h2>
                     <hr>
                     <p for="prev-comparison-label" class="mt-2 m-0 p-0" style="font-size: 13px;">Previous Month Record</p>
                     <h2 id="total_comparison_count" class="text-bold"></h2>
                  </div>
               </div>
               <div class="col-7">
                  <div class="chart-border">
                     <div id="top_overall_defect_category"></div>
                  </div>
               </div>
               <div class="col-3">
                  <div class="chart-border">
                     <div id="top_overall_defect_details_chart" class="d-flex align-items-center justify-content-start text-center pl-2" style="height: 100%;">
                        <p id="overall_defect_details_chart_placeholder" class="mb-0 text-info small">
                           Select from Top Defect Category Chart to generate Defect Details
                        </p>
                     </div>
                  </div>
               </div>
            </div>

            <div class="row mt-2">
               <div class="col-3">
                  <div class="chart-border">
                     <div id="top_lot_no_chart"></div>
                  </div>
               </div>
               <div class="col-3">
                  <div class="chart-border">
                     <div id="top_sequence_no_chart"></div>
                  </div>
               </div>
               <div class="col-3">
                  <div class="chart-border">
                     <div id="top_connector_no_chart"></div>
                  </div>
               </div>
               <div class="col-3">
                  <div class="chart-border">
                     <div id="top_overall_line_no_chart" class="d-flex align-items-center justify-content-start text-center pl-2" style="height: 100%;">
                        <p id="overall_line_no_chart_placeholder" class="mb-0 text-info small">
                           Select from Top Defect Category Chart to generate Top Line No.'s with Selected Defect
                        </p>
                     </div>
                  </div>
               </div>
            </div>

            <p class="mt-4 m-0 p-0 text-sm text-dark">Section Monitoring</p>
            <hr class="m-0 p-0" style="background: #eee;">
            <div class="row mt-2">
               <div class="col-4">
                  <div class="chart-border">
                     <div id="top_defect_category_per_section_chart"></div>
                  </div>
               </div>
               <div class="col-4">
                  <div class="chart-border">
                     <div id="defect_record_per_section_chart"></div>
                  </div>
               </div>
               <div class="col-4">
                  <div class="chart-border">
                     <div id="top_line_no_section_based_chart" class="d-flex align-items-center justify-content-start text-center pl-2" style="height: 100%;">
                        <p id="line_no_section_based_chart_placeholder" class="mb-0 text-info small">
                           Select a section from Defect Records per Section Chart to generate its Top Line No.
                        </p>
                     </div>
                  </div>
               </div>
            </div>

            <p class="mt-4 m-0 p-0 text-sm text-dark">Daily Trend Monitoring</p>
            <hr class="m-0 p-0" style="background: #eee;">
            <div class="row mt-2">
               <div class="col-12">
                  <div class="chart-border">
                     <div id="daily_trend_chart"></div>
                  </div>
               </div>
            </div>
            <div class="row mt-2 mb-3">
               <div class="col-7">
                  <div class="chart-border">
                     <div id="top_daily_defect_category_chart" class="d-flex align-items-center justify-content-start text-center pl-2" style="height: 100%;">
                        <p id="defect_category_chart_placeholder" class="mb-0 text-info small">
                           Select a date from Daily Defect Trend Chart to generate Top Defect Category
                        </p>
                     </div>
                  </div>
               </div>
               <div class="col-5">
                  <div class="chart-border">
                     <div id="top_daily_line_no_chart" class="d-flex align-items-center justify-content-start text-center pl-2" style="height: 100%;">
                        <p id="line_no_chart_placeholder" class="mb-0 text-info small">
                           Select a date from Daily Defect Trend Chart to generate Top Line No.
                        </p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>

      <?php
      include('plugins/system_plugins/footer_dashboard.php');
      ?>

   </section>
</div>

<?php
include('plugins/system_plugins/js/main_dashboard_script.php');
?>