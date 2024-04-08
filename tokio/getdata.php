<?php
$host = 'localhost'; // หรือ IP ของเซิร์ฟเวอร์ฐานข้อมูล
$dbname = 'tokio';
$user = 'root';
$password = '';
$port = 3307;

try {
    $queryText = "Hello";
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;port=$port", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $pdo->query("SELECT assistant_content, system_content FROM messages");
    
    $data = [
        "model" => "ft:gpt-3.5-turbo-0125:personal::8xe2NuPn",
        "messages" => [],
        "temperature" => 0,
        "max_tokens" => 1000
    ];
    $data["messages"][] = ["role" => "system", "content" => "You are an intelligent insurance agent expert. Assuming the persona of a woman named Yuki."];
    // while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    //     // ดึงข้อมูลและเพิ่มเข้าในอาร์เรย์ 'messages'
    //     $data["messages"][] = ["role" => "assistant", "content" => $row['assistant_content']];
    //     $data["messages"][] = ["role" => "system", "content" => $row['system_content']];
    // }
    $data["messages"][] = ["role" => "user", "content" => $queryText];
    // ตอนนี้ $data มีข้อมูลทั้งหมดจากฐานข้อมูล
    // แสดงหรือใช้งาน $data  
    echo "<pre>" . print_r($data, true) . "</pre>";

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
