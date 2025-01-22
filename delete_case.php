<?php
session_start();
include('includes/db.php');

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "User not logged in.";
    exit();
}

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    
    // Sanitize the input to prevent SQL injection
    $id = mysqli_real_escape_string($conn, $id);

    // Get the user's team (you need this to get the correct table for deletion)
    $user_id = $_SESSION['user_id'];
    $query = "SELECT team FROM users WHERE id = $user_id";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        $team = $user['team'];

        // Determine the table based on the user's team
        switch ($team) {
            case 'alpha':
                $table_name = 'alpha_tbl';
                break;
            case 'bravo':
                $table_name = 'bravo_tbl';
                break;
            case 'charlie':
                $table_name = 'charlie_tbl';
                break;
            default:
                echo "Unknown team. Please contact the administrator.";
                exit();
        }

        // Delete the case from the appropriate table
        $delete_query = "DELETE FROM $table_name WHERE id = '$id'";

        if (mysqli_query($conn, $delete_query)) {
            echo "Case deleted successfully.";
        } else {
            echo "Error deleting case.";
        }
    } else {
        echo "User data not found.";
    }
} else {
    echo "No ID provided.";
}
?>
