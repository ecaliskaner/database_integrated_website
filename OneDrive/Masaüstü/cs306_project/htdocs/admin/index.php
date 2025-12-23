<?php include 'mongo_config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <style> body { font-family: Arial, sans-serif; margin: 20px; } table { border-collapse: collapse; width: 100%; } th, td { border: 1px solid #ddd; padding: 8px; } th { background-color: #f2f2f2; } </style>
</head>
<body>
    <h1>Admin Dashboard - Support Tickets</h1>
    
    <?php
    // Get all active tickets
    $filter = ['status' => true];
    $query = new MongoDB\Driver\Query($filter);
    $cursor = $manager->executeQuery($namespace, $query);
    $tickets = $cursor->toArray();
    ?>

    <?php if (count($tickets) > 0): ?>
        <table>
            <tr>
                <th>Username</th>
                <th>Created At</th>
                <th>Message</th>
                <th>Action</th>
            </tr>
            <?php foreach ($tickets as $ticket): ?>
                <tr>
                    <td><?php echo htmlspecialchars($ticket->username); ?></td>
                    <td><?php echo $ticket->created_at; ?></td>
                    <td><?php echo htmlspecialchars(substr($ticket->message, 0, 50)); ?>...</td>
                    <td><a href="ticket_detail.php?id=<?php echo $ticket->_id; ?>">Manage</a></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No active tickets.</p>
    <?php endif; ?>
</body>
</html>
