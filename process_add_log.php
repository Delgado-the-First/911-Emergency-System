<?php
include('includes/db.php'); // Include the database connection

// Check if the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and get form inputs
    $serviceType = isset($_POST['serviceType']) ? mysqli_real_escape_string($conn, $_POST['serviceType']) : '';
    $callType = isset($_POST['callType']) ? mysqli_real_escape_string($conn, $_POST['callType']) : '';
    $contactNumber = isset($_POST['contactNumber']) ? mysqli_real_escape_string($conn, $_POST['contactNumber']) : '';
    $name = isset($_POST['name']) ? mysqli_real_escape_string($conn, $_POST['name']) : '';
    $age = isset($_POST['age']) ? mysqli_real_escape_string($conn, $_POST['age']) : '';
    $location = isset($_POST['location']) ? mysqli_real_escape_string($conn, $_POST['location']) : '';

    // Validate inputs
    if (empty($serviceType) || empty($callType) || empty($contactNumber) || empty($name) || empty($age) || empty($location)) {
        // Return an error if any required field is missing
        echo "All fields are required!";
        exit;
    }

    // Prepare the SQL query to insert the new log into the database
    $query = "INSERT INTO call_logs (service_type_id, call_type_id, contact_number, name, age, location) 
              VALUES ('$serviceType', '$callType', '$contactNumber', '$name', '$age', '$location')";

    // Execute the query
    if (mysqli_query($conn, $query)) {
        // Redirect to the dashboard or another page upon success
        header('Location: user_dashboard.php'); // Adjust with the correct path
        exit;
    } else {
        // Error handling if the query fails
        echo "Error: " . mysqli_error($conn);
    }
} else {
    // If the form is not submitted via POST
    echo "Invalid request.";
}
?>
