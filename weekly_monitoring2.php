<!-- <?php
include('process/conn.php');
include('plugins/system_plugins/header.php');
include('plugins/system_plugins/preloader.php');
include('plugins/system_plugins/navbar/index_navbar.php');
?>

<div class="content-wrapper" style="background: #f8f9fa;">
   <section class="content">
      <div class="d-side-nav collapse" id="sidebarOverall">
         <div class="row">
            <div class="col-12">
               <div class="mt-2" style="display: flex; justify-content: flex-end; align-items: center;">
                  <p class="m-0 p-0 btn btn-primary btn-sm"
                     style="font-size: 12px; transition: 0.3s; cursor: pointer;"
                     onclick="generate_weekly_dashboard_filter()">
                     &nbsp;Generate&nbsp;
                  </p>
               </div>

               <p class="m-0 p-0 text-left text-dark text-xs">Year</p>
               <select id="d_year" class="form-control form-control-sm form-control-border mb-1"></select>

               <p class="m-0 p-0 text-left text-dark text-xs">Month</p>
               <select id="d_month" class="form-control form-control-sm form-control-border mb-1"></select>
            </div>
         </div>
      </div>

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

         <div class="col-12">
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
?> -->