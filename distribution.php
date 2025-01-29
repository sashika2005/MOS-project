<?php
// Include the database connection
include('db_connection.php');

// Start the session to store messages
session_start();

// Handle form submission
if (isset($_POST['distribute_goods'])) {
    $coach_id = $_POST['coach_id'];
    $goods_id = $_POST['goods_id'];
    $quantity = $_POST['quantity'];

    // Check if the entered quantity is greater than the remaining stock
    $result = $conn->query("SELECT remaining_stock FROM SportingGoods WHERE id = $goods_id");
    $row = $result->fetch_assoc();
    $remaining_stock = $row['remaining_stock'];

    if ($quantity > $remaining_stock) {
        // Store error message in the session
        $_SESSION['error_message'] = "Error: Quantity entered exceeds remaining stock.";
        
        // Redirect to prevent form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        // Update stock if quantity is valid
        $conn->query("UPDATE SportingGoods SET remaining_stock = remaining_stock - $quantity WHERE id = $goods_id");

        // Insert into Distribution table
        $conn->query("INSERT INTO Distribution (coach_id, goods_id, quantity) VALUES ('$coach_id', '$goods_id', '$quantity')");

        // Set success message
        $_SESSION['success_message'] = "Success: Goods distributed successfully!";
        
        // Redirect after successful submission to avoid resubmission on refresh
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Distribution Form</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 30px;
        }
        .form-container {
            max-width: 500px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
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
                <select name="coach_id" class="form-control" required>
                    <option value="">Select Coach</option>
                    <?php
                    $coaches = $conn->query("SELECT * FROM Coach");
                    while ($row = $coaches->fetch_assoc()) {
                        echo "<option value='{$row['id']}'>{$row['name']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="goods_id">Select Goods</label>
                <select name="goods_id" class="form-control" required>
                    <option value="">Select Goods</option>
                    <?php
                    $goods = $conn->query("SELECT * FROM SportingGoods");
                    while ($row = $goods->fetch_assoc()) {
                        echo "<option value='{$row['id']}'>
                                {$row['name']} (Remaining: {$row['remaining_stock']})
                            </option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="quantity">Quantity</label>
                <input type="number" name="quantity" class="form-control" placeholder="Quantity" required>
            </div>

            <button type="submit" name="distribute_goods" class="btn btn-primary btn-block">Distribute</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
