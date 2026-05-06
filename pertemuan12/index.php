<?php
include "koneksi.php";

if (isset($_POST['tambah'])) {
  $nama = mysqli_real_escape_string($conn, $_POST['nama']);
  $npm = mysqli_real_escape_string($conn, $_POST['npm']);
  mysqli_query($conn, "INSERT INTO mahasiswa (nama, npm) VALUES ('$nama', '$npm')");
  header("Location: index.php");
  exit;
}

if (isset($_GET['hapus'])) {
  $id = (int) $_GET['hapus'];
  mysqli_query($conn, "DELETE FROM mahasiswa WHERE id=$id");
  header("Location: index.php");
  exit;
}

if (isset($_POST['update'])) {
  $id = (int) $_POST['id'];
  $nama = mysqli_real_escape_string($conn, $_POST['nama']);
  $npm = mysqli_real_escape_string($conn, $_POST['npm']);
  mysqli_query($conn, "UPDATE mahasiswa SET nama='$nama', npm='$npm' WHERE id=$id");
  header("Location: index.php");
  exit;
}

$data = mysqli_query($conn, "SELECT * FROM mahasiswa ORDER BY id DESC");
?>


<!DOCTYPE html>
<html>

<head>
  <title>CRUD Mahasiswa</title>
</head>

<body>
  <h2>Data Mahasiswa</h2>

  <form method="POST">
    Nama: <input type="text" name="nama" required>
    NPM: <input type="text" name="npm" required>
    <button type="submit" name="tambah">Tambah</button>
  </form>
  <br>

  <table border="1" cellspacing="0" cellpadding="5">
    <tr>
      <th>No</th>
      <th>Nama</th>
      <th>NPM</th>
      <th>Aksi</th>
    </tr>
    <?php $no = 1;
    while ($row = mysqli_fetch_assoc($data)): ?>
      <tr>
        <td><?= $no++ ?></td>
        <td>
          <span class="data-v-<?= $row['id'] ?>"><?= htmlspecialchars($row['nama']) ?></span>
          <input type="text" name="nama" value="<?= htmlspecialchars($row['nama']) ?>" class="data-e-<?= $row['id'] ?>"
            form="form-<?= $row['id'] ?>" style="display:none;" required>
        </td>
        <td>
          <span class="data-v-<?= $row['id'] ?>"><?= htmlspecialchars($row['npm']) ?></span>
          <input type="text" name="npm" value="<?= htmlspecialchars($row['npm']) ?>" class="data-e-<?= $row['id'] ?>"
            form="form-<?= $row['id'] ?>" style="display:none;" required>
        </td>
        <td>
          <a href="?hapus=<?= $row['id'] ?>" onclick="return confirm('Hapus?')" class="data-v-<?= $row['id'] ?>">Hapus</a>
          <button type="button" onclick="editMode(<?= $row['id'] ?>)" class="data-v-<?= $row['id'] ?>">Update</button>

          <form method="POST" id="form-<?= $row['id'] ?>" style="display:inline;">
            <input type="hidden" name="id" value="<?= $row['id'] ?>">
            <button type="submit" name="update" class="data-e-<?= $row['id'] ?>" style="display:none;">Save</button>
          </form>
          <button type="button" onclick="cancelMode(<?= $row['id'] ?>)" class="data-e-<?= $row['id'] ?>"
            style="display:none;">Cancel</button>
        </td>
      </tr>
    <?php endwhile; ?>
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