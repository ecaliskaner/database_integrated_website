<?php include 'db_config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Procedure 2: Agent Performance Report</title>
    <style> body { font-family: Arial, sans-serif; margin: 20px; } table { border-collapse: collapse; width: 100%; } th, td { border: 1px solid #ddd; padding: 8px; } th { background-color: #f2f2f2; } </style>
</head>
<body>
    <h1>Procedure 2: Generate Agent Performance Report</h1>
    <p>Responsible: Efe Çalışkaner</p>
    <a href="index.php">Back to Home</a>

    <form method="post">
        <label>Agent ID (Optional):</label>
        <input type="number" name="agent_id">
        <label>Start Date:</label>
        <input type="date" name="start_date" required value="2025-01-01">
        <label>End Date:</label>
        <input type="date" name="end_date" required value="2025-12-31">
        <button type="submit">Generate Report</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $agent_id = !empty($_POST['agent_id']) ? $_POST['agent_id'] : "NULL";
        $start = "'" . $_POST['start_date'] . "'";
        $end = "'" . $_POST['end_date'] . "'";

        $sql = "CALL sp_generate_agent_performance_report($agent_id, $start, $end)";
        $result = $conn->query($sql);

        if ($result) {
            echo "<h3>Performance Report:</h3>";
            echo "<table><tr><th>Agent ID</th><th>Name</th><th>Listings</th><th>Sold</th><th>Sales Value</th><th>Score</th></tr>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['Agent_ID'] . "</td>";
                echo "<td>" . $row['Agent_Email'] . "</td>"; // Using Email as Name proxy or just display email
                echo "<td>" . $row['Total_Listings'] . "</td>";
                echo "<td>" . $row['Properties_Sold'] . "</td>";
                echo "<td>" . $row['Total_Sales_Value'] . "</td>";
                echo "<td>" . $row['Performance_Score'] . "</td>";
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
