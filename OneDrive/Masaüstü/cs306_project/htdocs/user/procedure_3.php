<?php include 'db_config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procedure 3: City Properties</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <a href="index.php" class="navbar-brand"> Real Estate System</a>
        <div class="nav-links">
            <a href="index.php">Dashboard</a>
            <a href="support_list.php">Support</a>
        </div>
    </nav>

    <div class="container">
        <div class="card">
            <header class="page-header">
                <h1 class="page-title">Properties by City</h1>
                <p class="page-subtitle">Responsible: Doruk Kocaman</p>
            </header>

            <form method="post" style="max-width: 500px; margin: 0 auto 2rem auto;">
                <div class="form-group">
                    <label>City Name</label>
                    <input type="text" name="city" required placeholder="Enter city (e.g. London)">
                </div>
                <button type="submit" class="btn btn-block">Search City</button>
            </form>

            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $city = "'" . $_POST['city'] . "'";

                $sql = "CALL GetAvailablePropertiesByCity($city)";
                $result = $conn->query($sql);

                if ($result) {
                    echo "<h3>Properties in " . htmlspecialchars($_POST['city']) . "</h3>";
                    echo "<div class='table-responsive'><table><thead><tr><th>ID</th><th>Type</th><th>Price</th><th>Owner</th><th>Phone</th></tr></thead><tbody>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['Property_ID'] . "</td>";
                        echo "<td>" . $row['Type'] . "</td>";
                        echo "<td>$" . number_format($row['Price']) . "</td>";
                        echo "<td>" . $row['Owner_Name'] . "</td>";
                        echo "<td>" . $row['Owner_Phone'] . "</td>";
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
