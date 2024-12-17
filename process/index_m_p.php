<?php
include 'conn.php';
// include '../conn_emp_mgt.php';

$method = $_POST['method'];

if ($method == 'qr_setting_list') {
    $c = 0;

    $query = "SELECT * FROM m_car_qr_setting ORDER BY car_maker ASC";
    $stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        foreach ($stmt->fetchALL() as $row) {
            $c++;
            echo '<tr style="cursor:pointer;" class="modal-trigger" data-toggle="modal" data-target="#update_qr_setting" onclick="get_car_setting_details(&quot;' . $row['id'] . '~!~' . $row['car_maker'] . '~!~' . $row['car_model'] . '~!~' . $row['car_value'] . '~!~' . $row['total_length'] . '~!~' . $row['product_name_start'] . '~!~' . $row['product_name_length'] . '~!~' . $row['lot_no_start'] . '~!~' . $row['lot_no_length'] . '~!~' . $row['serial_no_start'] . '~!~' . $row['serial_no_length'] . '&quot;)">';
            echo '<td style="text-align:center;">' . $c . '</td>';
            echo '<td style="text-align:center;">' . $row['car_maker'] . '</td>';
            echo '<td style="text-align:center;">' . $row['car_model'] . '</td>';
            echo '<td style="text-align:center;">' . $row['car_value'] . '</td>';
            echo '<td style="text-align:center;">' . $row['total_length'] . '</td>';
            echo '<td style="text-align:center;">' . $row['product_name_start'] . '</td>';
            echo '<td style="text-align:center;">' . $row['product_name_length'] . '</td>';
            echo '<td style="text-align:center;">' . $row['lot_no_start'] . '</td>';
            echo '<td style="text-align:center;">' . $row['lot_no_length'] . '</td>';
            echo '<td style="text-align:center;">' . $row['serial_no_start'] . '</td>';
            echo '<td style="text-align:center;">' . $row['serial_no_length'] . '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr>';
        echo '<td colspan="12" style="text-align:center; color:red;">No Result</td>';
        echo '</tr>';
    }
}

if ($method == 'register_setting') {
    $car_maker = trim($_POST['car_maker']);
    $car_model = trim($_POST['car_model']);
    $car_value = trim($_POST['car_value']);
    $total_length = trim($_POST['total_length']);
    $pro_name_start = trim($_POST['pro_name_start']);
    $pro_name_length = trim($_POST['pro_name_length']);
    $lot_no_start = trim($_POST['lot_no_start']);
    $lot_no_length = trim($_POST['lot_no_length']);
    $serial_no_start = trim($_POST['serial_no_start']);
    $serial_no_length = trim($_POST['serial_no_length']);

    $stmt = NULL;
    $query = "INSERT INTO m_car_qr_setting (car_maker,car_model,car_value,total_length,product_name_start,product_name_length,lot_no_start,lot_no_length,serial_no_start,serial_no_length) VALUES ('$car_maker','$car_model','$car_value','$total_length','$pro_name_start','$pro_name_length','$lot_no_start','$lot_no_length','$serial_no_start','$serial_no_length')";

    $stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
}

if ($method == 'update_setting') {
    $id = $_POST['id'];
    $car_maker = trim($_POST['car_maker']);
    $car_model = trim($_POST['car_model']);
    $car_value = trim($_POST['car_value']);
    $total_length = trim($_POST['total_length']);
    $pro_name_start = trim($_POST['pro_name_start']);
    $pro_name_length = trim($_POST['pro_name_length']);
    $lot_no_start = trim($_POST['lot_no_start']);
    $lot_no_length = trim($_POST['lot_no_length']);
    $serial_no_start = trim($_POST['serial_no_start']);
    $serial_no_length = trim($_POST['serial_no_length']);

    $query = "UPDATE m_car_qr_setting SET car_maker = '$car_maker', car_model = '$car_model', car_value = '$car_value', total_length = '$total_length', product_name_start = '$pro_name_start', product_name_length = '$pro_name_length', lot_no_start = '$lot_no_start', lot_no_length = '$lot_no_length', serial_no_start = '$serial_no_start', serial_no_length = '$serial_no_length' WHERE id = '$id'";

    $stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));

    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }

}

if ($method == 'delete_setting') {
    $id = $_POST['id'];

    $query = "DELETE FROM m_car_qr_setting WHERE id = $id";
    $stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
}

