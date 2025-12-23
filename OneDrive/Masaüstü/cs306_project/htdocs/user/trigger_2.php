<?php include 'db_config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Trigger 2: Validate Payment Amount</title>
    <style> body { font-family: Arial, sans-serif; margin: 20px; } .message { margin: 10px 0; padding: 10px; border: 1px solid #ccc; background: #f9f9f9; } </style>
</head>
<body>
    <h1>Trigger 2: Validate Payment Amount</h1>
    <p>Responsible: Efe Çalışkaner</p>
    <p>This trigger prevents negative or zero payment amounts.</p>
    <a href="index.php">Back to Home</a>

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
                echo "<div class='message' style='color:green'>Payment added successfully! Amount: $amount</div>";
            } else {
                echo "<div class='message' style='color:red'>Error: " . $conn->error . "</div>";
            }
        } catch (Exception $e) {
             echo "<div class='message' style='color:red'>Caught Exception: " . $e->getMessage() . "</div>";
        }
    }
    ?>

    <form method="post">
        <label>Enter Payment Amount:</label>
        <input type="number" name="amount" step="0.01" required>
        <button type="submit">Submit Payment</button>
    </form>
    
    <p>Try entering -100 or 0 to see the error.</p>
</body>
</html>
