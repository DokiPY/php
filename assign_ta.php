<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Assign TA to Course Offering</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php
	include 'mainmenu.php';
    ?>
    <?php
        include 'connectdb.php';
    ?>
    <h1>Assign TA to Course</h1>
    <form action="assign_ta.php" method="post">
        <div>
            <label for="tauserid">TA User ID:</label>
            <input type="text" id="tauserid" name="tauserid" required>
        </div>
        <div>
            <label for="courseid">Course ID:</label>
            <input type="text" id="courseid" name="courseid" required>
        </div>
        <div>
            <label for="hours">Number of Hours:</label>
            <input type="number" id="hours" name="hours" required>
        </div>
        <div>
            <input type="submit" name="assign" value="Assign TA">
        </div>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['assign'])) {
        $tauserid = $connection->real_escape_string($_POST['tauserid']);
        $courseid = $connection->real_escape_string($_POST['courseid']);
        $hours = $connection->real_escape_string($_POST['hours']);

        // Check if the TA is already assigned to the course offering
        $checkQuery = "SELECT * FROM hasworkedon WHERE tauserid = ? AND courseid = ?";
        $stmt = $connection->prepare($checkQuery);
        $stmt->bind_param("ss", $tauserid, $courseid);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            echo "<p>TA is already assigned to this course offering.</p>";
        } else {
            // Proceed with the assignment
            $assignQuery = "INSERT INTO hasworkedon (tauserid, courseid, hours) VALUES (?, ?, ?)";
            $stmt = $connection->prepare($assignQuery);
            $stmt->bind_param("ssi", $tauserid, $courseid, $hours);
            $stmt->execute();
            if ($stmt->affected_rows > 0) {
                echo "<p>TA assigned to course offering successfully.</p>";
            } else {
                echo "<p>Error assigning TA to course offering: " . $stmt->error . "</p>";
            }
        }
        $stmt->close();
    }
    ?>
</body>
</html>
