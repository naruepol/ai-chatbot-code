<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.html');
    exit;
}
$host = 'localhost';
$dbname = 'tokio';
$user = 'root';
$password = '';
$port = 3307;
// กำหนดจำนวนรายการต่อหน้า
$perPage = 10;

// คำนวณหน้าปัจจุบันจาก query string 'page'. ถ้าไม่มี, ใช้หน้า 1
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// คำนวณ OFFSET สำหรับ SQL query
$offset = ($currentPage - 1) * $perPage;
echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages</title>
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
    <h2>Training Messages</h2>
    <table class="table table-bordered table-hover">
        <thead class="thead-dark">
            <tr>
                <th>ID</th>
                <th>Assistant Content</th>
                <th>System Content</th>
                <th>Status</th>
                <th>Operations</th>
            </tr>
        </thead>
        <tbody>';

try {


    $pdo = new PDO("mysql:host=$host;dbname=$dbname;port=$port", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->prepare("SELECT id, assistant_content, system_content, status FROM messages LIMIT :perPage OFFSET :offset");
    $stmt->bindParam(':perPage', $perPage, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
        echo "<td>" . htmlspecialchars($row['assistant_content']) . "</td>";
        echo "<td>" . htmlspecialchars($row['system_content']) . "</td>";
        echo "<td>" . htmlspecialchars($row['status']) . "</td>";
        $toggleStatus = ($row['status'] == 'active') ? 'inactive' : 'active';
        echo "<td><a href='update_form.php?id=" . $row['id'] . "'>Edit</a> | <a href='delete_process.php?id=" . $row['id'] . "'>Delete</a> | <a href='toggle_status.php?id=" . $row['id'] . "&status=" . $toggleStatus . "'>Toggle Status</a></td>";
        echo "</tr>";
    }

        // ดึงจำนวนข้อมูลทั้งหมดเพื่อคำนวณจำนวนหน้า
        $stmt = $pdo->query("SELECT COUNT(*) FROM messages");
        $totalRows = $stmt->fetchColumn();
        $totalPages = ceil($totalRows / $perPage);
    
        // สร้างลิงก์สำหรับแต่ละหน้า
        for ($i = 1; $i <= $totalPages; $i++) {
            echo "<a href='read4.php?page=$i'>$i || </a> ";
        }

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

echo '</tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>';
?>
