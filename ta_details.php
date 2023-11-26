<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TA Info</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php
	include 'mainmenu.php';
    ?>
    <?php
        include 'connectdb.php';
    ?>
    <h1>TA Details</h1>
    <?php

    // Assuming you have a TA's userId
    $userId = $_GET['userid']; // Make sure to validate and sanitize this input!

    // Prepare statement to avoid SQL injection
    $stmt = $conn->prepare("SELECT * FROM teachingassistant WHERE userId = ?");
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $ta = $result->fetch_assoc();
        echo "<div>";
        echo "<p>ID: ".$ta["userId"]."</p>";
        echo "<p>Name: ".$ta["firstName"]." ".$ta["lastName"]."</p>";
        echo "<p>Student Number: ".$ta["studentNum"]."</p>";
        echo "<p>Degree Type: ".$ta["degreeType"]."</p>";
        // Display image or a placeholder
        echo "<img src='".($ta["image"] ? $ta["image"] : "placeholder.png")."' alt='TA Image'>";
        echo "</div>";

        // Now, you could fetch and display the courses the TA loves and hates
        // You would run two more queries here using JOINs with the loves and hates tables

        // Don't forget to close your prepared statements
        $stmt->close();
    } else {
        echo "No details found for this TA.";
    }
    ?>
</body>
</html>
