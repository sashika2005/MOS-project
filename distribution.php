<?php
// Include the database connection
include('db_connection.php');

// Start the session to store messages
session_start();

// Handle form submission
if (isset($_POST['distribute_goods'])) {
    $coach_name = $_POST['coach_name'];
    $goods_name = $_POST['goods_name'];
    $quantity = (int) $_POST['quantity'];
    $date = $_POST['date'];

    // Get the coach ID based on the name
    $coach_result = $conn->query("SELECT id FROM Coach WHERE name = '$coach_name'");
    if ($coach_result->num_rows > 0) {
        $coach_row = $coach_result->fetch_assoc();
        $coach_id = $coach_row['id'];
    } else {
        $_SESSION['error_message'] = "Error: Coach not found.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    // Get the goods ID and remaining stock based on the name
    $goods_result = $conn->query("SELECT id, remaining_stock FROM SportingGoods WHERE name = '$goods_name'");
    if ($goods_result->num_rows > 0) {
        $goods_row = $goods_result->fetch_assoc();
        $goods_id = $goods_row['id'];
        $remaining_stock = $goods_row['remaining_stock'];
    } else {
        $_SESSION['error_message'] = "Error: Sporting goods not found.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }

    // Check if entered quantity exceeds available stock
    if ($quantity > $remaining_stock) {
        $_SESSION['error_message'] = "Error: Quantity entered exceeds remaining stock.";
    } else {
        // Update stock and insert into the distribution table
        $conn->query("UPDATE SportingGoods SET remaining_stock = remaining_stock - $quantity WHERE id = $goods_id");
        $conn->query("INSERT INTO Distribution (coach_id, goods_id, quantity, date) VALUES ('$coach_id', '$goods_id', '$quantity', '$date')");
        $_SESSION['success_message'] = "Success: Goods distributed successfully!";
    }

    // Redirect to avoid form resubmission
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Distribution Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
        }
        .form-container {
            max-width: 500px;
            margin: auto;
            padding: 50px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 25px;
        }
        .error-message {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
        .success-message {
            color: green;
            text-align: center;
            margin-bottom: 15px;
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
                        <a class="nav-link" href="distribution.php">Distribution</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">Profile</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <br><br>

    <div class="form-container">
        <h2>Distribute Goods</h2>

        <!-- Display error message if quantity exceeds stock -->
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="error-message"><?= $_SESSION['error_message']; ?></div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <!-- Display success message after successful distribution -->
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="success-message"><?= $_SESSION['success_message']; ?></div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <form method="POST">
           <div class="form-group">
                <label for="coach_id">Select Coach</label>
                <input list="coach_list" name="coach_name" class="form-control" required>
                <datalist id="coach_list">
                    <?php
                    $coaches = $conn->query("SELECT name FROM Coach");
                    while ($row = $coaches->fetch_assoc()) {
                        echo "<option value='{$row['name']}'></option>";
                    }
                    ?>
                </datalist>
            </div>

            <div class="form-group">
                <label for="goods_id">Select Goods</label>
                <input list="goods_list" name="goods_name" class="form-control" required>
                <datalist id="goods_list">
                    <?php
                    $goods = $conn->query("SELECT name, remaining_stock FROM SportingGoods");
                    while ($row = $goods->fetch_assoc()) {
                        echo "<option value='{$row['name']}'>Remaining: {$row['remaining_stock']}</option>";
                    }
                    ?>
                </datalist>
            </div>


            <div class="form-group">
                <label for="quantity">Quantity</label>
                <input type="number" name="quantity" class="form-control" placeholder="Quantity" required>
            </div>

            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" name="date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
            </div>

            <button type="submit" name="distribute_goods" class="btn btn-primary btn-block col-12">Distribute</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
