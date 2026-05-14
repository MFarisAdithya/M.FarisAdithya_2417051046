<?php
session_start();
if (!isset($_SESSION['nama'])) {
  header("Location: index.php");
  exit();
}

include_once "koneksi.php";

if (isset($_POST['tambah'])) {
  $nama = mysqli_real_escape_string($conn, $_POST['nama']);
  $password = mysqli_real_escape_string($conn, $_POST['password']);
  mysqli_query($conn, "INSERT INTO users (nama, password) VALUES ('$nama', '$password')");
  header("Location: dashboard.php");
  exit;
}

if (isset($_GET['hapus'])) {
  $id = (int) $_GET['hapus'];
  mysqli_query($conn, "DELETE FROM users WHERE id=$id");
  header("Location: dashboard.php");
  exit;
}

$data = mysqli_query($conn, "SELECT * FROM users ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
</head>

<body>
  <h2>Selamat Datang di Dashboard</h2>
  <p>Halo, <?php echo htmlspecialchars($_SESSION['nama']); ?>!</p>
  <a href="logout.php"><button>Logout</button></a>
  <hr />
  <?php if (str_contains($_SESSION['nama'], 'admin')): ?>
    <form method="POST">
      Nama: <input type="text" name="nama" required>
      Password: <input type="text" name="password" required>
      <button type="submit" name="tambah">Tambah</button>
    </form>
    <br />
    <br />
    <table border="1" cellspacing="0" cellpadding="5">
      <tr>
        <th>No</th>
        <th>Nama</th>
        <th>Password</th>
        <th>Hapus</th>
        <th>Edit</th>
      </tr>
      <?php $no = 1;
      while ($row = mysqli_fetch_assoc($data)): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td>
            <span class="data-v-<?= $row['id'] ?>"><?= htmlspecialchars($row['nama']) ?></span>
          </td>
          <td>
            <span class="data-v-<?= $row['id'] ?>"><?= htmlspecialchars($row['password']) ?></span>
          </td>
          <td>
            <a href="?hapus=<?= $row['id'] ?>" onclick="return confirm('Hapus?')" class="data-v-<?= $row['id'] ?>">Hapus</a>
          </td>
          <td>
            <a href="edit.php?id=<?= $row['id'] ?>" class="data-v-<?= $row['id'] ?>">Edit</a>
          </td>
        </tr>
      <?php endwhile; endif; ?>
  </table>
  <script>
    function editMode(id) {
      document.querySelectorAll('.data-v-' + id).forEach(element => element.style.display = 'none');
      document.querySelectorAll('.data-e-' + id).forEach(element => element.style.display = 'inline-block');
    }

    function cancelMode(id) {
      document.querySelectorAll('.data-e-' + id).forEach(element => element.style.display = 'none');
      document.querySelectorAll('.data-v-' + id).forEach(element => element.style.display = 'inline-block');
    }
  </script>
</body>

</html>