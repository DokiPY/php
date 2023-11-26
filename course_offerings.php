<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Course Offerings</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php
	include 'mainmenu.php';
    ?>
    <?php
        include 'connectdb.php';
    ?>
    <h1>View Course Offerings</h1>
    <form action="course_offerings.php" method="post">
        <div>
            <label for="courseid">Course ID:</label>
            <input type="text" id="courseid" name="courseid" required>
        </div>
        <div>
            <label for="startyear">Start Year:</label>
            <input type="number" id="startyear" name="startyear">
        </div>
        <div>
            <label for="endyear">End Year:</label>
            <input type="number" id="endyear" name="endyear">
        </div>
        <div>
            <input type="submit" name="view" value="View Offerings">
        </div>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['view'])) {
        $courseid = $connection->real_escape_string($_POST['courseid']);
        $startyear = isset($_POST['startyear']) ? $connection->real_escape_string($_POST['startyear']) : '';
        $endyear = isset($_POST['endyear']) ? $connection->real_escape_string($_POST['endyear']) : '';

        // Prepare the base query
        $query = "SELECT co.courseOfferId, co.numStudents, co.term, co.year, c.courseNum, c.courseName
                  FROM courseoffering co
                  JOIN course c ON co.whichcourse = c.courseNum
                  WHERE co.whichcourse = ?";
        
        // Add conditions for start year and end year if they are provided
        if (!empty($startyear)) {
            $query .= " AND co.year >= ?";
        }
        if (!empty($endyear)) {
            $query .= " AND co.year <= ?";
        }

        // Prepare and bind parameters
        if (!empty($startyear) && !empty($endyear)) {
            $stmt = $connection->prepare($query);
            $stmt->bind_param("sii", $courseid, $startyear, $endyear);
        } elseif (!empty($startyear)) {
            $stmt = $connection->prepare($query);
            $stmt->bind_param("si", $courseid, $startyear);
        } elseif (!empty($endyear)) {
            $stmt = $connection->prepare($query);
            $stmt->bind_param("si", $courseid, $endyear);
        } else {
            $stmt = $connection->prepare($query);
            $stmt->bind_param("s", $courseid);
        }
        
        // Execute and fetch results
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            echo "<table><tr><th>Offering ID</th><th>Number of Students</th><th>Term</th><th>Year</th><th>Course Number</th><th>Course Name</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row["courseOfferId"] . "</td><td>" . $row["numStudents"] . "</td><td>" . $row["term"] . "</td><td>" . $row["year"] . "</td><td>" . $row["courseNum"] . "</td><td>" . $row["courseName"] . "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No course offerings found for this course ID and year range.</p>";
        }
        $stmt->close();
    }
    ?>
</body>
</html>
