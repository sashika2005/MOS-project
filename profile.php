<?php
// Include database connection
include('db_connection.php');

// Initialize variables for filter input
$coach_name = $goods_name = "";

// Handle form submission for filtering
if (isset($_POST['filter'])) {
    $coach_name = $_POST['coach_name'];
    $goods_name = $_POST['goods_name'];
}

// Build SQL query to fetch distribution data with optional filters
$sql = "SELECT d.id, c.name AS coach_name, g.name AS goods_name, d.quantity 
        FROM Distribution d
        JOIN Coach c ON d.coach_id = c.id
        JOIN SportingGoods g ON d.goods_id = g.id
        WHERE 1";  // Always true condition to simplify adding filters

// Add filter conditions if values are provided
if ($coach_name) {
    $sql .= " AND c.name LIKE '%$coach_name%'";
}

if ($goods_name) {
    $sql .= " AND g.name LIKE '%$goods_name%'";
}

// Execute the query
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filter Distribution Records</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<style>
     .navbar {
            background-color: rgba(0, 0, 0, 0.7) !important; /* Added background color to the navbar */
            z-index: 10;
            position: fixed; /* Fixed position */
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
            padding: 10px 0;
        }

    </style>
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
                <li class="nav-item">
                    <a class="nav-link" href="profile.php">Profile</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<br>
<br>

<div class="container mt-5">
    <h2>Filter Distribution Records</h2>

    <!-- Filter form -->
    <form method="POST" class="mb-4">
        <div class="form-row">
            <div class="form-group col-md-4">
                <label for="coach_name">Coach Name</label>
                <input type="text" class="form-control" name="coach_name" id="coach_name" value="<?= $coach_name ?>">
            </div>

            <div class="form-group col-md-4">
                <label for="goods_name">Goods Name</label>
                <input type="text" class="form-control" name="goods_name" id="goods_name" value="<?= $goods_name ?>">
            </div>
        </div>

        <button type="submit" name="filter" class="btn btn-primary">Filter</button>
    </form>

    <!-- Display filtered results -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Coach Name</th>
                <th>Goods Name</th>
                <th>Quantity</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Check if there are results and display them
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['coach_name']}</td>
                            <td>{$row['goods_name']}</td>
                            <td>{$row['quantity']}</td>
                          </tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No data found</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

<?php
// Close database connection
$conn->close();
?>
