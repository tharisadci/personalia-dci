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

// Menangani pencarian
$search = isset($_GET['search']) ? $_GET['search'] : '';

$sql = "SELECT * FROM dokumen WHERE nama LIKE ? OR jenis LIKE ? OR departemen LIKE ?";
$stmt = $conn->prepare($sql);
$searchTerm = "%$search%";
$stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pencarian Dokumen</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Hasil Pencarian</h1>
    <form method="GET">
        <input type="text" name="search" placeholder="Cari dokumen..." value="<?php echo $search; ?>">
        <input type="submit" value="Cari">
    </form>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Jenis</th>
                <th>Tanggal</th>
                <th>Departemen</th>
                <th>Dokumen</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                $no = 1;
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $no++ . "</td>";
                    echo "<td>" . $row['nama'] . "</td>";
                    echo "<td>" . $row['jenis'] . "</td>";
                    echo "<td>" . $row['tanggal'] . "</td>";
                    echo "<td>" . $row['departemen'] . "</td>";
                    echo "<td><a href='uploads/" . $row['file'] . "' target='_blank'>Lihat</a></td>";
                    echo "<td>
                            <a href='edit.php?id=" . $row['id'] . "'>Edit</a> |
                            <a href='hapus.php?id=" . $row['id'] . "' onclick=\"return confirm('Yakin mau hapus?')\">Hapus</a>
                         </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>Tidak ada hasil ditemukan</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <br>
    <a href="index.php">Kembali ke Halaman Utama</a>
</div>
</body>
</html>
