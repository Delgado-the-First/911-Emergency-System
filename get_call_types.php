<?php
include('includes/db.php'); // Include the database connection file

if (isset($_GET['service_type_id'])) {
    $service_type_id = $_GET['service_type_id'];

    // Query to fetch call types based on the selected service type
    $query = "SELECT * FROM call_types WHERE service_type_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $service_type_id);  // Bind the service type ID
    $stmt->execute();
    $result = $stmt->get_result();

    $call_types = [];
    while ($row = $result->fetch_assoc()) {
        $call_types[] = $row;
    }

    // Return the result as a JSON object
    echo json_encode($call_types);
}
?>
