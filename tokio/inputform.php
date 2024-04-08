<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.html');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message Form</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            margin: 20px;
        }
        .footer {
          position: fixed;
          bottom: 0;
          width: 100%;
          background-color: #f5f5f5;
          color: #8a8a8a;
        }
    </style>
</head>
<body>
    <center>
    <div class="container mt-5">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">Prompt Management</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item active">
                        <a class="nav-link" href="inputform.php">เพิ่มข้อมูล <span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="read.php">แสดงข้อมูล (active)</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" href="read3.php">ข้อมูล (active/inactive)</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" target="_blank" href="../chatbot">chatbot</a>
                    </li>
                    <li class="nav-item">
                    <a class="nav-link" target="_blank" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
    <div class="container form-container">
        <h2>Submit Training Message</h2>
        <form action="processform.php" method="post">
            <div class="form-group">
                <label for="assistantContent">Assistant Content:</label>
                <textarea class="form-control" id="assistantContent" name="assistant_content" rows="3" required></textarea>
            </div>

            <div class="form-group">
                <label for="systemContent">System Content:</label>
                <textarea class="form-control" id="systemContent" name="system_content" rows="6" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</center>
    <!-- Bootstrap JS, Popper.js, and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<footer class="footer mt-auto py-3 bg-light">
  <div class="container text-center">
    <span class="text-muted">&copy; 2024 SDITT66. All Rights Reserved.</span>
  </div>
</footer>
</body>
</html>
