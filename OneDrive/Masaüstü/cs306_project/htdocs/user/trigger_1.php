<?php include 'db_config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Trigger 1: Increment Appointment Count</title>
    <style> body { font-family: Arial, sans-serif; margin: 20px; } .message { margin: 10px 0; padding: 10px; border: 1px solid #ccc; background: #f9f9f9; } </style>
</head>
<body>
    <h1>Trigger 1: Increment Agent Appointment Count</h1>
    <p>Responsible: Halis Cem Şahin</p>
    <p>This trigger automatically increments the agent's appointment count when a new appointment is added.</p>
    <a href="index.php">Back to Home</a>

    <?php
    $agent_id = 1;

    // Get current count
    $sql = "SELECT appointment_count FROM Agent WHERE Agent_ID = $agent_id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $current_count = $row['appointment_count'];

    echo "<div class='message'>Current Appointment Count for Agent $agent_id: <strong>$current_count</strong></div>";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Insert new appointment
        // Find next ID
        $res = $conn->query("SELECT MAX(Appointment_ID) as max_id FROM Appointment");
        $row_id = $res->fetch_assoc();
        $next_id = $row_id['max_id'] + 1;
        
        $date = date('Y-m-d');
        $time = date('H:i:s');
        $customer_id = 1; // Dummy customer

        $sql_insert = "INSERT INTO Appointment (Appointment_ID, Appointment_Date, Appointment_Time, Customer_ID, Agent_ID) VALUES ($next_id, '$date', '$time', $customer_id, $agent_id)";
        
        if ($conn->query($sql_insert) === TRUE) {
            echo "<div class='message' style='color:green'>New appointment added successfully!</div>";
            
            // Get new count
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            $new_count = $row['appointment_count'];
            echo "<div class='message'>New Appointment Count for Agent $agent_id: <strong>$new_count</strong></div>";
        } else {
            echo "<div class='message' style='color:red'>Error: " . $conn->error . "</div>";
        }
    }
    ?>

    <form method="post">
        <button type="submit">Add Dummy Appointment for Agent 1</button>
    </form>
</body>
</html>
