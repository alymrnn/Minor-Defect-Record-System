<?php
include('process/conn.php');
include('plugins/system_plugins/header.php');
include('plugins/system_plugins/preloader.php');
include('plugins/system_plugins/navbar/index_navbar.php');
?>

<div class="content-wrapper" style="background: #1b263b;">
   <!-- Main content -->
   <section class="content">
      <div class="row">
         <div class="col-2 d-side-nav pt-3">
            <div class="row">
               <div class="col-12">
                  <!-- commands -->
                  <div class="mt-2" style="display: flex; justify-content: flex-end; align-items: center;">
                     <p class="m-0 p-0 btn btn-warning btn-sm"
                        style="font-size: 12px; transition: 0.3s; cursor: pointer;"
                        onclick="generate_weekly_dashboard_filter()">
                        Generate
                     </p>
                  </div>

                  <p class="m-0 p-0 text-left" style="font-size: 12px;">Year</p>
                  <select id="d_year" class="form-control form-control-sm form-control-border mb-1"></select>

                  <p class="m-0 p-0 text-left" style="font-size: 12px;">Month</p>
                  <select id="d_month" class="form-control form-control-sm form-control-border mb-1"></select>
               </div>
            </div>
         </div>

         <!-- main content -->
         <div class="col-10 offset-2">
            <p class="pt-3 m-0 p-0 text-white" style="font-size: 14px;">Weekly Trend Monitoring</p>
            <hr class="m-0 p-0" style="background: #8d0801;">

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
                     <div id="weekly_top_lines_based_on_defect_category_chart" style="border-radius: 8px;">
                        <blockquote class="blockquote quote-secondary text-left m-2">
                           <p id="weekly_top_lines_chart_placeholder" class="mb-0" style="font-size: 12px; color: #6c757d;">
                              Select from Weekly Record per Defect Category to generate Top 10 Lines of Selected Defect Category per Week
                           </p>
                        </blockquote>
                     </div>
                  </div>
               </div>
            </div>

            <div class="row mt-2">
               <div class="col-12">
                  <div class="chart-border">
                     <div id="weekly_defect_per_section_chart" style="border-radius: 8px;"></div>
                  </div>
               </div>
            </div>



         </div>
      </div>
   </section>
</div>












<?php
include('plugins/system_plugins/footer_dashboard.php');
include('plugins/system_plugins/js/weekly_monitoring_script.php');
?>