<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TAs by Degree Type</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php
	include 'mainmenu.php';
    ?>
    <?php
        include 'connectdb.php';
    ?>
    <h1>Select TA's Degree</h1>
    <form action="ta_by_degree.php" method="post">
        <input type="radio" id="masters" name="degreeType" value="Masters">
        <label for="masters">Masters</label>

        <input type="radio" id="phd" name="degreeType" value="PhD">
        <label for="phd">PhD</label>

        <input type="submit" value="List TAs">
    </form>
    
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $degree = $_POST['degreeType'];

        // Prepare statement to avoid SQL injection
        $stmt = $connection->prepare("SELECT * WHERE degreeType = ?");
        $stmt->bind_param("s", $degree);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result > 0) {
            echo "<table><tr><th>ID</th><th>First Name</th><th>Last Name</th><th>student Number</th><th>Degree Type</th><th>image</th></tr>";
            // Output data of each row
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>".$row["userId"]."</td>
                        <td>".$row["firstName"]."</td>
                        <td>".$row["lastName"]."</td>
                        <td>".$row["studentnum"]."</td>
                        <td>".$row["degreetype"]."</td>
                        <td><img src=".$row["image"]."></td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "0 results found for this degree type.";
        }
        $stmt->close();
    }
    ?>
</body>
</html>
