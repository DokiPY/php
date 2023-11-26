<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TAs for Course Offering</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php
	include 'mainmenu.php';
    ?>
    <?php
        include 'connectdb.php';
    ?>
    <h1>TAs for Course Offering</h1>
    <form action="tas_for_course_offering.php" method="post">
        <div>
            <label for="courseOfferId">Course Offering ID:</label>
            <input type="text" id="courseOfferId" name="courseOfferId" required>
        </div>
        <div>
            <input type="submit" name="view" value="View TAs">
        </div>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['view'])) {
        $courseOfferId = $connection->real_escape_string($_POST['courseOfferId']);

        // Query to select all TAs for a specific course offering
        $query = "SELECT ta.userId, ta.firstName, ta.lastName, c.courseNum, c.courseName
                  FROM teachingassistant ta
                  JOIN hasworkedon h ON ta.userId = h.tauserid
                  JOIN courseoffering co ON h.courseOfferId = co.courseOfferId
                  JOIN course c ON co.whichcourse = c.courseNum
                  WHERE co.courseOfferId = ?";

        $stmt = $connection->prepare($query);
        $stmt->bind_param("s", $courseOfferId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<table><tr><th>User ID</th><th>First Name</th><th>Last Name</th><th>Course Number</th><th>Course Name</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row["userId"] . "</td><td>" . $row["firstName"] . "</td><td>" . $row["lastName"] . "</td><td>" . $row["courseNum"] . "</td><td>" . $row["courseName"] . "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No TAs found for this course offering.</p>";
        }
        $stmt->close();
    }
    ?>
</body>
</html>
