<?php include 'mongo_config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Support Ticket List</title>
    <style> body { font-family: Arial, sans-serif; margin: 20px; } table { border-collapse: collapse; width: 100%; } th, td { border: 1px solid #ddd; padding: 8px; } th { background-color: #f2f2f2; } </style>
</head>
<body>
    <h1>Support Ticket List</h1>
    <a href="index.php">Back to Home</a> | <a href="support_create.php">Create New Ticket</a>

    <?php
    // Get all active tickets to find users
    $filter = ['status' => true];
    $query = new MongoDB\Driver\Query($filter);
    $cursor = $manager->executeQuery($namespace, $query);
    $all_tickets = $cursor->toArray();
    
    // Extract unique usernames
    $users = [];
    foreach ($all_tickets as $ticket) {
        if (isset($ticket->username)) {
            $users[] = $ticket->username;
        }
    }
    $users = array_unique($users);
    ?>

    <form method="get">
        <label>Select User:</label>
        <select name="username" onchange="this.form.submit()">
            <option value="">-- Select User --</option>
            <?php foreach ($users as $user): ?>
                <option value="<?php echo htmlspecialchars($user); ?>" <?php if(isset($_GET['username']) && $_GET['username'] == $user) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($user); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <?php
    if (isset($_GET['username']) && !empty($_GET['username'])) {
        $selected_user = $_GET['username'];
        echo "<h3>Tickets for $selected_user:</h3>";
        
        // Filter tickets for this user
        $user_tickets = [];
        foreach ($all_tickets as $ticket) {
            if ($ticket->username == $selected_user) {
                $user_tickets[] = $ticket;
            }
        }

        if (count($user_tickets) > 0) {
            echo "<table><tr><th>Created At</th><th>Message</th><th>Status</th><th>Details</th></tr>";
            foreach ($user_tickets as $ticket) {
                echo "<tr>";
                echo "<td>" . $ticket->created_at . "</td>";
                echo "<td>" . htmlspecialchars(substr($ticket->message, 0, 50)) . "...</td>";
                echo "<td>" . ($ticket->status ? 'Active' : 'Resolved') . "</td>";
                echo "<td><a href='support_detail.php?id=" . $ticket->_id . "'>View Details</a></td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No active tickets found for this user.</p>";
        }
    } elseif (empty($all_tickets)) {
         echo "<p>No active tickets in the system.</p>";
    }
    ?>
</body>
</html>
