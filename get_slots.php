<?php
$servername = "cis-525-p3.cv26uk2u4t1h.us-east-2.rds.amazonaws.com";
$username = "admin";  // Update with your RDS username
$password = "Bitspilani405";  // Update with your RDS password
$dbname = "innodb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql_slots = "SELECT * FROM time_slots WHERE available_slots > 0";
$result_slots = $conn->query($sql_slots);

if ($result_slots->num_rows > 0) {
    while ($row = $result_slots->fetch_assoc()) {
        echo '<input type="radio" id="slot' . $row['slot_id'] . '" name="time_slot" value="' . $row['slot_id'] . '" required>';
        echo '<label for="slot' . $row['slot_id'] . '">' . $row['time_slot'] . ' (' . $row['available_slots'] . ' seats remaining)</label><br>';
    }
} else {
    echo "<p>All slots are fully booked.</p>";
}

$conn->close();
?>
