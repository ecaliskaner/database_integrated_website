<?php include 'db_config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procedure 1: Search Properties</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <a href="index.php" class="navbar-brand">🏠 Real Estate System</a>
        <div class="nav-links">
            <a href="index.php">Dashboard</a>
            <a href="support_list.php">Support</a>
        </div>
    </nav>

    <div class="container">
        <div class="card">
            <header class="page-header">
                <h1 class="page-title">Search Available Properties</h1>
                <p class="page-subtitle">Responsible: Halis Cem Şahin</p>
            </header>

            <form method="post" style="margin-bottom: 2rem;">
                <div class="grid">
                    <div class="form-group">
                        <label>Type</label>
                        <select name="type">
                            <option value="">Any Type</option>
                            <option value="Apartment">Apartment</option>
                            <option value="Villa">Villa</option>
                            <option value="Office">Office</option>
                            <option value="Studio">Studio</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>City</label>
                        <input type="text" name="city" placeholder="e.g. Istanbul">
                    </div>
                    <div class="form-group">
                        <label>Min Price</label>
                        <input type="number" name="min_price" placeholder="Min">
                    </div>
                    <div class="form-group">
                        <label>Max Price</label>
                        <input type="number" name="max_price" placeholder="Max">
                    </div>
                </div>
                <button type="submit" class="btn btn-block">Search Properties</button>
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
                    echo "<h3>Search Results</h3>";
                    echo "<div class='table-responsive'><table><thead><tr><th>ID</th><th>Type</th><th>Size</th><th>Price</th><th>City</th><th>Owner</th></tr></thead><tbody>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['Property_ID'] . "</td>";
                        echo "<td>" . $row['Type'] . "</td>";
                        echo "<td>" . $row['Size'] . " m²</td>";
                        echo "<td>$" . number_format($row['Price']) . "</td>";
                        echo "<td>" . $row['City'] . "</td>";
                        echo "<td>" . $row['Owner_Name'] . "</td>";
                        echo "</tr>";
                    }
                    echo "</tbody></table></div>";
                    $result->free();
                    $conn->next_result(); 
                } else {
                    echo "<div class='message error'>Error: " . $conn->error . "</div>";
                }
            }
            ?>
        </div>
    </div>
</body>
</html>
