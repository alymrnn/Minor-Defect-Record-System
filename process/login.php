<?php
include 'conn.php';
session_start();

if (isset($_POST['login_btn'])) {
    $username = $_POST['username'];

    if (empty($username)) {
        echo '<script>alert("Please enter your username")</script>';
    } else {
        $check = "SELECT username, role 
                  FROM m_accounts 
                  WHERE username = :username COLLATE SQL_Latin1_General_CP1_CS_AS";

        $stmt = $conn->prepare($check, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            // Fetch user details and set session variables
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $username = $row['username'];
            $role = $row['role'];

            $_SESSION['username'] = $username;
            $_SESSION['role'] = $role;

            if ($role == 'QC') {
                header('location: master/masterlist_m.php');
                exit;
            } 
        } else {
            echo '<script>alert("Sign In Failed. Incorrect credentials or account not found.")</script>';
        }
    }
}

if (isset($_POST['Logout'])) {
    session_unset();
    header('location: index.php');
}

?>