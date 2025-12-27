<?php include 'db_config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trigger 1: Appointment Tracker</title>
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
                <h1 class="page-title">Trigger 1: Increment Agent Appointment Count</h1>
                <p class="page-subtitle">Responsible: Halis Cem Şahin</p>
            </header>

            <div class="form-group">
                <p>This trigger automatically increments the <code>appointment_count</code> for an agent whenever a new appointment is inserted.</p>
            </div>

            <div class="grid" style="grid-template-columns: 1fr 1fr; margin-bottom: 2rem;">
                <div>
                    <h3>Current State</h3>
                    <?php
                    $agent_id = 1; // Demo agent
                    $sql = "SELECT Agent_ID, appointment_count FROM Agent WHERE Agent_ID = $agent_id";
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    $current_count = $row['appointment_count'];
                    echo "<p style='font-size: 2rem; font-weight: bold; color: var(--primary-color);'>$current_count</p>";
                    echo "<p>Appointments for Agent #$agent_id</p>";
                    ?>
                </div>
                
                <div>
                    <h3>Action</h3>
                    <form method="post">
                        <p style="margin-bottom: 1rem;">Click below to simulate adding a new appointment for this agent.</p>
                        <button type="submit" class="btn">Add Dummy Appointment</button>
                    </form>
                </div>
            </div>

            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // Find next ID
                $res = $conn->query("SELECT MAX(Appointment_ID) as max_id FROM Appointment");
                $row_id = $res->fetch_assoc();
                $next_id = $row_id['max_id'] + 1;
                
                $date = date('Y-m-d');
                $time = date('H:i:s');
                $customer_id = 1; 

                $sql_insert = "INSERT INTO Appointment (Appointment_ID, Appointment_Date, Appointment_Time, Customer_ID, Agent_ID) VALUES ($next_id, '$date', '$time', $customer_id, $agent_id)";
                
                if ($conn->query($sql_insert) === TRUE) {
                    echo "<div class='message success'>New appointment added successfully!</div>";
                    
                    // Get new count
                    $result = $conn->query($sql);
                    $row = $result->fetch_assoc();
                    $new_count = $row['appointment_count'];
                    echo "<div class='message'>New Appointment Count for Agent $agent_id: <strong>$new_count</strong></div>";
                } else {
                    echo "<div class='message error'>Error: " . $conn->error . "</div>";
                }
            }
            ?>
        </div>
    </div>
</body>
</html>
