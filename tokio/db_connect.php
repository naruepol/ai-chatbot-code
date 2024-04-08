<?php
// ไฟล์ db_connect.php

$host = 'localhost'; // หรือ IP ของเซิร์ฟเวอร์ฐานข้อมูล
$dbname = 'tokio'; // ชื่อฐานข้อมูล
$user = 'root'; // ชื่อผู้ใช้ฐานข้อมูล
$password = ''; // รหัสผ่านฐานข้อมูล
$port = 3307;

try {
    // สร้างออบเจกต์ PDO สำหรับการเชื่อมต่อ
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;port=$port;charset=utf8", $user, $password);
    // ตั้งค่า error mode ของ PDO เพื่อ throw exceptions
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // ตัวแปร $pdo สามารถใช้เพื่อทำการดำเนินการกับฐานข้อมูล
} catch(PDOException $e) {
    // จัดการข้อผิดพลาดในการเชื่อมต่อ
    die("Could not connect to the database $dbname :" . $e->getMessage());
}
?>
