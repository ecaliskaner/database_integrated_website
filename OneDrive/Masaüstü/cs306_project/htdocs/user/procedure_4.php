<?php include 'db_config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Procedure 4: Schedule Appointment</title>
    <style> body { font-family: Arial, sans-serif; margin: 20px; } .message { margin: 10px 0; padding: 10px; border: 1px solid #ccc; background: #f9f9f9; } </style>
</head>
<body>
    <h1>Procedure 4: Schedule Appointment</h1>
    <p>Responsible: Emre Yontucu</p>
    <a href="index.php">Back to Home</a>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $customer_id = $_POST['customer_id'];
        $agent_id = $_POST['agent_id'];
        $date = "'" . $_POST['date'] . "'";
        $time = "'" . $_POST['time'] . "'";

        $sql = "CALL sp_schedule_appointment($customer_id, $agent_id, $date, $time)";
        
        try {
            if ($conn->query($sql) === TRUE) {
                echo "<div class='message' style='color:green'>Appointment Scheduled Successfully!</div>";
            } else {
                echo "<div class='message' style='color:red'>Error: " . $conn->error . "</div>";
            }
        } catch (Exception $e) {
            echo "<div class='message' style='color:red'>Error: " . $e->getMessage() . "</div>";
        }
    }
    ?>

    <form method="post">
        <label>Customer ID:</label>
        <input type="number" name="customer_id" required value="1">
        <label>Agent ID:</label>
        <input type="number" name="agent_id" required value="1">
        <label>Date:</label>
        <input type="date" name="date" required value="<?php echo date('Y-m-d'); ?>">
        <label>Time:</label>
        <input type="time" name="time" required value="10:00">
        <button type="submit">Schedule</button>
    </form>
</body>
</html>
