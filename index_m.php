<?php require 'process/login.php';

if (isset($_SESSION['username'])) {
    if ($_SESSION['role'] == 'ADMIN') {
        header('location: master/masterlist_m.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Minor Defect Record System</title>

    <link rel="icon" href="dist/img/defect.png" type="image/x-icon" />
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="dist/css/font.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
    <!-- Sweet Alert -->
    <link rel="stylesheet" href="plugins/sweetalert2/dist/sweetalert2.min.css">
</head>

<style type="text/css">
    .login-page {
        width: 100%;
        background-image: url('dist/img/furukawa-bg.jpg');
        background-size: cover;
    }

    @font-face {
        font-family: 'Poppins';
        src: url('dist/font/poppins/Poppins-Regular.ttf') format('truetype');
    }

    body {
        font-family: 'Poppins', sans-serif;
    }

    @media (max-width: 576px) {
        .card {
            width: 90%;
            margin: 10px;
        }

        .login-card-body {
            padding: 20px;
        }

        .login-logo img {
            height: 100px;
        }

        .login-logo h2 {
            font-size: 20px;
        }

        .login-box-msg {
            font-size: 14px;
        }
    }
</style>

<body class="hold-transition login-page">
    <div class="wrapper login-page">
        <div class="row">
            <!-- Left Side -->
            <div class="col-md-6 position-relative d-flex align-items-center justify-content-center p-4"
                style="background-color: rgba(0, 45, 77, 0.30); border-radius: 12px 0px 0px 12px;">

                <!-- Top-left FAS logo -->
                <img src="dist/img/fas-name.png"
                    alt="FAS Logo"
                    class="position-absolute"
                    style="top: 20px; left: 20px; height: 37px;">

                <!-- Centered logo + text layout -->
                <div class="d-flex align-items-center flex-wrap justify-content-center w-100">
                    <img src="dist/img/defect.png"
                        class="mt-3 mb-2"
                        style="height: 90px; max-width: 100%;">

                    <div class="ml-4 d-flex align-items-center justify-content-start" style="height: 100%;">
                        <h2 class="text-white font-weight-bold m-0" style="text-align: left;">
                            MINOR <br> DEFECT <br> RECORD <br> SYSTEM
                        </h2>
                    </div>
                </div>
            </div>

            <!-- Right Side (Login Form) -->
            <div class="col-6" style="background-color: #FEFEFE; border-radius: 0px 12px 12px 0px; width: 450px;">
                <p class="text-center p-0 m-0 pt-5" style="font-size: 20px; font-weight: bold;">Login to your account</p>
                <p class="text-center p-0 m-0" style="font-size: 13px; color: #444;">
                    Health and Safety First!
                </p>

                <form class="form-horizontal mt-5 mr-4 ml-4" method="POST" id="login_form">
                    <div class="input-group mb-3">
                        <label class="m-0 p-0" style="font-weight: 100; font-size: 13px; color: #444;">User ID</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Enter your credential"
                            autocomplete="off" required
                            style="width: 100%; height: 40px; font-size: 13px; border-radius: 10px; border-color: #E9E9E9; transition: 0.4s ease;"
                            onfocus="this.style.borderColor='#0F78DC';"
                            onblur="this.style.borderColor='#E9E9E9';"
                            onmouseover="this.style.borderColor='#0F78DC';"
                            onmouseout="this.style.borderColor='#E9E9E9';"
                            autofocus>
                    </div>

                    <div class="input-group mb-3">
                        <button type="submit" class="btn btn-block" name="login_btn" value="login"
                            style="background-color: #0F78DC; color: #fff; font-size: 14px; border-radius: 10px; height: 40px; transition: 0.3s ease;"
                            onmouseover="this.style.backgroundColor='#00497A'; this.style.color='#fff';"
                            onmouseout="this.style.backgroundColor='#0F78DC'; this.style.color='#fff';">Login</button>
                    </div>
                </form>

                <div class="mr-4 ml-4">
                    <div style="display: flex; align-items: center;">
                        <hr style="flex: 1; border: none; border-top: 1px solid #E0E0E0;">
                        <p class="text-center" style="font-size: 12px; color: #BCBCBC; margin: 0 10px;">Or proceed to</p>
                        <hr style="flex: 1; border: none; border-top: 1px solid #E0E0E0;">
                    </div>

                    <div class="row mt-3 mb-5">
                        <div class="col-6 d-flex justify-content-center">
                            <button class="btn btn-block"
                                style="color: #353535; border-radius: 10px; border: 1px solid #E9E9E9; height: 40px; font-size: 13px; background-color: #fff; transition: 0.3s ease;"
                                onclick="window.open('template/Minor-Defect-Record-System_WI_rev.2.pdf', '_blank');"
                                onmouseover="this.style.backgroundColor='#00497A'; this.style.color='#fff';"
                                onmouseout="this.style.backgroundColor='#fff'; this.style.color='#353535';">
                                <i class="far fa-file"></i>&nbsp;Work Instruction
                            </button>
                        </div>

                        <div class="col-6 d-flex justify-content-center">
                            <button class="btn btn-block"
                                style="color: #353535; border-radius: 10px; border: 1px solid #E9E9E9; height: 40px; font-size: 13px; background-color: #fff; transition: 0.3s ease;"
                                onmouseover="this.style.backgroundColor='#00497A'; this.style.color='#fff';"
                                onmouseout="this.style.backgroundColor='#fff'; this.style.color='#353535';"
                                onclick="window.location.href='index.php';">
                                <i class="far fa-eye"></i>&nbsp;Main Page
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function changeBorderColor(element, color) {
            element.style.borderColor = color;
        }
    </script>
</body>

<!-- jQuery -->
<script src="plugins/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

</html>