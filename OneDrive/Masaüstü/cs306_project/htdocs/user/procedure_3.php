<?php include 'db_config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Procedure 3: Get Properties By City</title>
    <style> body { font-family: Arial, sans-serif; margin: 20px; } table { border-collapse: collapse; width: 100%; } th, td { border: 1px solid #ddd; padding: 8px; } th { background-color: #f2f2f2; } </style>
</head>
<body>
    <h1>Procedure 3: Get Available Properties By City</h1>
    <p>Responsible: Doruk Kocaman</p>
    <a href="index.php">Back to Home</a>

    <form method="post">
        <label>City:</label>
        <input type="text" name="city" required>
        <button type="submit">Search</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $city = "'" . $_POST['city'] . "'";

        $sql = "CALL GetAvailablePropertiesByCity($city)";
        $result = $conn->query($sql);

        if ($result) {
            echo "<h3>Properties in " . $_POST['city'] . ":</h3>";
            echo "<table><tr><th>ID</th><th>Type</th><th>Price</th><th>Owner</th><th>Phone</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['Property_ID'] . "</td>";
                echo "<td>" . $row['Type'] . "</td>";
                echo "<td>" . $row['Price'] . "</td>";
                echo "<td>" . $row['Owner_Name'] . "</td>";
                echo "<td>" . $row['Owner_Phone'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            $result->free();
            $conn->next_result();
        } else {
            echo "Error: " . $conn->error;
        }
    }
    ?>
</body>
</html>
