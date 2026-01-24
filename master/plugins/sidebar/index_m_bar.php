<aside class="main-sidebar sidebar-light-primary"
    style="position: fixed; top: 0; bottom: 0; overflow-y: auto; border-right: 1px solid #ddd;">
    <a href="masterlist_m.php" class="brand-link">
        <img src="../dist/img/defect.png" alt="Logo" class="brand-image" style="opacity: .8;">
        <span class="brand-text text-sm text-dark">Minor Defect System</span>
    </a>

    <div class="sidebar">
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="../dist/img/user.png" class="img-circle" alt="User Image">
            </div>
            <div class="info">
                <a href="masterlist_m.php" class="d-block text-xs text-primary"><?= htmlspecialchars($_SESSION['username']); ?></a>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    <p class="nav-link text-sm">AD</p>
                </li>
                <li class="nav-item mb-1">
                    <a href="masterlist_m.php" class="nav-link active">
                        <i class="fas fa-file-alt text-dark"></i>
                        <p class="pl-1 text-xs text-dark">
                            Masterlist
                        </p>
                    </a>
                </li>
                <li class="nav-item mb-1">
                    <a href="minor_record_m.php" class="nav-link">
                        <i class="fas fa-file-alt text-dark"></i>
                        <p class="pl-1 text-xs text-dark">
                            Minor Record List
                        </p>
                    </a>
                </li>
                <?php include 'logout.php'; ?>
            </ul>
        </nav>
    </div>
</aside>