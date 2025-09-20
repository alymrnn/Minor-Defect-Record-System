<aside class="main-sidebar sidebar-light-primary"
    style="position: fixed; top: 0; bottom: 0; overflow-y: auto; border-right: 1px solid #8d0801;">
    <!-- Brand Logo -->
    <a href="masterlist_m.php" class="brand-link">
        <img src="../dist/img/defect.png" alt="Logo" class="brand-image" style="opacity: .8;">
        <span class="brand-text" style="font-size:15px;color:black">Minor Defect System</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="../dist/img/user.png" class="img-circle" alt="User Image">
            </div>
            <div class="info">
                <a href="masterlist_m.php" class="d-block text-sm text-primary"><?= htmlspecialchars($_SESSION['username']); ?></a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <p class="nav-link">AD</p>
                </li>
                <li class="nav-item mb-1">
                    <a href="masterlist_m.php" class="nav-link active">
                        <i class="fas fa-file-alt" style="color: #000"></i>
                        <p class="pl-1" style="font-size:14px; color:black">
                            Masterlist
                        </p>
                    </a>
                </li>
                <?php include 'logout.php'; ?>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>