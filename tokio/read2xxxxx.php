<?php
$host = 'localhost'; // หรือ IP ของเซิร์ฟเวอร์ฐานข้อมูล
$dbname = 'tokio';
$user = 'root';
$password = '';
$port = 3307;
$perPage = 5; // จำนวนรายการต่อหน้า
$pdo = new PDO("mysql:host=$host;dbname=$dbname;port=$port", $user, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// คำนวณจำนวนหน้าทั้งหมด
$stmt = $pdo->query("SELECT COUNT(*) FROM messages");
$rowCount = $stmt->fetchColumn();
$totalPages = ceil($rowCount / $perPage);

// คำนวณหน้าปัจจุบัน
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$currentPage = max(1, min($currentPage, $totalPages));
$offset = ($currentPage - 1) * $perPage;

// ดึงข้อมูลสำหรับหน้าปัจจุบัน
$stmt = $pdo->prepare("SELECT id, assistant_content, system_content FROM messages LIMIT :perPage OFFSET :offset");
$stmt->bindParam(':perPage', $perPage, PDO::PARAM_INT);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->execute();

echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Prompt Management</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="inputform.html">เพิ่มข้อมูล <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="read.php">แสดงข้อมูล</a>
            </li>
        </ul>
    </div>
</nav>
</div>
<div class="container mt-5">
    <h2>Training Messages</h2>
    <table class="table table-bordered table-hover">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Assistant Content</th>
                <th>System Content</th>
                <th>Operations:</th>
            </tr>
        </thead>
        <tbody>';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;port=$port", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT id, assistant_content, system_content FROM messages");
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['assistant_content']) . "</td>";
        echo "<td>" . htmlspecialchars($row['system_content']) . "</td>";
        echo "<td><a href='update_form.php?id=" . $row['id'] . "'>Edit</a> | <a href='delete_process.php?id=" . $row['id'] . "'>Delete</a></td>";
        echo "</tr>";
    }
        // สร้างลิงก์สำหรับเปลี่ยนหน้า
        for ($i = 1; $i <= $totalPages; $i++) {
            echo "<a href='read.php?page=$i'>$i</a> ";
        }

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

echo '</tbody>
    </table>
</div>
<!-- Optional JavaScript; choose one of the two! -->

<!-- Option 1: jQuery and Bootstrap Bundle (includes Popper) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>';
?>