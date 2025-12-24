<?php include 'db_config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trigger 4: Prevent Duplicate</title>
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
        <div class="card" style="max-width: 700px; margin: 0 auto;">
            <header class="page-header">
                <h1 class="page-title">Trigger 4: Prevent Duplicates</h1>
                <p class="page-subtitle">Responsible: Emre Yontucu</p>
            </header>

            <p style="text-align: center; margin-bottom: 2rem;">
                Try to book an appointment. If the Agent is already booked for that Date & Time, it will fail.
            </p>

            <?php
            $agent_id = 1; 

            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $date = $_POST['date'];
                $time = $_POST['time']; // Just HH:MM
                // Ensure time has seconds for exact match or use strict comparison
                // MySQL TIME type usually is HH:MM:SS. 
                // Let's append :00 if needed or rely on string comp.
                if(strlen($time) == 5) $time .= ":00";

                
                // Find next ID
                $res = $conn->query("SELECT MAX(Appointment_ID) as max_id FROM Appointment");
                $row_id = $res->fetch_assoc();
                $next_id = $row_id['max_id'] + 1;
                $customer_id = 1;

                $sql_insert = "INSERT INTO Appointment (Appointment_ID, Appointment_Date, Appointment_Time, Customer_ID, Agent_ID) VALUES ($next_id, '$date', '$time', $customer_id, $agent_id)";
                
                try {
                    if ($conn->query($sql_insert) === TRUE) {
                        echo "<div class='message success'>Appointment booked successfully!</div>";
                    } else {
                        echo "<div class='message error'>Error: " . $conn->error . "</div>";
                    }
                } catch (Exception $e) {
                    echo "<div class='message error'><strong>Booking Failed:</strong> " . $e->getMessage() . "</div>";
                }
            }
            ?>

            <form method="post" style="margin-bottom: 2rem;">
                <div class="grid" style="gap: 1rem;">
                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" name="date" required value="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="form-group">
                        <label>Time</label>
                        <input type="time" name="time" required value="10:00">
                    </div>
                </div>
                <button type="submit" class="btn btn-block">Book Appointment</button>
            </form>

            <h3>Existing Appointments (Agent <?php echo $agent_id; ?>)</h3>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM Appointment WHERE Agent_ID = $agent_id ORDER BY Appointment_Date DESC, Appointment_Time DESC LIMIT 5";
                        $result = $conn->query($sql);
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row['Appointment_ID'] . "</td>";
                            echo "<td>" . $row['Appointment_Date'] . "</td>";
                            echo "<td>" . $row['Appointment_Time'] . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
