<footer class="main-footer" style="background:#2b2d42; color:white; font-size: 14px;">
    Copyright &copy; 2024. Aly Maranan. All rights reserved. <a href="index_m.php">ADMIN</a>
    <div class="float-right d-none d-sm-inline-block">
        Version 1.0.0 | <i style="font-size:12px;">IP Address: &nbsp;</i><input
            style="font-size:12px;border:none;background:none;color:#FFF;text-align:right;width:80px;"
            value="<?= $_SERVER['REMOTE_ADDR']; ?>" disabled>
    </div>
</footer>
<?php
//MODALS
include 'modals/add_defect_record.php';
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

</body>

</html>