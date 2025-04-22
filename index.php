<?php
// Koneksi ke database
$host = "localhost";
$username = "root";
$password = "";
$database = "dokumen_personalia";

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Proses upload dokumen
if (isset($_POST['upload'])) {
    $nama = $_POST['nama'];
    $jenis = $_POST['jenis'];
    $tanggal = $_POST['tanggal'];
    $departemen = $_POST['departemen'];

    // Menangani file upload
    $file = $_FILES['dokumen']['name'];
    $file_tmp = $_FILES['dokumen']['tmp_name'];
    $file_size = $_FILES['dokumen']['size'];
    $file_error = $_FILES['dokumen']['error'];

    // Tentukan folder penyimpanan dokumen
    $upload_dir = 'uploads/';
    $file_path = $upload_dir . basename($file);

    // Cek jika ada error saat upload
    if ($file_error === 0) {
        if (move_uploaded_file($file_tmp, $file_path)) {
            // Simpan data ke database
            $sql = "INSERT INTO dokumen (nama, jenis, tanggal, departemen, file) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $nama, $jenis, $tanggal, $departemen, $file);
            $stmt->execute();
            echo "Dokumen berhasil diupload!";
        } else {
            echo "Gagal upload dokumen!";
        }
    } else {
        echo "Terjadi error saat upload file!";
    }
}

// Menangani pengurutan berdasarkan GET parameter
$order_by = isset($_GET['order_by']) ? $_GET['order_by'] : 'id'; // Default urut berdasarkan 'id'
$order_dir = isset($_GET['order_dir']) && $_GET['order_dir'] == 'asc' ? 'asc' : 'desc'; // Default urutan 'desc'

// Menentukan query berdasarkan pengurutan
$sql = "SELECT * FROM dokumen ORDER BY $order_by $order_dir";

// Eksekusi query
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengelolaan Dokumen Personalia</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Zen+Maru+Gothic:wght@500&display=swap" rel="stylesheet">
</head>
<body>

    <div class="container">
        <h1>Upload Dokumen Personalia</h1>
        <form method="POST" enctype="multipart/form-data">
            <label for="nama">Nama:</label>
            <input type="text" id="nama" name="nama" required><br>
            
            <label for="jenis">Jenis Dokumen:</label>
            <input type="text" id="jenis" name="jenis" required><br>

            <label for="tanggal">Tanggal:</label>
            <input type="date" id="tanggal" name="tanggal" required><br>

            <label for="departemen">Departemen:</label>
            <input type="text" id="departemen" name="departemen" required><br>

            <label for="dokumen">Pilih Dokumen:</label>
            <input type="file" id="dokumen" name="dokumen" required><br>
            
            <input type="submit" name="upload" value="Upload Dokumen">
        </form>

        <!-- Link ke halaman pencarian -->
        <h2><a href="search.php">Pencarian Dokumen</a></h2>

    </div>
</body>
</html>
