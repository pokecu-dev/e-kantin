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

        <label>Nomor telpon(BEFORE: <?= $dataUsers['NO_TLP'] ?>)</label><br>
        <input type="text" name="no_tlp" value="<?= $dataUsers['NO_TLP'] ?>"><br><br>

        <label>Email(BEFORE: <?= $dataUsers['EMAIL'] ?>)</label><br>
        <input type="email" name="email" value="<?= $dataUsers['EMAIL'] ?>"><br><br>


        <!-- data setiap role:D -->
        <?php 
            switch ($dataUsers['ROLE']) :
                case 'MURID':
                    $sql = "SELECT * FROM MURID WHERE ID_USER='$id'";
                    $query = $conn->query($sql);
                    $datatable = $query->fetch_assoc();
                    $kelastmp = $datatable['ID_KELAS'];
                    $sql = "SELECT * FROM KELAS WHERE ID='$kelastmp'";
                    $query = $conn->query($sql) ;
                    $hasiltmp = $query->fetch_assoc();
                    $kelastmp = $hasiltmp['KELAS'];
                    

        ?>
        <label>NISN(BEFORE:<?= $datatable['NISN'] ?>)</label><br>
        <input type="text" name="nisn" value="<?= $datatable['NISN'] ?>"><br><br>

        <label>Kelas(BEFORE:<?= $kelastmp ?>)</label><br>
        <input list="kelas_list" name="id_kelas" placeholder="Cari kelas...(x,xi,xii)"><br>
        <datalist id="kelas_list">
            <?php  
                $sql = "SELECT * FROM kelas";
                $query = $conn->query($sql);
                
                while($kelas = $query->fetch_assoc()):
            ?>
                <option value="<?= $kelas['KELAS'] ?>">
                    <?= $kelas['KELAS'] ?>
                </option>

            <?php  endwhile;?>
        </datalist><br><br>

        <label>Tempat Lahir(BEFORE:<?= $datatable["TEMPAT_LAHIR"] ?>)</label><br>
        <input type="text" name="tempat_lahir" value="<?= $datatable["TEMPAT_LAHIR"] ?>"><br><br>
 
        <label>Tanggal Lahir(BEFORE:<?= $datatable["TANGGAL_LAHIR"] ?>)</label><br>
        <input type="date" name="tanggal_lahir" value="<?= $datatable["TANGGAL_LAHIR"] ?>" ><br><br>

        <label>Alamat Rumah(BEFORE:<?= $datatable["ALAMAT_RUMAH"] ?>)</label><br>
        <textarea name="alamat_rumah" rows="3" ><?= $datatable["ALAMAT_RUMAH"] ?></textarea><br><br>

        

        <?php 
            break;
            case 'GURU';
        ?>

        <input type="text">
        <h1>hai</h1>

        <?php endswitch; ?>
        <button type="submit">submit</button>
    </form>
</body>
</html>