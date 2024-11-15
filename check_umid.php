<?php
$servername = "cis-525-p3.cv26uk2u4t1h.us-east-2.rds.amazonaws.com";
$username = "admin";
$password = "Bitspilani405";
$dbname = "innodb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['umid'])) {
    $umid = $_GET['umid'];
    $sql_check = "SELECT * FROM students WHERE umid = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $umid);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    $response = array("exists" => $result->num_rows > 0);
    echo json_encode($response);

    $stmt_check->close();
}

$conn->close();
?>
