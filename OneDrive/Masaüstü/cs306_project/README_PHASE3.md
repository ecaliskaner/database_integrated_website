# CS306 Project Phase 3 Implementation

This folder contains the implementation for Phase 3 of the CS306 Project.

## Prerequisites
1.  **XAMPP** installed with Apache and MySQL.
2.  **MongoDB** installed and running.
3.  **MongoDB PHP Driver** installed and enabled in `php.ini` (`extension=mongodb`).

## Setup Instructions

### 1. Database Setup
1.  Open **phpMyAdmin** (`http://localhost/phpmyadmin`).
2.  Import the `cs306_project_phase3.sql` file located in this directory.
    *   This will create the `cs306_project` database, tables, data, triggers, and stored procedures.

### 2. Web Application Setup
1.  Copy the `htdocs` folder from this directory to your XAMPP installation directory (usually `C:\xampp\htdocs`).
    *   You should end up with `C:\xampp\htdocs\user` and `C:\xampp\htdocs\admin`.
2.  Start Apache and MySQL in XAMPP Control Panel.

### 3. Running the Application
*   **User Interface**: Open your browser and go to `http://localhost/user`.
*   **Admin Interface**: Open your browser and go to `http://localhost/admin`.

## Features Implemented
*   **Triggers**:
    *   Trigger 1: Increment Agent Appointment Count (`trigger_1.php`)
    *   Trigger 2: Validate Payment Amount (`trigger_2.php`)
    *   Trigger 3: After Offer Accepted (`trigger_3.php`)
    *   Trigger 4: Prevent Duplicate Appointments (`trigger_4.php`)
*   **Stored Procedures**:
    *   Procedure 1: Search Available Properties (`procedure_1.php`)
    *   Procedure 2: Generate Agent Performance Report (`procedure_2.php`)
    *   Procedure 3: Get Available Properties By City (`procedure_3.php`)
    *   Procedure 4: Schedule Appointment (`procedure_4.php`)
*   **Support Ticket System**:
    *   User: Create and view tickets (`support_list.php`, `support_create.php`).
    *   Admin: View and resolve tickets (`index.php`, `ticket_detail.php`).
