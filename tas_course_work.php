<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TA's Course Work</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php
	include 'mainmenu.php';
    ?>
    <?php
        include 'connectdb.php';
    ?>
    <h1>View TA's Course Work</h1>
    <form action="tas_course_work.php" method="post">
        <div>
            <label for="tauserid">TA User ID:</label>
            <input type="text" id="tauserid" name="tauserid" required>
        </div>
        <div>
            <input type="submit" name="view" value="View Course Work">
        </div>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['view'])) {
        $tauserid = $connection->real_escape_string($_POST['tauserid']);

        // Query to select all the course work for a TA
        $query = "SELECT c.courseNum, c.courseName, co.term, co.year, h.hours, 
                         CASE WHEN l.courseNum IS NOT NULL THEN 'Loves' WHEN h.courseNum IS NOT NULL THEN 'Hates' ELSE 'Neutral' END as sentiment
                  FROM hasworkedon h
                  LEFT JOIN course c ON h.courseNum = c.courseNum
                  LEFT JOIN courseoffering co ON h.courseOfferId = co.courseOfferId
                  LEFT JOIN loves l ON h.tauserid = l.tauserid AND h.courseNum = l.courseNum
                  LEFT JOIN hates ht ON h.tauserid = ht.tauserid AND h.courseNum = ht.courseNum
                  WHERE h.tauserid = ?";

        $stmt = $connection->prepare($query);
        $stmt->bind_param("s", $tauserid);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<table><tr><th>Course Number</th><th>Course Name</th><th>Term</th><th>Year</th><th>Hours</th><th>Sentiment</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>" . $row["courseNum"] . "</td><td>" . $row["courseName"] . "</td><td>" . $row["term"] . "</td><td>" . $row["year"] . "</td><td>" . $row["hours"] . "</td><td>" . $row["sentiment"] . "</td></tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No course work found for this TA.</p>";
        }
        $stmt->close();
    }
    ?>
</body>
</html>
