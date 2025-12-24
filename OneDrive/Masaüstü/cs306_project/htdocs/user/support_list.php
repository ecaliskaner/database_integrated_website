<?php include 'mongo_config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support Tickets</title>
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
                <h1 class="page-title">Support Ticket System</h1>
                <p class="page-subtitle">Track your support requests or submit a new one</p>
            </header>

            <div style="text-align: right; margin-bottom: 2rem;">
                <a href="support_create.php" class="btn">＋ Create New Ticket</a>
            </div>

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

            <form method="get" class="card" style="background-color: #F8FAFC; max-width: 400px; margin: 0 auto 2rem auto; border: none;">
                <div class="form-group" style="margin-bottom: 0;">
                    <label>Filter by User</label>
                    <select name="username" onchange="this.form.submit()">
                        <option value="">-- Select User --</option>
                        <?php foreach ($users as $user): ?>
                            <option value="<?php echo htmlspecialchars($user); ?>" <?php if(isset($_GET['username']) && $_GET['username'] == $user) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($user); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>

            <?php
            if (isset($_GET['username']) && !empty($_GET['username'])) {
                $selected_user = $_GET['username'];
                echo "<h3 style='margin-bottom:1rem;'>Tickets for <span style='color:var(--primary-color)'>$selected_user</span></h3>";
                
                // Filter tickets for this user
                $user_tickets = [];
                foreach ($all_tickets as $ticket) {
                    if ($ticket->username == $selected_user) {
                        $user_tickets[] = $ticket;
                    }
                }

                if (count($user_tickets) > 0) {
                    echo "<div class='table-responsive'><table><thead><tr><th>Created At</th><th>Message</th><th>Status</th><th>Details</th></tr></thead><tbody>";
                    foreach ($user_tickets as $ticket) {
                        echo "<tr>";
                        echo "<td>" . $ticket->created_at . "</td>";
                        echo "<td>" . htmlspecialchars(substr($ticket->message, 0, 50)) . "...</td>";
                        echo "<td>" . ($ticket->status ? '<span style="color:var(--success-color)">Active</span>' : 'Resolved') . "</td>";
                        echo "<td><a href='support_detail.php?id=" . $ticket->_id . "' class='btn' style='padding:0.25rem 0.5rem; font-size:0.8rem;'>View</a></td>";
                        echo "</tr>";
                    }
                    echo "</tbody></table></div>";
                } else {
                    echo "<p class='message'>No active tickets found for this user.</p>";
                }
            } elseif (empty($all_tickets)) {
                 echo "<p class='message'>No active tickets in the system.</p>";
            } else {
                 echo "<p class='message' style='text-align:center;'>Please select a user from the dropdown above to view their tickets.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>
