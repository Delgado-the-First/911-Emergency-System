<?php
session_start();
include('includes/db.php'); // Include the database connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet"> <!-- Custom Styles -->
    <style>
        /* Ensure the header does not overlap with the side panel */
        body {
            margin: 0;
            padding: 0;
        }

        /* Adjust the header's margin-bottom to add space between the header and the side panel */
        header {
            margin-bottom: 0px; /* You can adjust this value based on your design preference */
        }

        /* Adjust the left margin for the main content to account for the side panel width */
        .container-fluid {
            margin-left: 50px; /* This should match the width of your side panel */
        }

        /* Optional: Add more space to the top of the content if needed */
        .container-fluid .row {
            margin-top: 0px;
        }
        /* Adjust modal width to make it landscape */
        .modal-dialog.modal-lg {
            max-width: 80%;  /* You can adjust this percentage */
        }

        /* Add space for form fields inside the modal */
        .modal-body .row {
            margin-bottom: 15px;
        }

        /* Ensure the modal content is not too tight */
        .modal-content {
            padding: 10px;
        }

        /* Style input fields to make the layout clear */
        .modal-body input,
        .modal-body select,
        .modal-body textarea {
            margin-bottom: 10px;
        }

        /* Adjust button styling for consistency */
        .modal-footer .btn {
            padding: 10px 20px;
        }
        .side-panel .logo {
            display: block;
            margin: 0 auto;
            width: 100px;
            height: 100px;
            background-color: white;
            border-radius: 50%;
        }

    </style>
</head>
<body class="bg-light">

    <!-- Header -->
    <header class="d-flex justify-content-between align-items-center p-3 bg-primary text-white shadow-sm">
        <h1 class="h4 mb-0">User Dashboard</h1>
        <div class="dropdown">
            <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <?= $_SESSION['full_name'] ?>
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="logout.php">Logout</a></li>
            </ul>
        </div>
    </header>

    <!-- Main Content -->
    <div class="d-flex">
        <!-- Side Panel -->
        <div class="bg-dark text-white p-4 shadow-lg side-panel" style="width: 250px; height: 100vh;">
            <div class="text-center mb-4">
                <img src="assets/images/911 Official Logo.webp" alt="Logo" class="logo" >
            </div>
            <ul class="nav flex-column">
                <li class="nav-item mb-3">
                    <a class="nav-link text-white" href="user_dashboard.php"><i class="bi bi-house-door"></i> Dashboard</a>
                </li>
                <li class="nav-item mb-3">
                    <a class="nav-link text-white" href="user_call_log.php"><i class="bi bi-file-earmark-medical"></i> Call Logs</a>
                </li>
                <li class="nav-item mb-3">
                    <a class="nav-link text-white" href="user_feedback.php"><i class="bi bi-pencil-square"></i> Feedback</a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="container-fluid p-4">
            <div class="row mb-4">
                <!-- Total Calls Summary Cards -->
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <h5 class="card-title text-primary">Total Calls Today</h5>
                            <p class="h3" id="totalCallsToday">0</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <h5 class="card-title text-primary">Total Calls This Month</h5>
                            <p class="h3" id="totalCallsMonth">0</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body text-center">
                            <h5 class="card-title text-primary">Total Calls This Year</h5>
                            <p class="h3" id="totalCallsYear">0</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Call Log Data Table -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-4">Call Log</h5>
                    <!-- Add New Log Button -->
                    <div class="d-flex justify-content-between align-items-center">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLogModal">
                            <i class="bi bi-plus-circle"></i> Add New Log
                        </button>

                        <div class="mb-3">
                            <label for="callTypeFilter" class="form-label">Filter by Call Type:</label>
                            <select id="callTypeFilter" class="form-control">
                                <option value="">All</option>
                                <?php
                                // Fetch all call types for the filter dropdown
                                $call_types_result = mysqli_query($conn, "SELECT * FROM call_types");
                                while ($row = mysqli_fetch_assoc($call_types_result)) {
                                    echo "<option value='{$row['id']}'>{$row['call_type']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>


                    <table class="table table-bordered table-striped" id="callLogTable">
                        <thead class="table-dark">
                            <tr>
                                <th>No.</th>
                                <th>Type of Service</th>
                                <th>Call Type</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Contact Number</th>
                                <th>Count</th>
                                <th>Name</th>
                                <th>Age</th>
                                <th>Location</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                                // Fetch call log data with JOIN to get Service Type and Call Type names
                                $query = "
                                        SELECT cl.id, st.service_type, ct.call_type, cl.call_date, cl.call_time, cl.contact_number, cl.call_count, cl.name, cl.age, cl.location
                                        FROM call_logs cl
                                        JOIN service_types st ON cl.type_of_service = st.id  
                                        JOIN call_types ct ON st.call_type_id = ct.id        
                                    ";
                                    $result = mysqli_query($conn, $query);

                                    // Check if records exist
                                    if (mysqli_num_rows($result) > 0) {
                                        $no = 1; // Row numbering
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            echo "<tr>
                                                    <td>{$no}</td>
                                                    <td>{$row['service_type']}</td>
                                                    <td>{$row['call_type']}</td>
                                                    <td>{$row['call_date']}</td>
                                                    <td>{$row['call_time']}</td> 
                                                    <td>{$row['contact_number']}</td>
                                                    <td>{$row['call_count']}</td> 
                                                    <td>{$row['name']}</td>
                                                    <td>{$row['age']}</td>
                                                    <td>{$row['location']}</td>
                                                    <td>
                                                        <button class='btn btn-info btn-sm'>Edit</button>
                                                        <button class='btn btn-danger btn-sm'>Delete</button>
                                                    </td>
                                                </tr>";
                                            $no++;
                                        }
                                    } else {
                                        echo "<tr><td colspan='11' class='text-center'>No records found</td></tr>";
                                    }
                                ?>

                        </tbody>
                    </table>
                </div>
            </div>

          

  
