<?php 
include 'db_connection.php';
session_start();  // Start the session to store success message

// Delete the row if delete_id is set
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    $delete_query = "DELETE FROM SportingGoods WHERE id = $delete_id";
    
    if ($conn->query($delete_query) === TRUE) {
        $_SESSION['message'] = "<div class='alert alert-success'>Sporting goods deleted successfully!</div>";
    } else {
        $_SESSION['message'] = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
    header("Location: " . $_SERVER['PHP_SELF']);  // Redirect to prevent resubmission
    exit();
}

// Handle form submission for adding goods
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $goods_name = $conn->real_escape_string($_POST['goods_name']);
    $total_stock = (int)$_POST['total_stock'];
    $remaining_stock = $total_stock;

    // Check if the goods already exist
    $check_query = "SELECT * FROM SportingGoods WHERE name = '$goods_name'";
    $check_result = $conn->query($check_query);

    if ($check_result->num_rows > 0) {
        $_SESSION['message'] = "<div class='alert alert-warning'>This sporting goods already exist!</div>";
    } else {
        // Insert the new goods record if not already existing
        $sql = "INSERT INTO SportingGoods (name, total_stock, remaining_stock) VALUES ('$goods_name', $total_stock, $remaining_stock)";
        
        if ($conn->query($sql) === TRUE) {
            // Store success message in session and redirect to the same page
            $_SESSION['message'] = "<div class='alert alert-success'>Sporting goods added successfully!</div>";
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            $_SESSION['message'] = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Sporting Goods</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
        
    .card{
        padding-left: 50px;
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
                    <a class="nav-link" href="distribution.php">distribution</a>
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
        <div class="card shadow-sm p-4">
            <h2 class="mb-4">Add Sporting Goods</h2>
            
            <!-- Display session message if set -->
            <?php
                if (isset($_SESSION['message'])) {
                    echo $_SESSION['message'];
                    unset($_SESSION['message']);  // Clear the message after displaying it
                }
            ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="goods_name" class="form-label">Goods Name</label>
                    <input type="text" id="goods_name" name="goods_name" class="form-control" placeholder="Enter goods name" required>
                </div>
                <div class="mb-3">
                    <label for="total_stock" class="form-label">Total Stock</label>
                    <input type="number" id="total_stock" name="total_stock" class="form-control" placeholder="Enter total stock" required>
                </div>
                <button type="submit" class="btn btn-primary">Save Goods</button>
            </form>
        </div>
    </div>

    <div class="container mt-5">
        <div class="card shadow-sm p-4">
            <h2>All Sporting Goods</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Total Stock</th>
                        <th>Remaining Stock</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        // Display all goods
                        $result = $conn->query("SELECT * FROM SportingGoods");
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>{$row['id']}</td>";
                            echo "<td>{$row['name']}</td>";
                            echo "<td>{$row['total_stock']}</td>";
                            echo "<td>{$row['remaining_stock']}</td>";
                            echo "<td><a href='?delete_id={$row['id']}' class='btn btn-danger'>Delete</a></td>";
                            echo "</tr>";
                        }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
