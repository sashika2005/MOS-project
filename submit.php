<?php
$servername = "localhost";
$username = "root"; // Default username for XAMPP
$password = ""; // Default password for XAMPP
$dbname = "sports_db";

// Database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = $_POST['name'];
    $service_place = $_POST['work_place']; // Correct field name from HTML
    $nic = $_POST['NIC'];
    $telephone = $_POST['tel'];
    $district = $_POST['district'];
    $post = $_POST['post'];

    // Validate inputs to prevent empty fields
    if (empty($name) || empty($service_place) || empty($nic) || empty($telephone)) {
        echo "<script>alert('All fields are required!'); window.history.back();</script>";
        exit();
    }

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO Coach (name, work_place, NIC, tel, district, post) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $service_place, $nic, $telephone, $district, $post);

    // Execute and check if inserted
    if ($stmt->execute()) {
        echo "<script>alert('Registration successful!'); window.location.href='displaytable.php';</script>";
    } else {
        echo "<script>alert('Error: Could not register.'); window.history.back();</script>";
    }

    $stmt->close();
}

$conn->close();
?>
