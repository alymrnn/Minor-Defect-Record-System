<?php include 'plugins/navbar.php'; ?>
<?php include 'plugins/sidebar/index_m_bar.php'; ?>

<div class="content-wrapper bg-white">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4 class="m-0">Masterlist</h4>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right text-sm">
                        <li class="breadcrumb-item"><a href="barcode_m.php">Minor Defect Record System</a></li>
                        <li class="breadcrumb-item active">Masterlist</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Main content -->
    <section class="content">
        <div class="col-md-12">
            <div class="card card-tabs bg-white">
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs text-sm" id="masterlist-record-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link" id="masterlist-record-1-tab" data-toggle="pill"
                                href="#masterlist-record-1" role="tab" aria-controls="masterlist-record-1"
                                aria-selected="true">QR Settings</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" id="masterlist-record-2-tab" data-toggle="pill"
                                href="#masterlist-record-2" role="tab" aria-controls="masterlist-record-2"
                                aria-selected="true">Defect Details</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="masterlist-record-3-tab" data-toggle="pill"
                                href="#masterlist-record-3" role="tab" aria-controls="masterlist-record-3"
                                aria-selected="true">Car Model</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="masterlist-record-4-tab" data-toggle="pill"
                                href="#masterlist-record-4" role="tab" aria-controls="masterlist-record-4"
                                aria-selected="true">Process</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="masterlist-record-5-tab" data-toggle="pill"
                                href="#masterlist-record-5" role="tab" aria-controls="masterlist-record-5"
                                aria-selected="true">Admin Account</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="masterlist-record-6-tab" data-toggle="pill"
                                href="#masterlist-record-6" role="tab" aria-controls="masterlist-record-5"
                                aria-selected="true">Authorized Account</a>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content" id="masterlist-record-tab-content">
                        <!-- QR SETTINGS -->
                        <div class="tab-pane fade" id="masterlist-record-1" role="tabpanel"
                            aria-labelledby="masterlist-record-1-tab">
                            <!-- Main Content -->
                            <div class="row">
                                <div class="col-12 col-sm-2">
                                    <button class="btn btn-primary btn-sm w-100"
                                        data-toggle="modal" data-target="#add_qr_setting">
                                        Add Model Settings
                                    </button>
                                </div>
                            </div>

                            <!-- table -->
                            <div class="card-body table-responsive m-0 p-0 mt-3" style="max-height: 500px;">
                                <table class="table table-head-fixed text-nowrap table-hover table-sm table-bordered"
                                    id="qr_setting_table">
                                    <thead class="text-center text-sm">
                                        <th style="width: 10%;">#</th>
                                        <th style="width: 10%;">Car Maker</th>
                                        <th style="width: 10%;">Car Model Setting</th>
                                        <th style="width: 10%;">Car Value</th>
                                        <th style="width: 10%;">Total Length</th>
                                        <th style="width: 10%;">Product Name Start</th>
                                        <th style="width: 10%;">Product Name Length</th>
                                        <th style="width: 10%;">Lot No. Start</th>
                                        <th style="width: 10%;">Lot No. Length</th>
                                        <th style="width: 10%;">Serial No. Start</th>
                                        <th style="width: 10%;">Serial No. Length</th>
                                    </thead>
                                    <tbody class="mb-0 text-xs" id="list_of_qr_setting">
                                        <tr>
                                            <td colspan="11">
                                                <div class="spinner-border text-dark text-center" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Defect Details -->
                        <div class="tab-pane fade show active" id="masterlist-record-2" role="tabpanel"
                            aria-labelledby="masterlist-record-2-tab">
                            <div class="row">
                                <div class="col-12 col-sm-2">
                                    <button class="btn btn-primary btn-sm w-100"
                                        data-toggle="modal" data-target="#add_defect_details">
                                        Add Defect Details
                                    </button>
                                </div>
                            </div>

                            <!-- table -->
                            <div class="card-body table-responsive m-0 p-0 mt-3" style="max-height: 500px;">
                                <table class="table table-head-fixed text-nowrap table-hover table-sm table-bordered" id="defect_details_table">
                                    <thead class="text-sm text-center">
                                        <th>#</th>
                                        <th>Action</th>
                                        <th>Defect Code</th>
                                        <th>Defect Category</th>
                                        <th>Defect Sub Code</th>
                                        <th>Defect Details</th>
                                        <th>Defect Treatment</th>
                                    </thead>
                                    <tbody class="mb-0 text-xs" id="list_of_defect_details">
                                        <tr>
                                            <td colspan="7">
                                                <div class="spinner-border text-dark text-center" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Account Management -->
                        <div class="tab-pane fade" id="masterlist-record-5" role="tabpanel"
                            aria-labelledby="masterlist-record-5-tab">
                            <div class="row">
                                <div class="col-12 col-sm-2">
                                    <button class="btn btn-primary btn-sm w-100"
                                        data-toggle="modal" data-target="#add_account">
                                        Add Account
                                    </button>
                                </div>
                            </div>

                            <!-- table -->
                            <div class="card-body table-responsive m-0 p-0 mt-3" style="max-height: 500px;">
                                <table class="table col-12 table-head-fixed text-nowrap table-hover table-sm table-bordered" id="accounts_table">
                                    <thead class="text-center text-sm">
                                        <th class="text-right">#</th>
                                        <th>Action</th>
                                        <th>Username</th>
                                        <th>Role</th>
                                    </thead>
                                    <tbody class="mb-0 text-xs" id="list_of_accounts">
                                        <tr>
                                            <td colspan="4">
                                                <div class="spinner-border text-dark text-center" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Line / Process -->
                        <div class="tab-pane fade" id="masterlist-record-4" role="tabpanel"
                            aria-labelledby="masterlist-record-4-tab">
                            <div class="row">
                                <div class="col-12 col-sm-2">
                                    <button class="btn btn-primary btn-sm w-100"
                                        data-toggle="modal" data-target="#add_line_process">
                                        Add Process
                                    </button>
                                </div>
                            </div>

                            <!-- table -->
                            <div class="card-body table-responsive m-0 p-0 mt-3" style="max-height: 500px;">
                                <table class="table col-12 table-head-fixed text-nowrap table-hover table-sm table-bordered" id="line_process_table">
                                    <thead class="text-center text-sm">
                                        <th class="text-right">#</th>
                                        <th>Action</th>
                                        <th>Line</th>
                                        <th>Process</th>
                                    </thead>
                                    <tbody class="mb-0 text-xs" id="list_of_line_process">
                                        <tr>
                                            <td colspan="4">
                                                <div class="spinner-border text-dark text-center" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Line / Car Model -->
                        <div class="tab-pane fade" id="masterlist-record-3" role="tabpanel"
                            aria-labelledby="masterlist-record-3-tab">
                            <div class="row">
                                <div class="col-12 col-sm-2">
                                    <button class="btn btn-primary btn-sm w-100"
                                        data-toggle="modal" data-target="#add_line_car_model">
                                        Add Car Model
                                    </button>
                                </div>
                            </div>

                            <!-- table -->
                            <div class="card-body table-responsive m-0 p-0 mt-3" style="max-height: 500px;">
                                <table class="table col-12 table-head-fixed text-nowrap table-hover table-sm table-bordered" id="line_car_model_table">
                                    <thead class="text-center text-sm">
                                        <th class="text-right">#</th>
                                        <th>Action</th>
                                        <th>Line</th>
                                        <th>Section</th>
                                        <th>Car Maker</th>
                                        <th>Car Model</th>
                                        <th>Harness Type</th>
                                    </thead>
                                    <tbody class="mb-0 text-xs" id="list_of_line_car_model">
                                        <tr>
                                            <td colspan="6">
                                                <div class="spinner-border text-dark text-center" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- authorized accounts -->
                        <div class="tab-pane fade" id="masterlist-record-6" role="tabpanel"
                            aria-labelledby="masterlist-record-6-tab">
                            <div class="row">
                                <div class="col-12 col-sm-2">
                                    <button class="btn btn-primary btn-sm w-100"
                                        data-toggle="modal" data-target="#add_auth_account">
                                        Add Authorized Account
                                    </button>
                                </div>
                            </div>

                            <!-- table -->
                            <div class="card-body table-responsive m-0 p-0 mt-3" style="max-height: 500px;">
                                <table class="table col-12 table-head-fixed text-nowrap table-hover table-sm table-bordered" id="auth_account_table">
                                    <thead class="text-center text-sm">
                                        <th class="text-right">#</th>
                                        <th>Action</th>
                                        <th>Employee ID</th>
                                        <th>Name</th>
                                        <th>Department</th>
                                    </thead>
                                    <tbody class="mb-0 text-xs" id="list_of_auth_account">
                                        <tr>
                                            <td colspan="5">
                                                <div class="spinner-border text-dark text-center" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include 'plugins/footer.php'; ?>
<?php include 'plugins/js/index_m_script.php'; ?>