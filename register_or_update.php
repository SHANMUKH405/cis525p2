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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $umid = $_POST['umid'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $project_title = $_POST['project_title'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $slot_id = $_POST['time_slot'];

    // Check if UMID already exists
    $sql_check = "SELECT * FROM students WHERE umid = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $umid);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($result->num_rows > 0) {
        // Update existing registration
        $sql_update = "UPDATE students SET first_name = ?, last_name = ?, project_title = ?, email = ?, phone = ?, slot_id = ? WHERE umid = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("sssssis", $first_name, $last_name, $project_title, $email, $phone, $slot_id, $umid);
        if ($stmt_update->execute()) {
            // Update time slot availability
            $sql_update_slot = "UPDATE time_slots SET available_slots = available_slots - 1 WHERE slot_id = ?";
            $stmt_update_slot = $conn->prepare($sql_update_slot);
            $stmt_update_slot->bind_param("i", $slot_id);
            $stmt_update_slot->execute();

            // Redirect to the success page
            header("Location: registration_success.html");
            exit();
        } else {
            echo "<p>Error: " . $stmt_update->error . "</p>";
        }
    } else {
        // Insert new registration
        $sql = "INSERT INTO students (umid, first_name, last_name, project_title, email, phone, slot_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $umid, $first_name, $last_name, $project_title, $email, $phone, $slot_id);
        if ($stmt->execute()) {
            // Decrease the number of available slots
            $sql_update_slot = "UPDATE time_slots SET available_slots = available_slots - 1 WHERE slot_id = ?";
            $stmt_update_slot = $conn->prepare($sql_update_slot);
            $stmt_update_slot->bind_param("i", $slot_id);
            $stmt_update_slot->execute();

            // Redirect to the success page
            header("Location: registration_success.html");
            exit();
        } else {
            echo "<p>Error: " . $stmt->error . "</p>";
        }
    }

    $stmt_check->close();
}

$conn->close();
?>
