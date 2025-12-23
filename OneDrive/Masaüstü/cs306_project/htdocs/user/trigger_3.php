<?php include 'db_config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Trigger 3: After Offer Accepted</title>
    <style> body { font-family: Arial, sans-serif; margin: 20px; } .message { margin: 10px 0; padding: 10px; border: 1px solid #ccc; background: #f9f9f9; } </style>
</head>
<body>
    <h1>Trigger 3: After Offer Accepted</h1>
    <p>Responsible: Doruk Kocaman</p>
    <p>When an offer is accepted, the property status automatically changes to 'Sold'.</p>
    <a href="index.php">Back to Home</a>

    <?php
    $offer_id = 1; // Using Offer 1 for demo
    
    // Reset for demo purposes (optional, but good for testing repeatedly)
    if (isset($_POST['reset'])) {
        $conn->query("UPDATE Offer SET Status = 'Pending' WHERE Offer_ID = $offer_id");
        $conn->query("UPDATE Property SET Status = 'Available' WHERE Property_ID = (SELECT Property_ID FROM Offer WHERE Offer_ID = $offer_id)");
        echo "<div class='message'>Reset to initial state.</div>";
    }

    if (isset($_POST['accept'])) {
        $sql_update = "UPDATE Offer SET Status = 'Accepted' WHERE Offer_ID = $offer_id";
        if ($conn->query($sql_update) === TRUE) {
            echo "<div class='message' style='color:green'>Offer Accepted!</div>";
        } else {
            echo "<div class='message' style='color:red'>Error: " . $conn->error . "</div>";
        }
    }

    // Display current state
    $sql = "SELECT o.Offer_ID, o.Status as OfferStatus, p.Property_ID, p.Status as PropertyStatus 
            FROM Offer o 
            JOIN Property p ON o.Property_ID = p.Property_ID 
            WHERE o.Offer_ID = $offer_id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    ?>

    <div class="message">
        <p><strong>Offer ID:</strong> <?php echo $row['Offer_ID']; ?></p>
        <p><strong>Offer Status:</strong> <?php echo $row['OfferStatus']; ?></p>
        <p><strong>Property ID:</strong> <?php echo $row['Property_ID']; ?></p>
        <p><strong>Property Status:</strong> <?php echo $row['PropertyStatus']; ?></p>
    </div>

    <form method="post">
        <button type="submit" name="accept">Accept Offer</button>
        <button type="submit" name="reset">Reset Demo</button>
    </form>
</body>
</html>
