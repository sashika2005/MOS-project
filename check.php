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
    <link rel="stylesheet" href="styles.css">
</head>
<body>
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
