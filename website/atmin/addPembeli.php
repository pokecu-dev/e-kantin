<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>test tambah murid</title>
</head>
<body>
    <h2>Form Test Tambah Murid</h2>
    <p>note:usn akan di ubah menjadi huruf kecil semua</p>
    <form data-ajax="true" data-action="process/pro_addmurid.php" data-notif="notif" >
        
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

        <button type="submit">Tambah Murid</button>

    </form>

    <div id="notif"></div>
</body>
</html>