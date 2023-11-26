<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Modify TA</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php
	include 'mainmenu.php';
    ?>
    <?php
        include 'connectdb.php';
    ?>
    <h1>Modify Teaching Assistant</h1>
    <form action="modify_ta.php" method="post">
        <div>
            <label for="userid">TA User ID:</label>
            <input type="text" id="userid" name="userid" required>
        </div>
        <div>
            <label for="imageurl">New Image URL:</label>
            <input type="text" id="imageurl" name="imageurl" required>
        </div>
        <div>
            <input type="submit" name="update" value="Update Image">
        </div>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
        $userid = $connection->real_escape_string($_POST['userid']);
        $imageurl = $connection->real_escape_string($_POST['imageurl']);

        // Prepare statement to prevent SQL injection
        $stmt = $connection->prepare("UPDATE teachingassistant SET image = ? WHERE userId = ?");
        $stmt->bind_param("ss", $imageurl, $userid);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo "<p>Image updated successfully.</p>";
        } else {
            echo "<p>Error updating image or no change made: " . $stmt->error . "</p>";
        }
        $stmt->close();
    }
    ?>
</body>
</html>
