<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Student Registration</title>
    <script src="check_umid_script.js"></script>

</head>
<body>
    <div class="container">
        <h2>Student Registration for Demonstration</h2>
         <form action="register_or_update.php" method="POST">
            <label for="umid">UMID (8 digits):</label>
            <input type="text" id="umid" name="umid" required pattern="\d{8}" title="UMID must be 8 digits"><br>

            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" required pattern="[A-Za-z]+" title="First name must only contain letters"><br>

            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" required pattern="[A-Za-z]+" title="Last name must only contain letters"><br>

            <label for="project_title">Project Title:</label>
            <input type="text" id="project_title" name="project_title" required><br>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required><br>

            <label for="phone">Phone (999-999-9999):</label>
            <input type="text" id="phone" name="phone" required pattern="\d{3}-\d{3}-\d{4}" title="Phone must be in the format 999-999-9999"><br>

            <label for="time_slot">Choose a Time Slot:</label><br>
            <div class="radio-group">
                <?php include 'get_slots.php'; ?>
            </div>

            <input type="submit" value="Register or Update">
        </form>

        <button onclick="window.location.href='list_students.php'" class="view-list-btn">View Registered Students</button>
    </div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $servername = "cis-525-p3.cv26uk2u4t1h.us-east-2.rds.amazonaws.com";
        $username = "admin";  // Update with your RDS username
        $password = "Bitspilani405";  // Update with your RDS password
        $dbname = "innodb";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

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
            echo "<p>UMID already registered. Do you want to update your registration?</p>";
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
        $conn->close();
    }
    ?>
</body>
</html>
