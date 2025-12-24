<?php include 'mongo_config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Ticket Detail</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <a href="index.php" class="navbar-brand">🛡️ Admin Panel</a>
        <div class="nav-links">
            <a href="index.php">Dashboard</a>
        </div>
    </nav>

    <div class="container container-admin">
        <?php
        if (!isset($_GET['id'])) {
            echo "<div class='message error'>No ticket ID specified.</div>";
            exit;
        }

        $id = new MongoDB\BSON\ObjectId($_GET['id']);
        
        // Handle Actions
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['resolve'])) {
                $bulk = new MongoDB\Driver\BulkWrite;
                $bulk->update(
                    ['_id' => $id],
                    ['$set' => ['status' => false]]
                );
                $manager->executeBulkWrite($namespace, $bulk);
                echo "<div class='message success'>Ticket marked as Resolved!</div>";
                // Redirect or refresh?
                // header("Location: index.php"); // Optional
            } elseif (isset($_POST['comment'])) {
                $new_comment = "Admin: " . $_POST['comment_text'];
                $bulk = new MongoDB\Driver\BulkWrite;
                $bulk->update(
                    ['_id' => $id],
                    ['$push' => ['comments' => $new_comment]]
                );
                $manager->executeBulkWrite($namespace, $bulk);
            }
        }

        // Fetch Ticket
        $filter = ['_id' => $id];
        $query = new MongoDB\Driver\Query($filter);
        $cursor = $manager->executeQuery($namespace, $query);
        $tickets = $cursor->toArray();

        if (count($tickets) == 0) {
            echo "<div class='message error'>Ticket not found.</div>";
            exit;
        }
        $ticket = $tickets[0];
        ?>

        <div style="margin-bottom: 2rem;">
            <a href="index.php" style="color: var(--secondary-color); text-decoration: none;">&larr; Back to Dashboard</a>
        </div>

        <div class="grid" style="gap: 2rem;">
            <div>
                <div class="card" style="margin-bottom: 2rem;">
                    <div style="border-bottom: 1px solid var(--border-color); padding-bottom: 1rem; margin-bottom: 1rem; display: flex; justify-content: space-between; align-items: center;">
                        <h2 style="color: var(--primary-color);">Manage Ticket</h2>
                        <?php echo $ticket->status ? '<span class="status-badge" style="background:#DCFCE7; color:#166534; padding:0.25rem 0.5rem; border-radius:1rem; font-size:0.8rem; font-weight:bold;">Active</span>' : '<span style="color:#94a3b8; font-weight:bold;">Resolved</span>'; ?>
                    </div>
                    
                    <div style="margin-bottom: 1.5rem;">
                        <label style="color: var(--secondary-color); font-size: 0.85rem;">USER</label>
                        <p style="font-weight: 500;"><?php echo htmlspecialchars($ticket->username); ?></p>
                    </div>

                    <div style="margin-bottom: 1.5rem;">
                        <label style="color: var(--secondary-color); font-size: 0.85rem;">MESSAGE</label>
                        <p style="background: #F8FAFC; padding: 1rem; border-radius: 0.5rem; border: 1px solid var(--border-color);">
                            <?php echo nl2br(htmlspecialchars($ticket->message)); ?>
                        </p>
                    </div>
                </div>

                <h3>Discussion History</h3>
                <div class="card" style="margin-bottom: 2rem;">
                    <?php
                    if (isset($ticket->comments) && is_array($ticket->comments) && count($ticket->comments) > 0) {
                        foreach ($ticket->comments as $comment) {
                            $is_admin = strpos($comment, 'Admin:') === 0;
                            echo "<div class='comment' style='" . ($is_admin ? "background:#F1F5F9; padding:0.5rem; border-radius:0.3rem;" : "") . "'>" . htmlspecialchars($comment) . "</div>";
                        }
                    } else {
                        echo "<p style='color:var(--text-muted);'>No comments yet.</p>";
                    }
                    ?>
                </div>

                <?php if ($ticket->status): ?>
                <div class="card" style="border-top: 4px solid var(--primary-color);">
                    <h3>Admin Actions</h3>
                    <form method="post" style="margin-bottom: 2rem;">
                        <div class="form-group">
                            <label>Add Internal Comment / Reply</label>
                            <textarea name="comment_text" rows="3" placeholder="Write admin notice here..."></textarea>
                        </div>
                        <button type="submit" name="comment" class="btn">Add Comment</button>
                    </form>

                    <div style="border-top: 1px solid var(--border-color); padding-top: 1.5rem;">
                        <p style="margin-bottom: 1rem; color: var(--secondary-color);">Issue resolved?</p>
                        <form method="post">
                            <button type="submit" name="resolve" class="btn btn-danger">Mark as Resolved & Close</button>
                        </form>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
