<?php include 'plugins/navbar.php'; ?>
<?php include 'plugins/sidebar/index_m_bar.php'; ?>

<div class="content-wrapper" style="background: #FFF;">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Masterlist</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
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
            <div class="card card-tabs">
                <div class="card-header p-0 border-bottom-0">
                    <ul class="nav nav-tabs" id="masterlist-record-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="masterlist-record-1-tab" data-toggle="pill"
                                href="#masterlist-record-1" role="tab" aria-controls="masterlist-record-1"
                                aria-selected="true">QR Settings</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="masterlist-record-2-tab" data-toggle="pill"
                                href="#masterlist-record-2" role="tab" aria-controls="masterlist-record-2"
                                aria-selected="true">Defect Category, Details, and Treatment Content</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="masterlist-record-3-tab" data-toggle="pill"
                                href="#masterlist-record-3" role="tab" aria-controls="masterlist-record-3"
                                aria-selected="true">Account Management</a>
                        </li>
                    </ul>
                </div>

                <div class="card-body">
                    <div class="tab-content" id="masterlist-record-tab-content">
                        <!-- QR SETTINGS -->
                        <div class="tab-pane fade show active" id="masterlist-record-1" role="tabpanel"
                            aria-labelledby="masterlist-record-1-tab">
                            <!-- Main Content -->
                            <div class="row mb-3">
                                <div class="col-12 col-sm-2">
                                    <button class="btn btn-block btn-dark d-flex justify-content-left"
                                        data-toggle="modal" data-target="#add_qr_setting"
                                        style="color:#fff;height:34px;border-radius:.25rem;font-size:15px;font-weight:normal;">Add
                                        Model Settings</button>
                                </div>
                            </div>

                            <!-- table -->
                            <div id="" class="card-body table-responsive m-0 p-0" style="max-height: 500px;">
                                <table class="table col-12 mt-3 table-head-fixed text-nowrap table-hover"
                                    id="qr_setting_table" style="background: #F9F9F9;">
                                    <thead style="text-align: center;">
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
                                    <tbody class="mb-0" id="list_of_qr_setting">
                                        <tr>
                                            <td colspan="10" style="text-align: center;">
                                                <div class="spinner-border text-dark" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Defect Details -->
                        <div class="tab-pane fade" id="masterlist-record-2" role="tabpanel"
                            aria-labelledby="masterlist-record-2-tab">
                            <div class="row mb-3">
                                <div class="col-12 col-sm-2">
                                    <button class="btn btn-block btn-dark d-flex justify-content-left"
                                        data-toggle="modal" data-target="#add_defect_details"
                                        style="color:#fff;height:34px;border-radius:.25rem;font-size:15px;font-weight:normal;">Add
                                        Defect Details</button>
                                </div>
                            </div>

                            <!-- table -->
                            <div id="" class="card-body table-responsive m-0 p-0" style="max-height: 500px;">
                                <table class="table col-12 mt-3 table-head-fixed text-nowrap table-hover"
                                    id="defect_details_table" style="background: #F9F9F9;">
                                    <thead style="text-align: center;">
                                        <th style="text-align:right">#</th>
                                        <th style="text-align:left">Action</th>
                                        <th style="text-align:center">Defect Code</th>
                                        <th style="text-align:center">Defect Category</th>
                                        <th style="text-align:center">Defect Sub Code</th>
                                        <th style="text-align:center">Defect Details</th>
                                        <th style="text-align:center">Defect Treatment</th>
                                    </thead>
                                    <tbody class="mb-0" id="list_of_defect_details">
                                        <tr>
                                            <td colspan="10" style="text-align: center;">
                                                <div class="spinner-border text-dark" role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Account Management -->
                        <div class="tab-pane fade" id="masterlist-record-3" role="tabpanel"
                            aria-labelledby="masterlist-record-3-tab">
                            <div class="row mb-3">
                                <div class="col-12 col-sm-2">
                                    <button class="btn btn-block btn-dark d-flex justify-content-left"
                                        data-toggle="modal" data-target="#add_account"
                                        style="color:#fff;height:34px;border-radius:.25rem;font-size:15px;font-weight:normal;">Add
                                        Account</button>
                                </div>
                            </div>

                            <!-- table -->
                            <div id="" class="card-body table-responsive m-0 p-0" style="max-height: 500px;">
                                <table class="table col-12 mt-3 table-head-fixed text-nowrap table-hover"
                                    id="accounts_table" style="background: #F9F9F9;">
                                    <thead style="text-align: center;">
                                        <th style="text-align:right">#</th>
                                        <th style="text-align:center">Action</th>
                                        <th style="text-align:center">Username</th>
                                        <th style="text-align:center">Role</th>
                                    </thead>
                                    <tbody class="mb-0" id="list_of_accounts">
                                        <tr>
                                            <td colspan="10" style="text-align: center;">
                                                <div class="spinner-border text-dark" role="status">
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