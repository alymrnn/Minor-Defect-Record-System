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
        background-image: url('dist/img/splat-bg.svg');
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
    <div class="login-box">
        <div class="container d-flex justify-content-center align-items-center min-vh-100">
            <div class="card shadow-sm" style="border-radius: 15px;background: #F4F4F4; max-width: 100%; width: 400px;">
                <div class="card-body login-card-body" style="border-radius: 15px; background:#eaeaea">
                    <div class="login-logo mt-3 text-center">
                        <img class="pb-1" src="dist/img/defect.png" style="height: 120px;" alt="Minor Defect">
                        <h2 class="pb-2 text-bold" style="color: #313131; font-size: 25px;">MINOR DEFECT<br>RECORD
                            SYSTEM
                        </h2>
                    </div>

                    <form class="px-3" method="POST" id="login_form">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="username" name="username" placeholder="Username"
                                autocomplete="off" required
                                style="font-size: 14px; border-radius: 3px; border: 1px solid #888888; background: #eaeaea;"
                                onfocus="changeBorderColor(this, '#792021')"
                                oninput="changeBorderColor(this, '#792021')"
                                onblur="changeBorderColor(this, '#9e2a2b')">
                        </div>
                        <div class="input-group mb-3">
                            <button type="submit" class="login-btn btn btn-block" name="login_btn" value="login"
                                style="border-radius: 3px; background: #9e2a2b; color: #FFF;"
                                onmouseover="this.style.backgroundColor='#792021'; this.style.color='#FFF';"
                                onmouseout="this.style.backgroundColor='#9e2a2b'; this.style.color='#FFF';">SIGN IN
                            </button>
                        </div>
                        <div class="d-flex justify-content-center">
                            <a href="template/Minor-Defect-Record-System_WI.pdf" target="_blank"><button type="button"
                                    class="btn btn-block btn-sm"
                                    style="border-radius: 3px; background: #3E3E3E; width: 190px; color: #FFF;"
                                    onmouseover="this.style.backgroundColor='#242424'; this.style.color='#FFF';"
                                    onmouseout="this.style.backgroundColor='#3E3E3E'; this.style.color='#FFF';">Work
                                    Instruction</button></a>
                        </div>
                        <a href="index.php" class="nav-link mt-2 d-flex justify-content-center"
                            style="font-size: 15px;">Main Page</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

<script>
    function changeBorderColor(element, color) {
        element.style.borderColor = color;
    }
</script>

<!-- jQuery -->
<script src="plugins/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

</html>