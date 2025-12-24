<?php include 'db_config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trigger 3: Offer Acceptance</title>
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
        <div class="card">
            <header class="page-header">
                <h1 class="page-title">Trigger 3: After Offer Accepted</h1>
                <p class="page-subtitle">Responsible: Doruk Kocaman</p>
            </header>

            <?php
            // Reset for demo purposes if requested
            if (isset($_POST['reset'])) {
                $offer_id = 1; // using offer 1 for demo
                $conn->query("UPDATE Offer SET Status = 'Pending' WHERE Offer_ID = $offer_id");
                $conn->query("UPDATE Property SET Status = 'Available' WHERE Property_ID = (SELECT Property_ID FROM Offer WHERE Offer_ID = $offer_id)");
                echo "<div class='message'>System Reset for Demo</div>";
            }

            $offer_id = 1; 

            // Handle Accept
            if (isset($_POST['accept'])) {
                $sql_update = "UPDATE Offer SET Status = 'Accepted' WHERE Offer_ID = $offer_id";
                if ($conn->query($sql_update) === TRUE) {
                    echo "<div class='message success'>Offer Accepted! Logic triggered.</div>";
                } else {
                    echo "<div class='message error'>Error: " . $conn->error . "</div>";
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

            <div class="grid" style="margin-bottom: 2rem;">
                <div class="card" style="background-color: #F8FAFC; border: none;">
                    <h3>Offer #<?php echo $row['Offer_ID']; ?> Status</h3>
                    <p style="font-size: 1.5rem; font-weight: bold; color: var(--primary-color);">
                        <?php echo $row['OfferStatus']; ?>
                    </p>
                </div>
                <div class="card" style="background-color: #F8FAFC; border: none;">
                    <h3>Property #<?php echo $row['Property_ID']; ?> Status</h3>
                    <p style="font-size: 1.5rem; font-weight: bold; color: var(--primary-color);">
                        <?php echo $row['PropertyStatus']; ?>
                    </p>
                </div>
            </div>

            <div style="display: flex; gap: 1rem; justify-content: center;">
                <form method="post">
                    <button type="submit" name="accept" class="btn" <?php echo ($row['OfferStatus'] == 'Accepted') ? 'disabled style="opacity:0.5"' : ''; ?>>
                        Accept Offer
                    </button>
                </form>

                <form method="post">
                    <button type="submit" name="reset" class="btn" style="background-color: var(--secondary-color);">
                        Reset Demo
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
