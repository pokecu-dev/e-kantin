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
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        h2 {
            margin: 8px;

        }

        .conatiner {
            display: flex;
            background: white;
        }

        .parent {
            display: grid;
          
            gap: 25px;
          grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        }

        .card {
            background-color: white;
            padding: 24px;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
.right-column {
    /* Mengambil area kanan */
    grid-area: right;
    
    /* Agar di dalam kanan ini bisa berjejer 3 kolom */
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 15px;
}
.left-column{
    justify-content: center;
    align-items: center;
    max-height: 300px;
    margin-top: 50px;
}

        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            box-sizing: border-box;
        }

        label {
            font-weight: 600;
            color: #475569;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            .left-column{
                margin: 0;
            }
        }

        .btn {
            width: 100%;
            border: none;
            outline: none;
            font-size: 14px;
            height: 40px;
            border-radius: 20px;
            color: white;
            margin: 20px 0 15px;
            background-color: #F47B20;
            box-shadow: 0 2px 5px #492509;
        }
    </style>
</head>

<body>
    <h2>
        EDIT USER
    </h2>
    <div class="container">
        <form action="./pro_edit.php" method="post">
            <!-- data umum:D -->
            <div class="parent">
                <div class="left-column">
                <div class="card">
                    <label>Username</label>
                    <input type="text" name="usn" value="<?= $dataUsers['USERNAME'] ?>">
                    <label>Password</label>
                    <input type="text" name="pass" value="<?= $dataUsers['PASS'] ?>">
                </div>
                </div>
                <div class=".right-column">
                <div class="card full-width">>
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" value="<?= $dataUsers['NAMA_LENGKAP'] ?>">

                    <label>Nomor telpon</label>
                    <input type="text" name="no_tlp" value="<?= $dataUsers['NO_TLP'] ?>">

                    <label>Email</label>
                    <input type="email" name="email" value="<?= $dataUsers['EMAIL'] ?>">
                </div>

                <!-- data setiap role:D -->
                <?php
                switch ($dataUsers['ROLE']):
                    case 'MURID':
                        $sql = "SELECT * FROM MURID WHERE ID_USER='$id'";
                        $query = $conn->query($sql);
                        $datatable = $query->fetch_assoc();
                        $kelastmp = $datatable['ID_KELAS'];
                        $sql = "SELECT * FROM KELAS WHERE ID='$kelastmp'";
                        $query = $conn->query($sql);
                        $hasiltmp = $query->fetch_assoc();
                        $kelastmp = $hasiltmp['KELAS'];


                ?>
                        <div class="card full-width">>
                            <label>NISN</label>
                            <input type="text" name="nisn" value="<?= $datatable['NISN'] ?>">

                            <label>Kelas</label>
                            <input list="kelas_list" name="id_kelas" placeholder="Cari kelas...(x,xi,xii)">
                            <datalist id="kelas_list">
                                <?php
                                $sql = "SELECT * FROM kelas";
                                $query = $conn->query($sql);

                                while ($kelas = $query->fetch_assoc()):
                                ?>
                                    <option value="<?= $kelas['KELAS'] ?>">
                                        <?= $kelas['KELAS'] ?>
                                    </option>

                                <?php endwhile; ?>
                            </datalist>
                        </div>
                        <div class="card full-width">>
                            <label>Tempat Lahir</label>
                            <input type="text" name="tempat_lahir" value="<?= $datatable["TEMPAT_LAHIR"] ?>">

                            <label>Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" value="<?= $datatable["TANGGAL_LAHIR"] ?>">

                            <label>Alamat Rumah</label>
                            <textarea name="alamat_rumah" rows="3"><?= $datatable["ALAMAT_RUMAH"] ?></textarea>
                         <button type="submit" class="btn">SUBMIT</button>
                        </div>


                    <?php
                        break;
                    case 'GURU';
                    ?>

                        <input type="text">
                        <h1>hai</h1>

                <?php endswitch; ?>
                </div>
            </div>
          


        </form>
    </div>
</body>

</html>