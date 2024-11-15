<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css?v=1"> <!-- Added version query to force cache refresh -->
    <title>Registered Students</title>
</head>
<body>
    <div class="container">
        <h2>Registered Students</h2>
        <?php
        $servername = getenv('DB_HOST');
        $username = getenv('DB_USER');
        $password = getenv('DB_PASS');
        $dbname = getenv('DB_NAME');

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT s.umid, s.first_name, s.last_name, s.project_title, s.email, s.phone, t.time_slot 
                FROM students s 
                JOIN time_slots t ON s.slot_id = t.slot_id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<table>";
            echo "<tr><th>UMID</th><th>First Name</th><th>Last Name</th><th>Project Title</th><th>Email</th><th>Phone</th><th>Time Slot</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>" . htmlspecialchars($row['umid']) . "</td><td>" . htmlspecialchars($row['first_name']) . "</td><td>" . htmlspecialchars($row['last_name']) . "</td><td>" . htmlspecialchars($row['project_title']) . "</td><td>" . htmlspecialchars($row['email']) . "</td><td>" . htmlspecialchars($row['phone']) . "</td><td>" . htmlspecialchars($row['time_slot']) . "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No students registered yet.</p>";
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
