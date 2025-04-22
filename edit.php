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

if (isset($_POST['update'])) {
    $nama = $_POST['nama'];
    $jenis = $_POST['jenis'];
    $tanggal = $_POST['tanggal'];
    $departemen = $_POST['departemen'];

    $sql = "UPDATE dokumen SET nama=?, jenis=?, tanggal=?, departemen=? WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $nama, $jenis, $tanggal, $departemen, $id);
    $stmt->execute();

    header("Location: search.php?search=$nama");
    exit;
}

// Ambil data lama
$sql = "SELECT * FROM dokumen WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Dokumen</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Edit Dokumen</h1>
    <form method="POST">
        <label>Nama:</label>
        <input type="text" name="nama" value="<?php echo $data['nama']; ?>" required><br>
        <label>Jenis:</label>
        <input type="text" name="jenis" value="<?php echo $data['jenis']; ?>" required><br>
        <label>Tanggal:</label>
        <input type="date" name="tanggal" value="<?php echo $data['tanggal']; ?>" required><br>
        <label>Departemen:</label>
        <input type="text" name="departemen" value="<?php echo $data['departemen']; ?>" required><br>
        <input type="submit" name="update" value="Update Dokumen">
    </form>
    <br>
    <a href="search.php?search=<?php echo $data['nama']; ?>">Kembali</a>
</div>
</body>
</html>
