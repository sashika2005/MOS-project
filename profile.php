<?php
// Include the database connection
include('db_connection.php');

// Start the session to store messages
session_start();

// Handle form submission for filtering
$coach_filter = isset($_POST['coach_filter']) ? $_POST['coach_filter'] : '';
$quantity_filter = isset($_POST['quantity_filter']) ? $_POST['quantity_filter'] : '';

// Query for distribution data with optional filtering
$query = "SELECT * FROM Distribution";

// Add filtering conditions if coach filter or quantity filter is set
$conditions = [];
if ($coach_filter) {
    $conditions[] = "coach_id = $coach_filter";
}

if ($quantity_filter) {
    $conditions[] = "quantity > $quantity_filter";
}

if (count($conditions) > 0) {
    $query .= " WHERE " . implode(' AND ', $conditions);
}

$result = $conn->query($query);

// Fetch the coaches for the dropdown filter
$coaches_result = $conn->query("SELECT * FROM Coach");

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Distribution Data</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
        }
        .filter-container {
            margin-bottom: 20px;
        }
        table th, table td {
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2 class="text-center mb-4">Coach Distribution Profile</h2>

        <!-- Filter Form -->
        <div class="filter-container">
            <form method="POST" class="form-inline">
                <label for="coach_filter" class="mr-2">Filter by Coach:</label>
                <select name="coach_filter" class="form-control mr-2" id="coach_filter">
                    <option value="">Select Coach</option>
                    <?php
                    while ($row = $coaches_result->fetch_assoc()) {
                        echo "<option value='{$row['id']}' " . ($coach_filter == $row['id'] ? 'selected' : '') . ">{$row['name']}</option>";
                    }
                    ?>
                </select>

                <label for="quantity_filter" class="mr-2">Minimum Quantity:</label>
                <input type="number" name="quantity_filter" class="form-control mr-2" placeholder="Enter quantity" value="<?= $quantity_filter ?>">

                <button type="submit" class="btn btn-primary">Filter</button>
            </form>
        </div>

        <!-- Distribution Table -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Coach Name</th>
                    <th>Goods Name</th>
                    <th>Quantity Distributed</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Get coach name from the Coach table
                        $coach_id = $row['coach_id'];
                        $coach_result = $conn->query("SELECT name FROM Coach WHERE id = $coach_id");
                        $coach = $coach_result->fetch_assoc();

                        // Get goods name from the SportingGoods table
                        $goods_id = $row['goods_id'];
                        $goods_result = $conn->query("SELECT name FROM SportingGoods WHERE id = $goods_id");
                        $goods = $goods_result->fetch_assoc();

                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$coach['name']}</td>
                                <td>{$goods['name']}</td>
                                <td>{$row['quantity']}</td>
                                <td>{$row['date']}</td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No records found</td></tr>";
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
$conn->close();
?>
