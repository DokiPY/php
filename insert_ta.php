<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Insert New TA</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php
	include 'mainmenu.php';
    ?>
    <?php
        include 'connectdb.php';
    ?>
    <h1>Insert New Teaching Assistant</h1>
    <form action="insert_ta.php" method="post">
        <div>
            <label for="tauserid">User ID:</label>
            <input type="text" id="userid" name="tauserid" required>
        </div>
        <div>
            <label for="firstname">First Name:</label>
            <input type="text" id="firstname" name="firstname" required>
        </div>
        <div>
            <label for="lastname">Last Name:</label>
            <input type="text" id="lastname" name="lastname" required>
        </div>
        <div>
            <label for="studentnum">Student Number:</label>
            <input type="text" id="studentnum" name="studentNum" required>
        </div>
        <div>
            <label for="degreeType">Degree Type:</label>
            <select id="degreetype" name="degreeType">
                <option value="Masters">Masters</option>
                <option value="PhD">PhD</option>
            </select>
        </div>
        <!-- Add fields for courses they love or hate -->
        <div>
            <input type="submit" value="Insert TA">
        </div>
    </form>

    <?php

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Sanitize input data and assign variables
        $userid = $conn->real_escape_string($_POST['userid']);
        $firstname = $conn->real_escape_string($_POST['firstname']);
        $lastname = $conn->real_escape_string($_POST['lastname']);
        $studentnum = $conn->real_escape_string($_POST['studentnum']);
        $degreetype = $conn->real_escape_string($_POST['degreetype']);

        // Check for duplicate user ID or student number
        $duplicateCheckQuery = "SELECT * FROM ta WHERE userId = ? OR studentnum = ?";
        $stmt = $conn->prepare($duplicateCheckQuery);
        $stmt->bind_param("ss", $userid, $studentnum);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            echo "<p>Error: A TA with the given User ID or Student Number already exists.</p>";
        } else {
            // Proceed with insertion
            $insertQuery = "INSERT INTO teachingassistant (userId, firstname, lastname, studentnum, degreetype) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("sssss", $userid, $firstname, $lastname, $studentnum, $degreetype);
            if ($stmt->execute()) {
                echo "<p>New TA inserted successfully.</p>";
            } else {
                echo "<p>Error inserting TA: " . $stmt->error . "</p>";
            }
        }
        $stmt->close();
    }
    ?>
</body>
</html>
