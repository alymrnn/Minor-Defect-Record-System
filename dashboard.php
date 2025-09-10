<?php
include('process/conn.php');
include('plugins/system_plugins/header.php');
include('plugins/system_plugins/preloader.php');
include('plugins/system_plugins/navbar/index_navbar.php');
?>

<div class="content-wrapper text-white" style="background: #1b263b;">
   <!-- Main content -->
   <section class="content">
      <div class="row">
         <div class="col-2 d-side-nav pt-4">
            <div class="row">
               <div class="col-12">
                  <!-- commands -->
                  <div class="mt-2" style="display: flex; justify-content: space-between; align-items: center;">
                     <p class="m-0 p-0 btn" style="font-size: 12px; transition: 0.3s; cursor: pointer; color: #FFF;"
                        onclick="clear_dashboard_filter()" onmouseover="this.style.color='red'"
                        onmouseout="this.style.color='#FFF'">
                        Clear Display
                     </p>
                     <p class="m-0 p-0 btn btn-warning btn-sm" style="font-size: 12px; transition: 0.3s; cursor: pointer;"
                        onclick="generate_dashboard_filter()">
                        Generate
                     </p>
                  </div>

                  <!-- line no -->
                  <p class="m-0 p-0 text-left" style="font-size: 12px;">Line No.</p>
                  <select name="d_pd_type" id="d_line_no" autocomplete="off" class="pl-2 form-control form-control-sm form-control-border mb-1" style="font-size: 13px;" required>
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
            charts

         </div>



      </div>
   </section>
</div>


</section>
</div>

<?php
include('plugins/system_plugins/footer_dashboard.php');
include('plugins/system_plugins/js/dashboard_script.php');
?>