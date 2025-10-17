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

    #stickyHeader {
        position: sticky;
        top: 50px;
        background: #f8f9fa;
        z-index: 10;
        padding: 7px;
        transition: all 0.3s ease;
    }

    .d-side-nav {
        position: fixed;
        top: 50px;
        bottom: 0;
        left: 0;
        width: 16.6667%;
        height: calc(100vh - 50px);
        overflow-y: auto;
        background: #f8f9fa;
        padding: 10px;
        border-right: 1px solid #ddd;
        z-index: 10;
        transition: width 0.3s ease, opacity 0.3s ease;
    }

    .d-side-nav.collapsing {
        width: 0 !important;
        opacity: 0;
        overflow: hidden;
        transition: width 0.3s ease, opacity 0.3s ease;
    }

    .d-side-nav.collapse {
        display: block;
    }

    .d-side-nav.collapse:not(.show) {
        width: 0 !important;
        opacity: 0;
        overflow: hidden;
    }

    #mainContent {
        margin-left: 16.6667%;
        transition: margin-left 0.3s ease;
        overflow: visible !important;
    }

    #sidebar:not(.show)~#mainContent {
        margin-left: 0 !important;
    }

    #mainFooter {
        margin-left: 16.6667%;
        transition: margin-left 0.3s ease;
    }

    #sidebar:not(.show)~#mainFooter {
        margin-left: 0 !important;
    }

    #stickyHeaderOverall {
        position: sticky;
        top: 50px;
        background: #f8f9fa;
        z-index: 10;
        padding: 7px;
        transition: all 0.3s ease;
    }

    #mainContentOverall {
        margin-left: 16.6667%;
        transition: margin-left 0.3s ease;
        overflow: visible !important;
    }

    #sidebarOverall:not(.show)~#mainContentOverall {
        margin-left: 0 !important;
    }

    #mainFooterOverall {
        margin-left: 16.6667%;
        transition: margin-left 0.3s ease;
    }

    #sidebarOverall:not(.show)~#mainFooterOverall {
        margin-left: 0 !important;
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
        border-radius: 15px;
        border: 1px solid #eee;
        height: 100%;
    }

    .chart-border {
        border: none;
        background-color: #fff;
        padding: 8px;
        border-radius: 15px;
        border: 1px solid #eee;
        height: 100%;
    }
    .filter-border {
        border: none;
        background-color: #fff;
        padding: 15px;
        border-radius: 15px;
        border: 1px solid #ddd;
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

    /* overall monitoring */
    /* Hide the actual checkbox input */
    .btn-check {
        position: absolute;
        clip: rect(0, 0, 0, 0);
        pointer-events: none;
    }

    /* Style when checked */
    .btn-check:checked+.btn-outline-secondary {
        background-color: #97dffc;
        color: #020887;
        border-color: #97dffc;
    }

    /* Hover effect */
    .btn-outline-secondary:hover {
        background-color: #97dffc;
        color: #020887;
        border-color: #97dffc;
    }
    
</style>

<!-- Navbar -->
<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<nav class="main-header navbar sticky-top d-flex align-items-center justify-content-between"
    style="background:#00375C;">

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
                    <i class="fas fa-desktop mr-1"></i> Daily Monitoring
                </a>
                <a class="btn btn-sm dropdown-item <?php echo ($current_page == 'weekly_monitoring.php') ? 'active' : ''; ?>" href="weekly_monitoring.php">
                    <i class="fas fa-calendar-week mr-1"></i> Overall Monitoring
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

<body>