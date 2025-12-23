<?php include 'db_config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Procedure 1: Search Available Properties</title>
    <style> body { font-family: Arial, sans-serif; margin: 20px; } table { border-collapse: collapse; width: 100%; } th, td { border: 1px solid #ddd; padding: 8px; } th { background-color: #f2f2f2; } </style>
</head>
<body>
    <h1>Procedure 1: Search Available Properties</h1>
    <p>Responsible: Halis Cem Şahin</p>
    <a href="index.php">Back to Home</a>

    <form method="post">
        <label>Type:</label>
        <select name="type">
            <option value="">Any</option>
            <option value="Apartment">Apartment</option>
            <option value="Villa">Villa</option>
            <option value="Office">Office</option>
            <option value="Studio">Studio</option>
        </select>
        <label>Min Price:</label>
        <input type="number" name="min_price">
        <label>Max Price:</label>
        <input type="number" name="max_price">
        <label>City:</label>
        <input type="text" name="city">
        <button type="submit">Search</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $type = !empty($_POST['type']) ? "'" . $_POST['type'] . "'" : "NULL";
        $min = !empty($_POST['min_price']) ? $_POST['min_price'] : "NULL";
        $max = !empty($_POST['max_price']) ? $_POST['max_price'] : "NULL";
        $city = !empty($_POST['city']) ? "'" . $_POST['city'] . "'" : "NULL";

        $sql = "CALL sp_search_available_properties($type, $min, $max, $city)";
        $result = $conn->query($sql);

        if ($result) {
            echo "<h3>Results:</h3>";
            echo "<table><tr><th>ID</th><th>Type</th><th>Size</th><th>Price</th><th>City</th><th>Owner</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['Property_ID'] . "</td>";
                echo "<td>" . $row['Type'] . "</td>";
                echo "<td>" . $row['Size'] . "</td>";
                echo "<td>" . $row['Price'] . "</td>";
                echo "<td>" . $row['City'] . "</td>";
                echo "<td>" . $row['Owner_Name'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            $result->free();
            $conn->next_result(); // Clear result set for next query
        } else {
            echo "Error: " . $conn->error;
        }
    }
    ?>
</body>
</html>
