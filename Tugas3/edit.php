<?php
session_start();

// Proteksi halaman: hanya admin yang dapat mengakses
if (!isset($_SESSION['nama']) || $_SESSION['nama'] !== "admin") {
  header("Location: dashboard.php");
  exit();
}

include_once "koneksi.php";

// Cek apakah parameter ID ada
if (!isset($_GET['id']) || empty($_GET['id'])) {
  header("Location: dashboard.php");
  exit();
}

$id = (int) $_GET['id'];

// Ambil data pengguna berdasarkan ID
$stmt = $conn->prepare("SELECT id, nama FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
  // ID tidak ditemukan, arahkan ke dashboard
  header("Location: dashboard.php");
  exit();
}

$user = $result->fetch_assoc();
$stmt->close();

$pesan = "";
$pesan_tipe = "";

// Proses update data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
  $nama_baru = trim($_POST['nama']);
  $password_baru = $_POST['password'];

  // Validasi input
  if (empty($nama_baru)) {
    $pesan = "Nama wajib diisi!";
    $pesan_tipe = "error";
  } elseif (empty($password_baru)) {
    $pesan = "Password wajib diisi!";
    $pesan_tipe = "error";
  } elseif (strlen($password_baru) < 6) {
    $pesan = "Password minimal 6 karakter!";
    $pesan_tipe = "error";
  } else {
    // Enkripsi password baru menggunakan password_hash()
    $hashed_password = password_hash($password_baru, PASSWORD_BCRYPT);

    // Update ke database
    $stmt_update = $conn->prepare("UPDATE users SET nama = ?, password = ? WHERE id = ?");
    $stmt_update->bind_param("ssi", $nama_baru, $hashed_password, $id);

    if ($stmt_update->execute()) {
      $pesan = "Data berhasil diperbarui!";
      $pesan_tipe = "success";
      
      // Update data lokal untuk tampilan
      $user['nama'] = $nama_baru;
      
      // Redirect ke dashboard setelah 2 detik
      header("Refresh: 2; url=dashboard.php");
    } else {
      $pesan = "Gagal memperbarui data: " . $stmt_update->error;
      $pesan_tipe = "error";
    }
    $stmt_update->close();
  }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Pengguna</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .container { max-width: 500px; margin: 0 auto; }
    form { background-color: #f9f9f9; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
    input[type="text"], input[type="password"] { width: 100%; padding: 8px; margin: 8px 0; box-sizing: border-box; }
    button { padding: 10px 20px; margin-right: 10px; cursor: pointer; }
    .pesan { padding: 10px; margin-bottom: 15px; border-radius: 5px; }
    .pesan.success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    .pesan.error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
    a { color: #0066cc; text-decoration: none; }
    a:hover { text-decoration: underline; }
  </style>
</head>

<body>
  <div class="container">
    <h2>Edit Data Pengguna</h2>
    
    <?php if ($pesan != ""): ?>
      <div class="pesan <?= $pesan_tipe ?>">
        <?= htmlspecialchars($pesan) ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="">
      <div>
        <label for="nama">Nama Pengguna:</label>
        <input type="text" id="nama" name="nama" value="<?= htmlspecialchars($user['nama']) ?>" required>
      </div>

      <div>
        <label for="password">Password Baru (Min. 6 Karakter):</label>
        <input type="password" id="password" name="password" placeholder="Masukkan password baru" required>
      </div>

      <div>
        <button type="submit" name="update">Simpan Perubahan</button>
        <a href="dashboard.php"><button type="button">Batal</button></a>
      </div>
    </form>
  </div>
</body>

</html>