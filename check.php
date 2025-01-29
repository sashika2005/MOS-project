<?php
// Database Connection
$conn = new mysqli('localhost', 'root', '', 'sports_db');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Coach Form Processing
if (isset($_POST['add_coach'])) {
    $name = $_POST['name'];
    $nic = $_POST['nic'];
    $work_place = $_POST['work_place'];
    $tel = $_POST['tel'];
    $district = $_POST['district'];
    $post = $_POST['post'];
    $conn->query("INSERT INTO Coach (name, NIC, work_place, tel, district, post) VALUES ('$name', '$nic', '$work_place', '$tel', '$district', '$post')");
}

// Sporting Goods Form Processing
if (isset($_POST['add_goods'])) {
    $name = $_POST['goods_name'];
    $stock = $_POST['stock'];
    $conn->query("INSERT INTO SportingGoods (name, total_stock, remaining_stock) VALUES ('$name', '$stock', '$stock')");
}

// Distribution Form Processing
if (isset($_POST['distribute_goods'])) {
    $coach_id = $_POST['coach_id'];
    $goods_id = $_POST['goods_id'];
    $quantity = $_POST['quantity'];

    // Update stock
    $conn->query("UPDATE SportingGoods SET remaining_stock = remaining_stock - $quantity WHERE id = $goods_id");
    // Insert into Distribution table without date field
    $conn->query("INSERT INTO Distribution (coach_id, goods_id, quantity) VALUES ('$coach_id', '$goods_id', '$quantity')");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sports Distribution System</title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
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
<br>
<br>

    <h2>Add Coach</h2>
    <form method="POST">
        <input type="text" name="name" placeholder="Name" required>
        <input type="text" name="nic" placeholder="NIC" required>
        <input type="text" name="work_place" placeholder="Work Place">
        <input type="text" name="tel" placeholder="Telephone">
        <input type="text" name="district" placeholder="District">
        <input type="text" name="post" placeholder="Post">
        <button type="submit" name="add_coach">Add Coach</button>
    </form>

    <h2>Add Sporting Goods</h2>
    <form method="POST">
        <input type="text" name="goods_name" placeholder="Goods Name" required>
        <input type="number" name="stock" placeholder="Total Stock" required>
        <button type="submit" name="add_goods">Add Goods</button>
    </form>

    <h2>Distribute Goods</h2>
    <form method="POST">
        <select name="coach_id" required>
            <option value="">Select Coach</option>
            <?php
            $coaches = $conn->query("SELECT * FROM Coach");
            while ($row = $coaches->fetch_assoc()) {
                echo "<option value='{$row['id']}'>{$row['name']}</option>";
            }
            ?>
        </select>
        <select name="goods_id" required>
            <option value="">Select Goods</option>
            <?php
            $goods = $conn->query("SELECT * FROM SportingGoods");
            while ($row = $goods->fetch_assoc()) {
                echo "<option value='{$row['id']}'>{$row['name']} (Remaining: {$row['remaining_stock']})</option>";
            }
            ?>
        </select>
        <input type="number" name="quantity" placeholder="Quantity" required>
        <button type="submit" name="distribute_goods">Distribute</button>
    </form>
</body>
</html>
