<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Real Estate Management</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <a href="index.php" class="navbar-brand">
             Real Estate System
        </a>
        <div class="nav-links">
            <a href="index.php">Dashboard</a>
            <a href="support_list.php">Support</a>
        </div>
    </nav>

    <div class="container">
        <header class="page-header">
            <h1 class="page-title">Welcome Back</h1>
            <p class="page-subtitle">Select an action below to manage the real estate system</p>
        </header>

        <section class="section">
            <h2 style="margin-bottom: 1rem; color: var(--secondary-color);">Database Triggers</h2>
            <div class="grid">
                <!-- Trigger 1 -->
                <article class="card">
                    <h3>Appointment Tracker</h3>
                    <p class="card-content">Automatically increments agent appointment count when a new appointment is booked.</p>
                    <a href="trigger_1.php" class="btn btn-block">Test Trigger 1</a>
                </article>

                <!-- Trigger 2 -->
                <article class="card">
                    <h3>Payment Validation</h3>
                    <p class="card-content">Ensures payment amounts are valid (greater than zero) before processing.</p>
                    <a href="trigger_2.php" class="btn btn-block">Test Trigger 2</a>
                </article>

                <!-- Trigger 3 -->
                <article class="card">
                    <h3>Offer Acceptance</h3>
                    <p class="card-content">Updates property status to 'Sold' automatically when an offer is accepted.</p>
                    <a href="trigger_3.php" class="btn btn-block">Test Trigger 3</a>
                </article>

                <!-- Trigger 4 -->
                <article class="card">
                    <h3>Duplicate Prevention</h3>
                    <p class="card-content">Prevents booking conflicting appointments for the same agent at the same time.</p>
                    <a href="trigger_4.php" class="btn btn-block">Test Trigger 4</a>
                </article>
            </div>
        </section>

        <section class="section" style="margin-top: 3rem;">
            <h2 style="margin-bottom: 1rem; color: var(--secondary-color);">Stored Procedures</h2>
            <div class="grid">
                <!-- Procedure 1 -->
                <article class="card">
                    <h3>Search Properties</h3>
                    <p class="card-content">Advanced search for available properties by type, price range, and city.</p>
                    <a href="procedure_1.php" class="btn btn-block">Run Procedure 1</a>
                </article>

                <!-- Procedure 2 -->
                <article class="card">
                    <h3>Performance Reports</h3>
                    <p class="card-content">Generate detailed performance reports for agents listing sales and activities.</p>
                    <a href="procedure_2.php" class="btn btn-block">Run Procedure 2</a>
                </article>

                <!-- Procedure 3 -->
                <article class="card">
                    <h3>City Listings</h3>
                    <p class="card-content">Quickly retrieve all available properties in a specific city.</p>
                    <a href="procedure_3.php" class="btn btn-block">Run Procedure 3</a>
                </article>

                <!-- Procedure 4 -->
                <article class="card">
                    <h3>Schedule Booking</h3>
                    <p class="card-content">Official procedure to schedule appointments with built-in validation.</p>
                    <a href="procedure_4.php" class="btn btn-block">Run Procedure 4</a>
                </article>
            </div>
        </section>

        <section class="section" style="margin-top: 3rem;">
            <h2 style="margin-bottom: 1rem; color: var(--secondary-color);">Support & Help</h2>
            <div class="grid">
                <article class="card">
                    <h3>Support Tickets</h3>
                    <p class="card-content">View status of existing tickets or submit a new request.</p>
                    <a href="support_list.php" class="btn btn-block">Go to Support</a>
                </article>
            </div>
        </section>
    </div>

    <footer>
        <p>&copy; 2025 CS306 Project Team. All rights reserved.</p>
    </footer>
</body>
</html>
