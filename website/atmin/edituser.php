<?php

    require_once __DIR__ . "/../include/koneksi.php";
    session_start();
    if ($_SESSION['role'] != 'ADMIN') {
        header('Location: ../login.php');
        exit;
    }

    $id = $conn->real_escape_string($_GET['id']);

    $sql = "select * from users where ID='$id'";
    
    $query = $conn->query($sql);
    if ($query->num_rows > 0) {
        $dataUsers = $query->fetch_assoc();
    }
    echo $dataUsers['ROLE'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>
        edit user:D
    </h2>
    <form action="./pro_edit.php" method="post">
        <!-- data umum:D -->
         <label>Username(BEFORE: <?= $dataUsers['USERNAME'] ?>)</label><br>
        <input type="text" name="usn" value="<?= $dataUsers['USERNAME'] ?>"><br><br>

        <label>Password(BEFORE: <?= $dataUsers['PASS'] ?>)</label><br>
        <input type="text" name="pass" value="<?= $dataUsers['PASS'] ?>"><br><br>

        <label>Nama Lengkap(BEFORE: <?= $dataUsers['NAMA_LENGKAP'] ?>)</label><br>
        <input type="text" name="nama_lengkap" value="<?= $dataUsers['NAMA_LENGKAP'] ?>"><br><br>

        <label>Nomor telpon</label><br>
        <input type="text" name="no_tlp" value=""><br><br>

        <label>Email</label><br>
        <input type="email" name="email"><br><br>


        <!-- data setiap role:D -->
        <?php 
        ?>
        <label>NISN</label><br>
        <input type="text" name="nisn"><br><br>

        <label>Kelas</label><br>
        <input list="kelas_list" name="id_kelas" placeholder="Cari kelas...(x,xi,xii)"><br>
        <datalist id="kelas_list">
            <!-- <?php
                require_once __DIR__ . '/../include/koneksi.php';
                $sql = "SELECT * FROM KELAS";
                $query = $conn->query($sql);
                while($kelas = $query->fetch_assoc()):
                ?>
                <option value="<?= $kelas['KELAS'] ?>">
                    <?= $kelas['KELAS'] ?>
                </option>
            <?php endwhile; ?> -->
        </datalist><br><br>

        <label>Tempat Lahir</label><br>
        <input type="text" name="tempat_lahir"><br><br>
 
        <label>Tanggal Lahir</label><br>
        <input type="date" name="tanggal_lahir"><br><br>

        <label>Alamat Rumah</label><br>
        <textarea name="alamat_rumah" rows="3"></textarea><br><br>

        <button type="submit">Tambah Murid</button>
    </form>
</body>
</html>