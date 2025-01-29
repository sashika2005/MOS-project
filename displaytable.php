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

// Fetch data from the Coach table
$sql = "SELECT * FROM Coach";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Coaches - Sports DB</title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTable CSS -->
    <link href="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.12.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="form.php">Sports</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto"> <!-- Added ms-auto class here to align to the right -->
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="form.php">Register</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="displaytable.php">View Table</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="goods.php">Goods</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="check.php">Check</a>
                </li>
            </ul>
        </div>
    </div>
</nav>


    <div class="container my-5">
        <h2 class="text-center mb-4">Coaches List</h2>

        <!-- Table to display the data -->
        <table id="coachesTable" class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>NIC</th>
                    <th>Service Place</th>
                    <th>Telephone</th>
                    <th>District</th>
                    <th>Post</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Check if there are results and display them
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['name']}</td>
                                <td>{$row['NIC']}</td>
                                <td>{$row['work_place']}</td>
                                <td>{$row['tel']}</td>
                                <td>{$row['district']}</td>
                                <td>{$row['post']}</td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No data available</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTable JS -->
    <script src="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.12.1/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/datatables.net@1.12.1/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#coachesTable').DataTable();
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>
