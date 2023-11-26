<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>List of Teaching Assistants</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php
	include 'mainmenu.php';
    ?>
    <?php
        include 'connectdb.php';
    ?>
    <h1>List of Teaching Assistants</h1>
    <form action="list_tas.php" method="post">
        <input type="radio" id="lastName" name="sort" value="lastName">
        <label for="lastName">Last Name</label>

        <input type="radio" id="degreeType" name="sort" value="degreeType">
        <label for="degreeType">Degree Type</label>

        <input type="radio" id="asc" name="order" value="ASC" checked>
        <label for="asc">Ascending</label>

        <input type="radio" id="desc" name="order" value="DESC">
        <label for="desc">Descending</label>

        <input type="submit" value="Sort">
    </form>
    <?php
    
     if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $sort = $_POST['sort'] ?? 'lastName'; // Default sorting by lastName
        $order = $_POST['order'] ?? 'ASC'; // Default order is ASC
        
        // Sanitize the input
        $sort = $sort === 'degreeType' ? 'degreeType' : 'lastName';
        $order = $order === 'DESC' ? 'DESC' : 'ASC';

        $query = "SELECT * FROM ta  ORDER BY $sort $order";
        $result = mysqli_query($connection,$query);
        if ($result > 0) {
            echo "<table><tr><th>ID</th><th>First Name</th><th>Last Name</th><th>student Number</th><th>Degree Type</th><th>image</th></tr>"
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>".$row["tauserid"]."</td>
                        <td>".$row["lastName"]."</td>
                        <td>".$row["firstName"]."</td>          
                        <td>".$row["studentnum"]."</td>
                        <td>".$row["degreetype"]."</td>
                        <td><img src=".$row["image"]."></td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "0 results";
        }
    }
    ?>
</body>
</html>
