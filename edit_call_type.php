<?php
// Include database connection
include('includes/db.php');

// Check if the 'id' parameter is passed in the URL (for identifying the record)
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Fetch existing Call Type details from the database
    $query = "SELECT * FROM call_types WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $call_type = $row['call_type'];
    } else {
        // If no record found, redirect or show an error
        echo "Call Type not found!";
        exit();
    }
} else {
    // If 'id' is not passed, redirect to the admin settings page
    header("Location: admin-settings.php?error=No ID provided.");
    exit();
}

// Check if the form is submitted to update the Call Type
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $updated_call_type = mysqli_real_escape_string($conn, $_POST['call_type']);

    // Validate input
    if (empty($updated_call_type)) {
        echo "Call Type is required!";
    } else {
        // Update the database with the new Call Type
        $update_query = "UPDATE call_types SET call_type = '$updated_call_type' WHERE id = '$id'";

        if (mysqli_query($conn, $update_query)) {
            // Success: redirect to the admin settings page with a success message
            header("Location: admin-settings.php?success=Call Type updated successfully!");
            exit();
        } else {
            // Error: show an error message
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>
