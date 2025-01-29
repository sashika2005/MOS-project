<?php
include('head.php');
include('db_connection.php');

// Initialize variables for filter input
$coach_name = $goods_name = "";

// Handle form submission for filtering
if (isset($_POST['filter'])) {
    $coach_name = $_POST['coach_name'] ?? '';  // Ensure coach_name is always set
    $goods_name = $_POST['goods_name'] ?? '';  // Ensure goods_name is always set
}

// Build SQL query to fetch distribution data with optional filters
$sql = "SELECT c.name AS coach_name, g.name AS goods_name, d.quantity, d.date 
        FROM Distribution d
        JOIN Coach c ON d.coach_id = c.id
        JOIN SportingGoods g ON d.goods_id = g.id
        WHERE 1";

if ($coach_name) {
    $sql .= " AND c.name LIKE ?";
}

if ($goods_name) {
    $sql .= " AND g.name LIKE ?";
}

// Prepare and bind the query
$stmt = $conn->prepare($sql);

if ($coach_name && $goods_name) {
    $coach_name_param = "%" . $coach_name . "%";
    $goods_name_param = "%" . $goods_name . "%";
    $stmt->bind_param("ss", $coach_name_param, $goods_name_param);
} elseif ($coach_name) {
    $coach_name_param = "%" . $coach_name . "%";
    $stmt->bind_param("s", $coach_name_param);
} elseif ($goods_name) {
    $goods_name_param = "%" . $goods_name . "%";
    $stmt->bind_param("s", $goods_name_param);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filter Distribution Records</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin-top: 30px;
        }

        h2 {
            font-size: 2rem;
            color: #343a40;
            margin-bottom: 20px;
            text-align: center;
        }


        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            border-radius: 8px;
            overflow: hidden;
        }

        table tbody tr:hover {
            background-color: #f1f1f1;
        }

        .no-data {
            text-align: center;
            color: #d9534f;
        }

        .navbar {
            background-color: rgba(0, 0, 0, 0.7) !important;
            z-index: 10;
            position: fixed;
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
                <ul class="navbar-nav ms-auto">
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
                        <a class="nav-link" href="distribution.php">distribution</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">Profile</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <br><br>

    <div class="container">
        <h2>Filter Distribution Records</h2>

        <!-- Filter form -->
        <form method="POST">
            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="coach_name">Coach Name</label>
                    <input type="text" class="form-control" name="coach_name" id="coach_name" value="<?= htmlspecialchars($coach_name) ?>">
                </div>

                <div class="form-group col-md-4">
                    <label for="goods_name">Goods Name</label>
                    <input type="text" class="form-control" name="goods_name" id="goods_name" value="<?= htmlspecialchars($goods_name) ?>">
                </div>
            </div>

            <button type="submit" name="filter" class="btn btn-primary">Filter</button>
        </form>

        <!-- Display filtered results -->
        <table class="table table-bordered" id="table1">
            <thead>
                <tr>
                    <th>Coach Name</th>
                    <th>Goods Name</th>
                    <th>Quantity</th>
                    <th>Date</th> <!-- New column -->
                </tr>
            </thead>

            <tbody>
                <?php
                if ($result->num_rows > 0) {
                   while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>" . htmlspecialchars($row['coach_name']) . "</td>
                            <td>" . htmlspecialchars($row['goods_name']) . "</td>
                            <td>" . htmlspecialchars($row['quantity']) . "</td>
                            <td>" . htmlspecialchars($row['date']) . "</td> <!-- Display date -->
                        </tr>";
                }

                } else {
                    echo "<tr><td colspan='3' class='no-data'>No data found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#table1').DataTable({
                dom: 'Bfrtip',
                order: [],
                pageLength: 10,
                buttons: [
                    {
                        extend: 'copy',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'excel',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdf',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'print',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    'colvis'
                ],
                responsive: true
            });
        });
    </script>

<?php include('foot.php'); ?>
</body>
</html>

<?php
// Close database connection
$conn->close();
?>
