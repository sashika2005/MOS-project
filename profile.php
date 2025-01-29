<?php
include('head.php');
include('db_connection.php');

// Initialize variables for filter input
$coach_name = $goods_name = "";

// Handle form submission for filtering
if (isset($_POST['filter'])) {
    $coach_name = $_POST['coach_name'] ?? '';  // Ensure coach_name is always set
    $goods_name = $_POST['goods_name'] ?? '';  // Ensure goods_name is always set

    // Redirect to the same page to avoid form resubmission message on refresh
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Build SQL query to fetch distribution data with optional filters
$sql = "SELECT c.name AS coach_name, g.name AS goods_name, d.quantity 
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

        .form-row {
            margin-bottom: 15px;
        }

        .form-control {
            border-radius: 5px;
            border: 1px solid #ddd;
            padding: 10px;
        }

        .btn-primary {
            background-color: #007bff;
            border: 1px solid #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 15px;
        }

        .btn-primary:hover {
            background-color: #0056b3;
            border: 1px solid #0056b3;
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

        .dataTables_length {
            display: none;
        }
    </style>
</head>
<body>

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
            </tr>
        </thead>
        <tbody>
            <?php
            // Check if there are results and display them
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    // Ensure keys exist to prevent undefined index warnings
                    $coach_name = isset($row['coach_name']) ? $row['coach_name'] : 'Unknown';
                    $goods_name = isset($row['goods_name']) ? $row['goods_name'] : 'Unknown';

                    echo "<tr>
                            <td>{$coach_name}</td>
                            <td>{$goods_name}</td>
                            <td>{$row['quantity']}</td>
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
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

<script>
        $(document).ready(function() {
            $('#table1').DataTable({
                dom: 'Bfrtip',
                order: [],
                pageLength: 7,
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
