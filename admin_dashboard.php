<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include 'includes/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - 911 Emergency System</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        /* Basic styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
        }
        /* Header Style */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #6d5dfc;
            color: white;
            position: fixed;
            top: 0;
            left: 201px;
            right: 0;
            z-index: 10;
        }
        .header h1 {
            margin: 0;
        }
        .side-panel {
            width: 200px;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            background-color: #343a40;
            color: white;
            padding-top: 20px;
            z-index: 5;
        }
        .side-panel .logo {
            display: block;
            margin: 0 auto;
            width: 100px;
            height: 100px;
            background-color: white;
            border-radius: 50%;
        }
        .side-panel ul {
            list-style: none;
            padding: 0;
            margin: 20px 0;
        }
        .side-panel ul li {
            padding: 10px;
            text-align: center;
        }
        .side-panel ul li a {
            color: white;
            text-decoration: none;
            font-size: 16px;
        }
        /* Content Adjustment */
        .content {
            margin-left: 220px; /* Adjust for side panel width */
            margin-top: 70px; /* Adjust for fixed header */
            padding: 20px;
        }
        .modal-header {
            background-color: #6d5dfc;
            color: white;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>911 Emergency System</h1>
        <div class="dropdown">
            <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <?= $_SESSION['full_name'] ?>
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </div>

    <!-- Side Panel -->
    <div class="side-panel">
        <img src="assets/images/911 Official Logo.webp" alt="Logo" class="logo">
        <ul>
            <li><a href="admin_dashboard.php">Dashboard</a></li>
            <li><a href="#callLogSection">Call Logs</a></li>
            <li><a href="#reportsSection">Reports</a></li>
            <li><a href="admin-settings.php">Settings</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="content">
        <h2>Admin Dashboard</h2>
        <div id="callLogSection">
            <h3>Call Logs</h3>
            <table id="callLogTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Agent</th>
                        <th>Type of Service</th>
                        <th>Call Type</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Contact Number</th>
                        <th>Count</th>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Location</th>
                        <th>Reason of Call</th>
                        <th>Actions Taken</th>
                        <th>Remarks</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Populate with PHP/Database -->
                </tbody>
            </table>
        </div>

        <div id="reportsSection">
            <h3>Reports</h3>
            <button class="btn btn-primary" onclick="generateReport('call_log')">Generate Call Log Report</button>
            <button class="btn btn-primary" onclick="generateReport('ecsm')">Generate ECSM Report</button>
        </div>

        
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#callLogTable').DataTable();
        });

        function generateReport(type) {
            alert("Generating " + type + " report...");
        }
    </script>
</body>
</html>
