<?php
    require_once __DIR__ . "/../include/session/adminC.php";
    require_once __DIR__ . "/../include/koneksi.php";


    // 1. Ambil data user yang BELUM memiliki kantin untuk dimasukkan ke <select>
    $sql_user = "SELECT u.ID, u.NAMA_LENGKAP 
                 FROM users u
                 LEFT JOIN list_kantin k ON u.ID = k.id_penjual
                 WHERE k.id_penjual IS NULL AND u.ROLE = 'PENJUAL'"; 

    $result_user = $conn->query($sql_user);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Outlet Kantin</title>
    </head>
<body>

    <h2>Tambah Outlet Baru</h2>

    <form action="proses_tambah_outlet.php" method="POST">
        
        <label for="nama_kantin">Nama Outlet / Kantin</label>
        <input type="text" name="nama_kantin" id="nama_kantin" required placeholder="Masukkan nama kantin...">

        <label for="id_user">Pemilik Kantin</label>
        <select name="id_user" id="id_user" required>
            <option value="">Pilih Pemilik yang Tersedia</option>
            <?php 
            if ($result_user && $result_user->num_rows > 0) {
                while ($user = $result_user->fetch_assoc()) {
                    echo "<option value='".$user['ID']."'>".$user['NAMA_LENGKAP']."</option>";
                }
            } else {
                echo "<option value='' disabled>Semua penjual sudah memiliki kantin</option>";
            }
            ?>
        </select>

        <button type="submit">Simpan Outlet</button>
    </form>

</body>
</html>