// defect details
if ($method == 'defect_details_list') {
    $c = 0;

    $query = "SELECT * FROM m_defect_details";
    $stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        foreach ($stmt->fetchALL() as $row) {
            $c++;
            echo '<tr style="cursor:pointer;">';
            echo '<td style="text-align:right;">' . $c . '</td>';
            echo '<td style="text-align:center;">';
            echo '<button type="button" class="btn btn-outline-danger btn-xs" onclick="delete_added_defect(event)" data-id="' . $row["id"] . '"><i class="fas fa-trash"></i> Delete</button>';
            echo '</td>';
            echo '<td style="text-align:center;">' . $row['defect_code_dd'] . '</td>';
            echo '<td style="text-align:center;">' . $row['defect_code_value_dd'] . '</td>';
            echo '<td style="text-align:center;">' . $row['defect_sub_code_dd'] . '</td>';
            echo '<td style="text-align:center;">' . $row['defect_details_dd'] . '</td>';
            echo '<td style="text-align:center;">' . $row['defect_treatment_dd'] . '</td>';

            echo '</tr>';
        }
    } else {
        echo '<tr>';
        echo '<td colspan="12" style="text-align:center; color:red;">No Result</td>';
        echo '</tr>';
    }
}

if ($method == 'register_defect_details') {
    $defect_code = trim($_POST['defect_code']);
    $defect_category = trim($_POST['defect_category']);
    $defect_sub_code = trim($_POST['defect_sub_code']);
    $defect_details = trim($_POST['defect_details']);
    $defect_treatment = trim($_POST['defect_treatment']);

    // Check if the defect_code and defect_category exist in m_defect_category
    $stmt_check = $conn->prepare("SELECT COUNT(*) FROM m_defect_category WHERE defect_code_dc = ? AND defect_category_dc = ?");
    $stmt_check->execute([$defect_code, $defect_category]);
    $exists = $stmt_check->fetchColumn();

    if ($exists == 0) {
        // If not exists, insert into m_defect_category
        $stmt_insert_category = $conn->prepare("INSERT INTO m_defect_category (defect_code_dc, defect_category_dc) VALUES (?, ?)");
        if ($stmt_insert_category->execute([$defect_code, $defect_category])) {
            echo 'defect category inserted';
        } else {
            echo 'error inserting defect category';
        }
    }

    // Proceed to insert defect details
    $stmt = NULL;
    $query = "INSERT INTO m_defect_details (defect_code_dd, defect_code_value_dd, defect_sub_code_dd, defect_details_dd, defect_treatment_dd) 
              VALUES ('$defect_code', '$defect_category', '$defect_sub_code', '$defect_details', '$defect_treatment')";

    $stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
}

if ($method == 'delete_added_defect') {
    $query = "DELETE FROM m_defect_details WHERE id = :id";
    $stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
    $stmt->bindParam(':id', $_POST['id']);
    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
}

if ($method == 'accounts_list') {
    $c = 0;

    $query = "SELECT * FROM m_accounts";
    $stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        foreach ($stmt->fetchALL() as $row) {
            $c++;
            echo '<tr style="cursor:pointer;">';
            echo '<td style="text-align:right;">' . $c . '</td>';
            echo '<td style="text-align:center;">';
            echo '<button type="button" class="btn btn-outline-danger btn-xs" onclick="delete_added_account(event)" data-id="' . $row["id"] . '"><i class="fas fa-trash"></i> Delete</button>';
            echo '</td>';
            echo '<td style="text-align:center;">' . $row['username'] . '</td>';
            echo '<td style="text-align:center;">' . $row['role'] . '</td>';

            echo '</tr>';
        }
    } else {
        echo '<tr>';
        echo '<td colspan="12" style="text-align:center; color:red;">No Result</td>';
        echo '</tr>';
    }
}

if ($method == 'register_account') {
    $username = trim($_POST['username']);
    $role = trim($_POST['role']);

    $stmt = NULL;
    $query = "INSERT INTO m_accounts (username,role) VALUES ('$username','$role')";

    $stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
}

if ($method == 'delete_added_account') {
    $query = "DELETE FROM m_accounts WHERE id = :id";
    $stmt = $conn->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
    $stmt->bindParam(':id', $_POST['id']);
    if ($stmt->execute()) {
        echo 'success';
    } else {
        echo 'error';
    }
}




$conn = NULL;
?>