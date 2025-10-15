<footer class="main-footer bg-white text-xs">
    <div class="row">
        <div class="col-10 offset-2 d-flex justify-content-between">
            <span>
                Copyright © 2024. All Rights Reserved. Developed by: <i>Ally Maranan.</i>
            </span>

            <span>
                Version 2.1.0 |
                <i style="font-size:12px;">IP Address:</i>
                <input style="font-size:12px; border:none; background:none; text-align:right; width:80px;"
                    value="<?= $_SERVER['REMOTE_ADDR']; ?>" disabled>
            </span>
        </div>
    </div>
</footer>

<?php
//MODALS
include 'modals/add_defect_record.php';
include 'modals/add_record_auth.php';
?>

<!-- jQuery -->
<script src="plugins/jquery/dist/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- SweetAlert2 -->
<script type="text/javascript" src="plugins/sweetalert2/dist/sweetalert2.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- overlayScrollbars -->
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.js"></script>

<!-- Highcharts -->
<script src="plugins/highcharts/js/accessibility.js"></script>
<script src="plugins/highcharts/js/export-data.js"></script>
<script src="plugins/highcharts/js/exporting.js"></script>
<script src="plugins/highcharts/js/highcharts-more.js"></script>
<script src="plugins/highcharts/js/highcharts.js"></script>
<script src="plugins/highcharts/js/map.js"></script>

</body>

</html>