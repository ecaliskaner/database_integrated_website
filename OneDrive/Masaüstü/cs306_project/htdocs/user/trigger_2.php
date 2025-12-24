<?php include 'db_config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trigger 2: Payment Validation</title>
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
                <h1 class="page-title">Trigger 2: Validate Payment</h1>
                <p class="page-subtitle">Responsible: Efe Çalışkaner</p>
            </header>

            <p style="margin-bottom: 2rem; text-align: center;">Attempts to insert a payment. The trigger will block values &le; 0.</p>

            <form method="post">
                <div class="form-group">
                    <label>Amount:</label>
                    <input type="number" name="amount" step="0.01" required placeholder="Enter amount (try -100 or 0)">
                </div>
                <button type="submit" class="btn btn-block">Process Payment</button>
            </form>

            <div style="margin-top: 2rem;">
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $amount = $_POST['amount'];
                
                // Find next ID
                $res = $conn->query("SELECT MAX(Payment_ID) as max_id FROM Payment");
                $row_id = $res->fetch_assoc();
                $next_id = $row_id['max_id'] + 1;
                
                $date = date('Y-m-d');
                $method = 'Cash';
                $owner_id = 1;

                $sql_insert = "INSERT INTO Payment (Payment_ID, Payment_Date, Payment_Method, Amount, Owner_ID) VALUES ($next_id, '$date', '$method', $amount, $owner_id)";
                
                try {
                    if ($conn->query($sql_insert) === TRUE) {
                        echo "<div class='message success'>Payment added successfully! Amount: $amount</div>";
                    } else {
                        // This block usually catches SQL errors if exceptions are off
                        echo "<div class='message error'>Error: " . $conn->error . "</div>";
                    }
                } catch (Exception $e) {
                     echo "<div class='message error'><strong>Trigger Error Caught:</strong> " . $e->getMessage() . "</div>";
                }
            }
            ?>
            </div>
        </div>
    </div>
</body>
</html>
