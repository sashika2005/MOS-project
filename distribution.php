<?php
// Include the database connection
include('db_connection.php');

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
        $error_message = "Error: Quantity entered exceeds remaining stock.";
    } else {
        // Update stock if quantity is valid
        $conn->query("UPDATE SportingGoods SET remaining_stock = remaining_stock - $quantity WHERE id = $goods_id");

        // Insert into Distribution table
        $conn->query("INSERT INTO Distribution (coach_id, goods_id, quantity) VALUES ('$coach_id', '$goods_id', '$quantity')");

        // Success message or redirection (optional)
        $success_message = "Goods successfully distributed.";
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
    </style>
</head>
<body>

    <div class="form-container">
        <h2>Distribute Goods</h2>
        
        <!-- Display error message if quantity exceeds stock -->
        <?php if (isset($error_message)): ?>
            <div class="error-message"><?= $error_message; ?></div>
        <?php endif; ?>

        <!-- Display success message after successful distribution -->
        <?php if (isset($success_message)): ?>
            <div class="success-message"><?= $success_message; ?></div>
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
