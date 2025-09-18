<style>
    @font-face {
        font-family: 'Poppins';
        src: url('dist/font/poppins/Poppins-Regular.ttf') format('truetype');
    }

    body {
        font-family: 'Poppins', sans-serif;
    }

    /* scrollbar */
    /* width */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    /* Track */
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }

    /* Handle */
    ::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }

    /* Handle on hover */
    ::-webkit-scrollbar-thumb:hover {
        background: #332D2D;
    }

    .highlight {
        border: 1px solid #CA3F3F;
    }

    .d-side-nav {
        position: fixed;
        top: 50px;
        bottom: 0;
        left: 0;
        width: 16.6667%;
        height: calc(100vh - 50px);
        overflow-y: auto;
        background: #1b263b;
        color: #FFF;
        padding: 10px;
        border-right: 1px solid #8d0801;
        z-index: 10;
        box-sizing: border-box;
    }

    @media screen and (max-width: 768px) {
        .d-side-nav {
            width: 100%;
            position: relative;
            height: auto;
            max-height: 400px;
            overflow-y: auto;
        }
    }

    .count-border {
        border: none;
        background-color: #fff;
        padding: 15px;
        border-radius: 6px;
        height: 100%;
    }

    .chart-border {
        border: none;
        background-color: #fff;
        padding: 8px;
        border-radius: 6px;
        height: 100%;
    }

    .navbar-nav .nav-item a.btn {
        transition: background-color 0.3s, color 0.3s;
    }

    .navbar-nav .nav-item a.btn:hover {
        background-color: #415a77 !important;
        color: #fff !important;
    }

    .dropdown-menu .dropdown-item:hover {
        background-color: #415a77 !important;
        color: #fff !important;
    }
</style>

<!-- Navbar -->
<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<nav class="main-header navbar sticky-top d-flex align-items-center justify-content-between"
    style="background:#1b263b; border-bottom: 3px solid #8d0801; z-index:1030;">

    <!-- Brand -->
    <a href="" class="navbar-brand d-flex align-items-center ml-2">
        <img src="dist/img/defect.png" alt="Minor Defect Record System Logo" class="brand-image mr-2">
        <span class="brand-text font-weight-normal text-light" style="font-size:20px;">
            MINOR DEFECT RECORD SYSTEM
        </span>
    </a>

    <!-- Nav Buttons -->
    <ul class="navbar-nav d-flex flex-row ml-auto align-items-center">
        <!-- Main -->
        <li class="nav-item mr-1">
            <a class="btn btn-sm <?php echo ($current_page == 'index.php') ? 'btn-light text-dark' : 'text-white'; ?>"
                href="index.php">
                <i class="fas fa-home"></i> Main
            </a>
        </li>

        <!-- Dashboard with Dropdown -->
        <li class="nav-item dropdown mr-1">
            <a class="btn btn-sm <?php echo ($current_page == 'main_dashboard.php' || $current_page == 'weekly_monitoring.php') ? 'btn-light text-dark' : 'text-white'; ?> dropdown-toggle"
                href="#" id="dashboardDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
            <div class="dropdown-menu" aria-labelledby="dashboardDropdown" style="position: absolute; z-index: 1050;">
                <a class="btn btn-sm dropdown-item <?php echo ($current_page == 'main_dashboard.php') ? 'active' : ''; ?>" href="main_dashboard.php">
                    <i class="fas fa-desktop mr-1"></i> Main Monitoring
                </a>
                <a class="btn btn-sm dropdown-item <?php echo ($current_page == 'weekly_monitoring.php') ? 'active' : ''; ?>" href="weekly_monitoring.php">
                    <i class="fas fa-calendar-week mr-1"></i> Weekly Monitoring
                </a>
            </div>
        </li>

        <!-- Work Instruction -->
        <li class="nav-item mr-1">
            <a class="btn btn-sm <?php echo ($current_page == 'Minor-Defect-Record-System_WI_rev.2.pdf') ? 'btn-light text-dark' : 'text-white'; ?>"

                href="template/Minor-Defect-Record-System_WI_rev.2.pdf" target="_blank">
                <i class="fas fa-file-alt"></i> Work Instruction
            </a>
        </li>

        <!-- Admin -->
        <li class="nav-item mr-1">
            <a class="btn btn-sm <?php echo ($current_page == 'index_m.php') ? 'btn-light text-dark' : 'text-white'; ?>"
                href="index_m.php">
                <i class="fas fa-user-cog"></i> Admin
            </a>
        </li>
    </ul>
</nav>
<!-- /.navbar -->