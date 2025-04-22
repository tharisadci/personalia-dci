<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "dokumen_personalia";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'];

// Ambil nama file sebelum hapus
$sql = "SELECT file FROM dokumen WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

// Hapus file dari folder
if ($data && file_exists("uploads/" . $data['file'])) {
    unlink("uploads/" . $data['file']);
}

// Hapus dari database
$sql = "DELETE FROM dokumen WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: search.php");
exit;
