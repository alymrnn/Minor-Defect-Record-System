<?php
//SESSION
include '../process/login.php';

if (!isset($_SESSION['username'])) {
    header('location:../');
    exit;
} 

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Minor Defect Record System System</title>

    <link rel="icon" href="../dist/img/defect.png" type="image/x-icon" />
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="../dist/css/font.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/adminlte.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="../plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Sweet Alert -->
    <link rel="stylesheet" href="../plugins/sweetalert2/dist/sweetalert2.min.css">
    <style>
        @font-face {
            font-family: 'Poppins';
            src: url('../dist/font/poppins/Poppins-Regular.ttf') format('truetype');
        }

        body {
            font-family: 'Poppins', sans-serif;
        }

        .sidebar-light-primary .nav-sidebar>.nav-item>.nav-link.active {
            background-color: #DFDFDF;
            margin-bottom: 0px;
        }

        .spinner {
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid #536A6D;
            width: 50px;
            height: 50px;
            -webkit-animation: spin 2s linear infinite;
            animation: spin 2s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(1080deg);
            }
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

        /* return to top button */
        .return-to-top {
            position: fixed;
            right: 15px;
            bottom: 15px;
            /* border: 3px solid #332D2D; */
            border: none;
            background: none;
            border-radius: 15%;
        }

        .nav-icon-top {
            font-size: 38px;
            color: #636363;
            font-weight: 100;
        }

        .return-to-top:hover {
            /* border: 3px solid #3B71CA; */
            border: none;
            /* background: #3B71CA; */
        }

        .nav-icon-top:hover {
            color: #2b2b2b;
            opacity: 1.0;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini sidebar-collapse">
    <div class="wrapper">

        <!-- Preloader -->
        <!-- <div class="preloader flex-column justify-content-center align-items-center" style="background: #00375C;">
      <img class="animation__shake" src="../../dist/img/tool-box.png" alt="logo" height="100" width="100">
    </div> -->

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white" style="background: #343a40;">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button" style="color: #fff;"><i
                            class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button" style="color: #fff;">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

</html>