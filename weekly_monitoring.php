<?php
include('process/conn.php');
include('plugins/system_plugins/header.php');
include('plugins/system_plugins/preloader.php');
include('plugins/system_plugins/navbar/index_navbar.php');
?>

<div class="content-wrapper" style="background: #f8f9fa;">
   <!-- Main content -->
   <section class="content">
      <div class="d-side-nav collapse" id="sidebarOverall">
         <div class="row">
            <div class="col-12">
               <div class="mt-2 d-flex justify-content-end align-items-center">
                  <button class="m-0 p-0 btn btn-primary btn-xs"
                     onclick="generate_weekly_dashboard_filter()">
                     &nbsp;Generate&nbsp;
                  </button>
               </div>

               <div class="filter-border mt-2 mb-2">
                  <p class="m-0 p-0 mb-1 text-xs">Defect Category</p>
                  <div id="defect_category_container"></div>
               </div>

               <div class="filter-border mb-2">
                  <p class="m-0 p-0 mb-1 text-xs">Year</p>
                  <div id="year_container"></div>
               </div>

               <div class="filter-border mb-2">
                  <p class="m-0 p-0 mb-1 text-xs">Month</p>
                  <div id="month_container"></div>
               </div>

               <div class="filter-border mb-2 d-none">
                  <p class="m-0 p-0 mb-1 text-xs">Week</p>
                  <div id="week_container"></div>
               </div>

               <div class="filter-border mb-2">
                  <p class="m-0 p-0 mb-1 text-xs">Section</p>
                  <div id="section_container"></div>
               </div>

               <div class="filter-border mb-2">
                  <p class="m-0 p-0 mb-1 text-xs">Process</p>
                  <div id="process_container"></div>
               </div>

               <div class="filter-border mb-2">
                  <p class="m-0 p-0 mb-1 text-xs">Line Category</p>
                  <div id="line_category_container"></div>
               </div>

               <div class="filter-border mb-2">
                  <p class="m-0 p-0 mb-1 text-xs">Line No.</p>
                  <div id="line_container"></div>
               </div>


               <!-- hide this -->
               <div class="d-none">
                  <p class="m-0 p-0 text-left text-dark text-xs">Year</p>
                  <select id="d_year" class="form-control form-control-sm form-control-border mb-1"></select>

                  <p class="m-0 p-0 text-left text-dark text-xs">Month</p>
                  <select id="d_month" class="form-control form-control-sm form-control-border mb-1"></select>
               </div>
            </div>
         </div>
      </div>

      <!-- Main content -->
      <div class="flex-grow-1" id="mainContentOverall">
         <div id="stickyHeaderOverall" class="sticky-header d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center m-1">
               <button id="toggleBtnOverall" class="btn btn-default btn-sm">
                  <i class="fas fa-bars"></i>
               </button>
               <span style="color: #ddd;">&nbsp; | &nbsp;</span>
               <p class="mb-0 me-2 text-sm text-muted">Overall Monitoring</p>
            </div>
         </div>

         <div class="row mx-1">
            <div class="col-4">
               <div class="chart-border">
                  <!-- this is the total count of defect record per month sorted by week -->
                  <div id="overall_month_week_chart"></div>
               </div>
            </div>
            <div class="col-4">
               <div class="chart-border">
                  <!-- this is the breakdown per harness type -->
                  <div id="harness_type_breakdown_chart"></div>
               </div>
            </div>
            <div class="col-4">
               <div class="chart-border">
                  <!-- this is the summary per detection -->
                  <div id="summary_per_detection_chart"></div>
               </div>
            </div>
         </div>

         <div class="row mx-1 mt-2">
            <div class="col-6">
               <div class="chart-border">
                  <!-- this is the overall minor record per section -->
                  <div id="overall_record_per_section_chart"></div>
               </div>
            </div>
            <div class="col-6">
               <div class="chart-border">
                  <!-- this is the top 10 lines based on month and defect category -->
                  <div id="top_ten_lines_chart"></div>
               </div>
            </div>
         </div>

         <div class="row mx-1 mt-2">
            <div class="col-5">
               <div class="chart-border">
                  <!-- this is the defect category breakdown per month sorted by week -->
                  <div id="defect_category_breakdown_chart"></div>
               </div>
            </div>
            <div class="col-7">
               <div class="chart-border">
                  <!-- this is the breakdown of the sub defect category per main category sorted by week -->
                  <div id="sub_defect_details_breakdown_chart"></div>
               </div>
            </div>
         </div>

         <div class="row mx-1 mt-2 mb-3">
            <div class="col-4">
               <div class="chart-border">
                  <!-- this is the top 5 sequence no by process -->
                  <div id="sequence_no_breakdown_chart"></div>
               </div>
            </div>
            <div class="col-4">
               <div class="chart-border">
                  <!-- this is the top 5 connector no by process -->
                  <div id="connector_no_breakdown_chart"></div>
               </div>
            </div>
            <div class="col-4">
               <div class="chart-border">
                  <!-- this is the weekly breakdown of line category based on year/month/defect -->
                  <div id="line_category_month_week_chart"></div>
               </div>
            </div>
         </div>

         <!-- hide this -->
         <div class="col-12 d-none">
            <p class="pt-3 m-0 p-0 text-sm">Weekly Trend Monitoring</p>
            <hr class="m-0 p-0" style="background: #eee;">

            <div class="row mt-2">
               <div class="col-12">
                  <div class="chart-border">
                     <div id="weekly_defect_category_count_chart" style="border-radius: 8px;"></div>
                  </div>
               </div>
            </div>

            <div class="row mt-2">
               <div class="col-12">
                  <div class="chart-border">
                     <div id="weekly_top_lines_based_on_defect_category_chart" class="d-flex align-items-center justify-content-start text-center pl-2" style="height: 100%;">
                        <p id="weekly_top_lines_chart_placeholder" class="mb-0 text-info small">
                           Select from Weekly Record per Defect Category to generate Top 10 Lines of Selected Defect Category per Week
                        </p>
                     </div>
                  </div>
               </div>
            </div>

            <div class="row mt-2 mb-3">
               <div class="col-12">
                  <div class="chart-border">
                     <div id="weekly_defect_per_section_chart" style="border-radius: 8px;"></div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <?php
      include('plugins/system_plugins/footer_dashboard_overall.php');
      ?>
   </section>
</div>

<?php
include('plugins/system_plugins/js/weekly_monitoring_script.php');
?>