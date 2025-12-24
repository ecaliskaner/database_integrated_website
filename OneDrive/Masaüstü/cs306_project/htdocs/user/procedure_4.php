<?php include 'db_config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Procedure 4: Schedule Appointment</title>
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
        <div class="card" style="max-width: 600px; margin: 0 auto;">
            <header class="page-header">
                <h1 class="page-title">Schedule Appointment</h1>
                <p class="page-subtitle">Responsible: Emre Yontucu</p>
            </header>

            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $customer_id = $_POST['customer_id'];
                $agent_id = $_POST['agent_id'];
                $date = "'" . $_POST['date'] . "'";
                $time = "'" . $_POST['time'] . "'";

                $sql = "CALL sp_schedule_appointment($customer_id, $agent_id, $date, $time)";
                
                try {
                    if ($conn->query($sql) === TRUE) {
                        echo "<div class='message success'>Appointment Scheduled Successfully!</div>";
                    } else {
                        echo "<div class='message error'>Error: " . $conn->error . "</div>";
                    }
                } catch (Exception $e) {
                    echo "<div class='message error'>Error: " . $e->getMessage() . "</div>";
                }
            }
            ?>

            <form method="post">
                <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label>Customer ID</label>
                        <input type="number" name="customer_id" required value="1">
                    </div>
                    <div class="form-group">
                        <label>Agent ID</label>
                        <input type="number" name="agent_id" required value="1">
                    </div>
                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" name="date" required value="<?php echo date('Y-m-d'); ?>">
                    </div>
                    <div class="form-group">
                        <label>Time</label>
                        <input type="time" name="time" required value="10:00">
                    </div>
                </div>
                <button type="submit" class="btn btn-block" style="margin-top: 1rem;">Confirm Schedule</button>
            </form>
        </div>
    </div>
</body>
</html>
