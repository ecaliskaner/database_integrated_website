<?php include 'mongo_config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <a href="index.php" class="navbar-brand">
            🛡️ Admin Panel
        </a>
        <div class="nav-links">
            <span style="color: #cbd5e1; font-size: 0.9rem;">Logged in as Admin</span>
        </div>
    </nav>

    <div class="container container-admin">
        <header class="page-header">
            <h1 class="page-title">Support Ticket Management</h1>
            <p class="page-subtitle">View and resolve incoming support requests</p>
        </header>
        
        <div class="card">
            <?php
            // Get all active tickets
            $filter = ['status' => true];
            $query = new MongoDB\Driver\Query($filter);
            $cursor = $manager->executeQuery($namespace, $query);
            $tickets = $cursor->toArray();
            ?>

            <?php if (count($tickets) > 0): ?>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Created At</th>
                                <th>Message Preview</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tickets as $ticket): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($ticket->username); ?></strong></td>
                                    <td><?php echo $ticket->created_at; ?></td>
                                    <td><?php echo htmlspecialchars(substr($ticket->message, 0, 60)); ?>...</td>
                                    <td>
                                        <a href="ticket_detail.php?id=<?php echo $ticket->_id; ?>" class="btn">Manage</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div style="text-align: center; padding: 3rem;">
                    <p style="font-size: 1.2rem; margin-bottom: 1rem;">🎉 All caught up!</p>
                    <p style="color: var(--secondary-color);">No active support tickets found.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
