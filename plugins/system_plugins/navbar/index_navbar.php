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

    .chart-border {
        border: none;
        background-color: #fff;
        padding: 8px;
        border-radius: 8px;
        height: 100%;
    }
</style>

<!-- Navbar -->
<!-- <nav class="main-header navbar navbar-expand-md border-bottom-0" style="background:#163A65;"> -->
<nav class="main-header navbar" style="background:#1b263b; border-bottom: 3px solid #8d0801;">
    <a href="" class="navbar-brand ml-2">
        <img src="dist/img/defect.png" alt="Minor Defect Record System Logo" class="brand-image">
        <span class="brand-text font-weight-normal text-light" style="color: white; font-size: 20px;">MINOR DEFECT RECORD SYSTEM</span>
    </a>

    <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
        <li class="nav-item mr-4 pt-3">
            <p style="color: #fff; font-size: 14px;"><i class="fas fa-calendar-check"></i>&nbsp;&nbsp;
                <span id="datetime"></span> |
                <a style="color: #EED965;" href="template/Minor-Defect-Record-System_WI_rev.2.pdf" target="_blank">Work Instruction</a>
            </p>
        </li>
    </ul>
</nav>
<!-- /.navbar -->

<script>
    function refreshDateTime() {
        const datetimeDisplay = document.getElementById("datetime");
        const now = new Date();

        const dateOptions = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        const formattedDate = now.toLocaleDateString(undefined, dateOptions);

        const timeOptions = {
            hour: 'numeric',
            minute: 'numeric',
            second: 'numeric'
        };
        const formattedTime = now.toLocaleTimeString(undefined, timeOptions);

        const formattedDateTime = `${formattedDate} | ${formattedTime}`;

        datetimeDisplay.textContent = formattedDateTime;
    }
    setInterval(refreshDateTime, 1000);
</script>