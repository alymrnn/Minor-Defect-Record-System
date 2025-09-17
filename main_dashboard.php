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
                        onclick="generate_dashboard_filter()">
                        Generate
                     </p>
                  </div>

                  <!-- line no -->
                  <p class="m-0 p-0 text-left d-none" style="font-size: 12px;">Line No.</p>
                  <select name="d_pd_type" id="d_line_no" autocomplete="off" class="pl-2 form-control form-control-sm form-control-border mb-1 d-none" style="font-size: 13px;" required>
                     <option value="" disabled selected>Line No.</option>
                  </select>

                  <!-- date range -->
                  <p class="m-0 p-0 text-left" style="font-size: 12px;">Date From</p>
                  <input type="text" name="date_from" class="form-control form-control-sm form-control-border mb-1" id="d_date_from" placeholder="Date From"
                     onfocus="this.type='date';" onblur="if(this.value==''){this.type='text';}">
                  <p class="m-0 p-0 text-left" style="font-size: 12px;">Date To</p>
                  <input type="text" name="date_to" class="form-control form-control-sm form-control-border mb-1" id="d_date_to" placeholder="Date To"
                     onfocus="this.type='date';" onblur="if(this.value==''){this.type='text';}">
               </div>
            </div>
         </div>

         <!-- main content -->
         <div class="col-10 offset-2">
            <div class="row mt-3">
               <div class="col-2">
                  <div class="count-border">
                     <p class="m-0 p-0" style="font-size: 14px;">Defect Record</p>
                     <h2 id="total_record_count" class="text-bold"></h2>
                     <hr>
                     <p for="prev-comparison-label" class="mt-3 m-0 p-0" style="font-size: 14px;">Previous Month Record</p>
                     <h2 id="total_comparison_count" class="text-bold"></h2>
                  </div>
               </div>
               <div class="col-10">
                  <div class="chart-border">
                     <div id="top_overall_defect_category" style="border-radius: 8px;"></div>
                  </div>
               </div>
            </div>

            <div class="row mt-3">
               <div class="col-6">
                  <div class="chart-border">
                     <div id="top_overall_line_no_chart" style="border-radius: 8px;">
                        <blockquote class="blockquote quote-secondary text-left m-2">
                           <p id="overall_line_no_chart_placeholder" class="mb-0" style="font-size: 12px; color: #6c757d;">
                              Select from Top Defect Category Chart to generate Top Line No.'s with Selected Defect
                           </p>
                        </blockquote>
                     </div>
                  </div>
               </div>
               <div class="col-6">
                  <div class="chart-border">
                     <div id="top_overall_defect_details_chart" style="border-radius: 8px;">
                        <blockquote class="blockquote quote-secondary text-left m-2">
                           <p id="overall_defect_details_chart_placeholder" class="mb-0" style="font-size: 12px; color: #6c757d;">
                              Select from Top Defect Category Chart to generate Defect Details
                           </p>
                        </blockquote>
                     </div>
                  </div>
               </div>
            </div>

            <div class="row mt-3">
               <div class="col-4">
                  <div class="chart-border">
                     <div id="top_lot_no_chart" style="border-radius: 8px;"></div>
                  </div>
               </div>
               <div class="col-4">
                  <div class="chart-border">
                     <div id="top_sequence_no_chart" style="border-radius: 8px;"></div>
                  </div>
               </div>
               <div class="col-4">
                  <div class="chart-border">
                     <div id="top_connector_no_chart" style="border-radius: 8px;"></div>
                  </div>
               </div>
            </div>

            <p class="mt-4 m-0 p-0 text-white" style="font-size: 14px;">Section Monitoring</p>
            <hr class="m-0 p-0" style="background: #8d0801;">
            <div class="row mt-2">
               <div class="col-12">
                  <div class="chart-border">
                     <div id="top_defect_category_per_section_chart" style="border-radius: 8px;"></div>
                  </div>
               </div>
            </div>
            <div class="row mt-3">
               <div class="col-8">
                  <div class="chart-border">
                     <div id="defect_record_per_section_chart" style="border-radius: 8px;"></div>
                  </div>
               </div>
               <div class="col-4">
                  <div class="chart-border">
                     <div id="top_line_no_section_based_chart" style="border-radius: 8px;">
                        <blockquote class="blockquote quote-secondary text-left m-2">
                           <p id="line_no_section_based_chart_placeholder" class="mb-0" style="font-size: 12px; color: #6c757d;">
                              Select a section from Defect Records per Section Chart to generate its Top Line No.
                           </p>
                        </blockquote>
                     </div>
                  </div>
               </div>
            </div>

            <p class="mt-4 m-0 p-0 text-white" style="font-size: 14px;">Daily Trend Monitoring</p>
            <hr class="m-0 p-0" style="background: #8d0801;">
            <div class="row mt-2">
               <div class="col-12">
                  <div class="chart-border">
                     <div id="daily_trend_chart" style="border-radius: 8px;"></div>
                  </div>
               </div>
            </div>
            <div class="row mt-3 mb-3">
               <div class="col-7">
                  <div class="chart-border">
                     <div id="top_daily_defect_category_chart" style="border-radius: 8px;">
                        <blockquote class="blockquote quote-secondary text-left m-2">
                           <p id="defect_category_chart_placeholder" class="mb-0" style="font-size: 12px; color: #6c757d;">
                              Select a date from Daily Defect Trend Chart to generate Top Defect Category
                           </p>
                        </blockquote>
                     </div>
                  </div>
               </div>
               <div class="col-5">
                  <div class="chart-border">
                     <div id="top_daily_line_no_chart" style="border-radius: 8px;">
                        <blockquote class="blockquote quote-secondary text-left m-2">
                           <p id="line_no_chart_placeholder" class="mb-0" style="font-size: 12px; color: #6c757d;">
                              Select a date from Daily Defect Trend Chart to generate Top Line No.
                           </p>
                        </blockquote>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </section>
</div>

<?php
include('plugins/system_plugins/footer_dashboard.php');
include('plugins/system_plugins/js/main_dashboard_script.php');
?>