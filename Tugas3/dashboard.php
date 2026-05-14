<?php
session_start();
if (!isset($_SESSION['nama'])) {
  header("Location: auth.php");
  exit();
}

include_once "koneksi.php";

// Logika delete - hanya admin yang bisa menghapus
if (isset($_GET['hapus'])) {
  if ($_SESSION['nama'] === "admin") {
    $id = (int) $_GET['hapus'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: dashboard.php");
    exit;
  } else {
    // Akses ditolak jika bukan admin
    header("Location: dashboard.php");
    exit;
  }
}

// Ambil data users
$data = mysqli_query($conn, "SELECT id, nama FROM users ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    table { border-collapse: collapse; margin-top: 20px; }
    table, th, td { border: 1px solid #ddd; padding: 10px; }
    th { background-color: #f2f2f2; }
    a { margin: 0 5px; color: #0066cc; text-decoration: none; }
    a:hover { text-decoration: underline; }
    button { padding: 8px 15px; cursor: pointer; }
  </style>
</head>

<body>
  <h2>Selamat Datang di Dashboard</h2>
  <p>Halo, <?php echo htmlspecialchars($_SESSION['nama']); ?>!</p>
  <a href="logout.php"><button>Logout</button></a>
  <hr />

  <?php if ($_SESSION['nama'] === "admin"): ?>
    <!-- Tampilkan tabel manajemen data hanya untuk admin -->
    <h3>Manajemen Data Pengguna</h3>
    <table>
      <tr>
        <th>ID</th>
        <th>Nama</th>
        <th>Aksi</th>
      </tr>
      <?php while ($row = mysqli_fetch_assoc($data)): ?>
        <tr>
          <td><?= htmlspecialchars($row['id']) ?></td>
          <td><?= htmlspecialchars($row['nama']) ?></td>
          <td>
            <a href="edit.php?id=<?= $row['id'] ?>">Edit</a>
            <a href="?hapus=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus pengguna ini?')">Hapus</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </table>
  <?php else: ?>
    <!-- Tampilkan halaman dashboard reguler untuk non-admin -->
    <p>Anda login sebagai pengguna biasa. Akses ke manajemen data dibatasi hanya untuk admin.</p>
  <?php endif; ?>

</body>

</html>