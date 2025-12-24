<?php include 'db_config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procedure 2: Performance Report</title>
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
                <h1 class="page-title">Agent Performance Report</h1>
                <p class="page-subtitle">Responsible: Efe Çalışkaner</p>
            </header>

            <form method="post" style="margin-bottom: 2rem;">
                <div class="grid" style="grid-template-columns: 1fr 1fr 1fr;">
                    <div class="form-group">
                        <label>Agent ID (Optional)</label>
                        <input type="number" name="agent_id" placeholder="ID">
                    </div>
                    <div class="form-group">
                        <label>Start Date</label>
                        <input type="date" name="start_date" required value="2025-01-01">
                    </div>
                    <div class="form-group">
                        <label>End Date</label>
                        <input type="date" name="end_date" required value="2025-12-31">
                    </div>
                </div>
                <button type="submit" class="btn btn-block">Generate Report</button>
            </form>

            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $agent_id = !empty($_POST['agent_id']) ? $_POST['agent_id'] : "NULL";
                $start = "'" . $_POST['start_date'] . "'";
                $end = "'" . $_POST['end_date'] . "'";

                $sql = "CALL sp_generate_agent_performance_report($agent_id, $start, $end)";
                $result = $conn->query($sql);

                if ($result) {
                    echo "<h3>Report Results</h3>";
                    echo "<div class='table-responsive'><table><thead><tr><th>Agent ID</th><th>Name / Email</th><th>Listings</th><th>Sold</th><th>Sales Value</th><th>Score</th></tr></thead><tbody>";
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['Agent_ID'] . "</td>";
                        echo "<td>" . $row['Agent_Email'] . "</td>"; 
                        echo "<td>" . $row['Total_Listings'] . "</td>";
                        echo "<td>" . $row['Properties_Sold'] . "</td>";
                        echo "<td>$" . number_format($row['Total_Sales_Value']) . "</td>";
                        echo "<td><strong>" . number_format($row['Performance_Score'], 2) . "</strong></td>";
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
