<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modern Styled Form</title>
    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: url('https://source.unsplash.com/1600x900/?nature,abstract') no-repeat center center/cover;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            max-width: 1000px;
            padding: 25px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(92, 84, 84, 0.3);
            color: rgb(15, 14, 14);
            animation: fadeIn 1s ease-in-out;
        }
        .form-label {
            font-weight: 600;
        }
        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: rgb(27, 23, 23);
        }
        .btn-primary {
            width: 100%;
            font-weight: 600;
            background: linear-gradient(45deg, #6a11cb, #2575fc);
            border: none;
            transition: 0.3s;
        }
        .btn-primary:hover {
            transform: scale(1.05);
            background: linear-gradient(45deg, #2575fc, #6a11cb);
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card text-blue">
        <h2 class="text-center mb-4">Registration Form</h2>
        <form action="submit.php" method="post">
            <div class="mb-3">
                <label for="name" class="form-label">Name:</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
            </div>

            <div class="mb-3">
                <label for="work_place" class="form-label">Service Place:</label>
                <input type="text" class="form-control" id="work_place" name="work_place" placeholder="Enter service place" required>
            </div>

            <div class="mb-3">
                <label for="NIC" class="form-label">NIC:</label>
                <input type="text" class="form-control" id="NIC" name="NIC" placeholder="Enter NIC number" required>
            </div>

            <div class="mb-3">
                <label for="tel" class="form-label">Telephone:</label>
                <input type="text" class="form-control" id="tel" name="tel" placeholder="Enter phone number" required>
            </div>

            <div class="mb-3">
                <label for="district" class="form-label">District:</label>
                <select name="district" class="form-select" required>
                    <option value="Galle">Galle</option>
                    <option value="Matara">Matara</option>
                    <option value="Hambantota">Hambantota</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="post" class="form-label">Post:</label>
                <select name="post" class="form-select" required>
                    <option value="Sport Officer">Sport Officer</option>
                    <option value="Coach">Coach</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
