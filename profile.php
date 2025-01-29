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

// Fetch all coaches for datalist suggestions
$suggestionsSql = "SELECT DISTINCT name FROM Coach";
$suggestionsResult = $conn->query($suggestionsSql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Search Coaches</title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTable CSS -->
    <link href="https://cdn.jsdelivr.net/npm/datatables.net-bs5@1.12.1/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="form.php">Sports</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="form.php">Register</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="profile.php">Search Coaches</a>
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

    <!-- Search form -->
    <div class="container my-5">
        <h2 class="text-center mb-4">Search Coaches</h2>

        <form id="searchForm" class="d-flex mb-4">
            <input type="text" name="searchTerm" id="searchTerm" class="form-control me-2" placeholder="Search by name or NIC" list="coachNames">
            <datalist id="coachNames">
                <?php
                // Loop through the suggestions result to display names in the datalist
                if ($suggestionsResult->num_rows > 0) {
                    while ($row = $suggestionsResult->fetch_assoc()) {
                        echo "<option value='" . htmlspecialchars($row['name']) . "'>";
                    }
                }
                ?>
            </datalist>
        </form>

        <!-- Table to display the coaches data -->
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
                <!-- Table data will be loaded dynamically via AJAX -->
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
            // Initialize DataTable (will be populated dynamically)
            var table = $('#coachesTable').DataTable();

            // Listen for input on the search field and send an AJAX request
            $('#searchTerm').on('input', function() {
                var searchTerm = $(this).val();

                // AJAX request to filter coaches based on search term
                $.ajax({
                    url: 'fetch_coaches.php',
                    type: 'GET',
                    data: { searchTerm: searchTerm },
                    success: function(response) {
                        // Clear the existing table and populate with new filtered data
                        var data = JSON.parse(response);
                        table.clear().draw();
                        if (data.length > 0) {
                            data.forEach(function(coach) {
                                table.row.add([
                                    coach.id,
                                    coach.name,
                                    coach.nic,
                                    coach.work_place,
                                    coach.tel,
                                    coach.district,
                                    coach.post
                                ]).draw();
                            });
                        } else {
                            table.row.add(['', '', '', '', '', '', 'No results found']).draw();
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>
