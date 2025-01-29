<?php
// Database connection
$servername = "localhost";
$username = "root"; // Default username for XAMPP
$password = ""; // Default password for XAMPP
$dbname = "sports_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the search term
$searchTerm = isset($_GET['searchTerm']) ? $_GET['searchTerm'] : '';

// Prepare the SQL query to search based on the search term
$sql = "SELECT * FROM Coach WHERE name LIKE '%$searchTerm%' OR NIC LIKE '%$searchTerm%'";

// Execute the query
$result = $conn->query($sql);

// Prepare an array to hold the response data
$coaches = [];

if ($result->num_rows > 0) {
    // Fetch the data and store it in the coaches array
    while ($row = $result->fetch_assoc()) {
        $coaches[] = $row;
    }
}

// Return the results as a JSON-encoded array
echo json_encode($coaches);

// Close the connection
$conn->close();
?>
