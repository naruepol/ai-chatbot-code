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
    <meta http-equiv="refresh" content="2;url=https://sc.npru.ac.th/tokio/read.php">
    <title>Update</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .form-container {
            margin: 20px;
        }
        body, html {
            height: 100%;
        }
        .vertical-center {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .alert-custom {
            background-color: #dff0d8; /* Light green background */
            color: #3c763d; /* Dark green text */
            border-radius: 20px; /* Rounded edges */
            padding: 20px;
            width: 100%;
            max-width: 400px; /* Maximum width */
            box-shadow: 0 4px 8px rgba(0,0,0,0.1); /* Shadow effect */
            border: 1px solid #badbcc; /* Light green border */
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
<?php
$host = 'localhost'; // หรือ IP ของเซิร์ฟเวอร์ฐานข้อมูล
$dbname = 'tokio';
$user = 'root';
$password = '';
$port = 3307;

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;port=$port", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST['id'];
        $assistantContent = $_POST['assistant_content'];
        $systemContent = $_POST['system_content'];

        $stmt = $pdo->prepare("UPDATE messages SET assistant_content = :assistantContent, system_content = :systemContent WHERE id = :id");
        $stmt->bindParam(':assistantContent', $assistantContent);
        $stmt->bindParam(':systemContent', $systemContent);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        // echo "<p>Message with ID " . htmlspecialchars($id) . " has been updated.</p>";
        echo "<div class=\"vertical-center text-center\">";
        echo "<div class=\"alert-custom\">";
        echo "<strong>Success! </strong> Message with ID " . htmlspecialchars($id) . " has been updated.";
        echo "</div>";
        echo "</div>";
    } else {
        throw new Exception("Invalid request method.");
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
} catch(Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
