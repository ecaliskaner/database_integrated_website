<?php include 'db_config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Trigger 4: Prevent Duplicate Appointments</title>
    <style> body { font-family: Arial, sans-serif; margin: 20px; } .message { margin: 10px 0; padding: 10px; border: 1px solid #ccc; background: #f9f9f9; } </style>
</head>
<body>
    <h1>Trigger 4: Prevent Duplicate Appointments</h1>
    <p>Responsible: Emre Yontucu</p>
    <p>Prevents booking an agent for the same date and time twice.</p>
    <a href="index.php">Back to Home</a>

    <?php
    $agent_id = 1;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $date = $_POST['date'];
        $time = $_POST['time'];
        
        // Find next ID
        $res = $conn->query("SELECT MAX(Appointment_ID) as max_id FROM Appointment");
        $row_id = $res->fetch_assoc();
        $next_id = $row_id['max_id'] + 1;
        $customer_id = 1;

        $sql_insert = "INSERT INTO Appointment (Appointment_ID, Appointment_Date, Appointment_Time, Customer_ID, Agent_ID) VALUES ($next_id, '$date', '$time', $customer_id, $agent_id)";
        
        try {
            if ($conn->query($sql_insert) === TRUE) {
                echo "<div class='message' style='color:green'>Appointment booked successfully!</div>";
            } else {
                echo "<div class='message' style='color:red'>Error: " . $conn->error . "</div>";
            }
        } catch (Exception $e) {
            echo "<div class='message' style='color:red'>Caught Exception: " . $e->getMessage() . "</div>";
        }
    }

    // Show existing appointments
    $sql = "SELECT * FROM Appointment WHERE Agent_ID = $agent_id ORDER BY Appointment_Date, Appointment_Time";
    $result = $conn->query($sql);
    ?>

    <h3>Existing Appointments for Agent <?php echo $agent_id; ?>:</h3>
    <ul>
        <?php while($row = $result->fetch_assoc()) {
            echo "<li>" . $row['Appointment_Date'] . " at " . $row['Appointment_Time'] . "</li>";
        } ?>
    </ul>

    <h3>Book New Appointment</h3>
    <form method="post">
        <label>Date:</label>
        <input type="date" name="date" required value="<?php echo date('Y-m-d'); ?>">
        <label>Time:</label>
        <input type="time" name="time" required value="10:00">
        <button type="submit">Book</button>
    </form>
    <p>Try booking a time that already exists in the list above.</p>
</body>
</html>