<!-- Add New Log Modal -->
            <div class="modal fade" id="addLogModal" tabindex="-1" aria-labelledby="addLogModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addLogModalLabel">Add New Call Log</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="logForm" method="post" action="process_add_log.php">
                              
                                <!-- Service Type Dropdown -->
                                <div class="mb-3">
                                <label for="service_type">Service Type</label>
                                    <select id="service_type" name="service_type" class="form-control" required>
                                        <option value="">Select Service Type</option>
                                        <?php
                                        // Fetch service types from the database
                                        $service_types_result = mysqli_query($conn, "SELECT * FROM service_types");
                                        while ($row = mysqli_fetch_assoc($service_types_result)) {
                                            echo "<option value='{$row['id']}'>{$row['service_type']}</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <!-- Call Type Dropdown -->
                                <div class="mb-3">
                                    <label for="call_type">Call Type</label>
                                    <select id="call_type" name="call_type" class="form-control" required>
                                        <option value="">Select Call Type</option>
                                    </select>
                                </div>

                                <!-- Other Form Fields -->
                                <div class="mb-3">
                                    <label for="contactNumber" class="form-label">Contact Number</label>
                                    <input type="text" class="form-control" id="contactNumber" name="contactNumber" required>
                                </div>
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="age" class="form-label">Age</label>
                                    <input type="number" class="form-control" id="age" name="age" required>
                                </div>
                                <div class="mb-3">
                                    <label for="location" class="form-label">Location</label>
                                    <input type="text" class="form-control" id="location" name="location" required>
                                </div>
                                <div class="mb-3">
                                    <button type="submit" class="btn btn-primary">Save Log</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Client Feedback Section -->
            <div class="text-center">
                            <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#feedbackModal">
                                <i class="bi bi-pencil-square"></i> Submit Feedback
                            </button>
                        </div>
                    </div>
                </div>

    <!-- Bootstrap JS and necessary Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Additional JS for Dynamic Call Type Dropdown and Feedback Submission -->
    <script>
                // Handle Feedback Form Submission
            document.getElementById('feedbackForm').addEventListener('submit', function (e) {
                e.preventDefault();
                // Send form data to the server via AJAX or other methods
                // For now, just display a confirmation
                alert('Thank you for your feedback!');
                $('#feedbackModal').modal('hide');
            });
      


            // Dynamically update Call Type options based on selected Service Type
            document.getElementById('service_type').addEventListener('change', function() {
                var serviceTypeId = this.value;
                var callTypeSelect = document.getElementById('call_type');
                
                // Clear existing Call Type options
                callTypeSelect.innerHTML = '<option value="">Select Call Type</option>';

                // Fetch Call Types based on selected Service Type
                if (serviceTypeId) {
                    fetch('get_call_types.php?service_type_id=' + serviceTypeId)
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(callType => {
                                var option = document.createElement('option');
                                option.value = callType.id;
                                option.textContent = callType.call_type;
                                callTypeSelect.appendChild(option);
                            });
                        });
                }
            });
    </script>
</body>
</html>
