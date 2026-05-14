<?php include_once "koneksi.php"; ?>


<?php
if (isset($_POST['update'])) {
  $id = (int) $_POST['id'];
  $nama = mysqli_real_escape_string($conn, $_POST['nama']);
  $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
  mysqli_query($conn, "UPDATE users SET nama='$nama', password='$password' WHERE id=$id");
  header("Location: dashboard.php");
  exit;
}
?>

<?php
$id = isset($_GET["id"]) ? $_GET["id"] : "";
if ($id == "") {
  header("location: dashboard.php");
  exit;
}

$id = mysqli_real_escape_string($conn, $id);
$query = mysqli_query($conn, "SELECT * FROM users where id = '{$id}'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
  header("location: dashboard.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit</title>
</head>

<body>
  <br />
  <br />
  <table border="1" cellspacing="0" cellpadding="5">
    <tr>
      <th>No</th>
      <th>Nama</th>
      <th>Password</th>
    </tr>
    <tr>
      <td> <?= $data["id"] ?> </td>
      <td> <?= $data["nama"] ?> </td>
      <td> <?= $data["password"] ?> </td>
    </tr>
  </table>
  <hr />
  <form action="" method="post">
    <input type="hidden" name="id" value="<?= $data['id'] ?>">
    <label for="nama">nama: </label><input type="text" name="nama" required>
    <label for="password">password: </label><input type="password" name="password" required>
    <button type="submit" name="update">update</button>
  </form>
  <form action="dashboard.php" method="post"><button type="submit" name="cancel">cancel</button></form>
</body>

</html>