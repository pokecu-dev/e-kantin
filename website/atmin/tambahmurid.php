<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>test tambah murid</title>
</head>
<body>
    <h2>Form Test Tambah Murid</h2>
    <p>note:usn akan di ubah menjadi huruf kecil semua</p>
    <form action="./proaddmurid.php" method="POST">
        
        <label>Username</label><br>
        <input type="text" name="usn"><br><br>

        <label>Password</label><br>
        <input type="text" name="pass"><br><br>

        <label>Nama Lengkap</label><br>
        <input type="text" name="nama_lengkap"><br><br>

        <label>Nomor telpeon</label><br>
        <input type="text" name="no_tlp"><br><br>

        <label>Email</label><br>
        <input type="email" name="email"><br><br>

        <label>NISN</label><br>
        <input type="text" name="nisn"><br><br>

        <label>Kelas</label><br>
        <input list="kelas_list" name="id_kelas" placeholder="Cari kelas...(x,xi,xii)"><br>
        <datalist id="kelas_list">
            <?php
            require_once __DIR__ . '/include/koneksi.php';
            $sql = "SELECT * FROM KELAS";
            $query = $conn->query($sql);
            while($kelas = $query->fetch_assoc()):
            ?>
                <option value="<?= $kelas['KELAS'] ?>">
                    <?= $kelas['KELAS'] ?>
                </option>
            <?php endwhile; ?>
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