<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete TA</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php
	include 'mainmenu.php';
    ?>
    <?php
        include 'connectdb.php';
    ?>
    <h1>Delete Teaching Assistant</h1>
    <form action="delete_ta.php" method="post">
        <div>
            <label for="userid">TA User ID:</label>
            <input type="text" id="userid" name="userid" required>
        </div>
        <div>
            <input type="submit" name="delete" value="Delete TA">
        </div>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
        $userid = $connection->real_escape_string($_POST['userid']);

        // First, check if the TA is assigned to a course offering
        $checkQuery = "SELECT * FROM hasworkedon WHERE tauserid = ?";
        $stmt = $connection->prepare($checkQuery);
        $stmt->bind_param("s", $userid);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            echo "<p>You cannot delete this TA as they are assigned to a course offering.</p>";
        } else {
            // Proceed with deletion
            $deleteQuery = "DELETE FROM teachingassistant WHERE userid = ?";
            $stmt = $connection->prepare($deleteQuery);
            $stmt->bind_param("s", $userid);
            if ($stmt->execute()) {
                echo "<p>TA deleted successfully.</p>";
            } else {
                echo "<p>Error deleting TA: " . $stmt->error . "</p>";
            }
        }
        $stmt->close();
    }
    ?>
</body>
</html>
