<?php

    require_once __DIR__ . "/../include/koneksi.php";

    if ($conn->error) {
        echo $conn->connect_error;
    }

    $sql = "SELECT * FROM users";
    $query = $conn->query($sql);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <header>
        <h1>
            Welcome to E-canteen EsemKita
        </h1>
    </header>
    <main>
        <section>
            <a href="./login.php"><button>Login</button></a>
            <a href="./tambahmurid.php"><button>tambah murid</button></a>
            <a href="./addAdmin.php"><button>tambah admin</button></a>
            <a href="./addPenjual.php"><button>tambah penjual</button></a>
            <a href="./../logout.php"><button>log out</button></a>
        </section>

        
        <h2>BUAT TES TAMPIL DATA!</h2>
        <table border="1">
            <tr>
                <th>ID</th>
                <th>USERNAME</th>
                
                <th>NAMA LENGKAP</th>
                <th>NO TLP</th>
                <th>EMAIL</th>
                <th>ROLE</th>
                <th>AKSI</th>
            </tr>
        
            <?php while($user = $query->fetch_assoc()): ?>
        
            <tr>
                <td><?= $user['ID'] ?></td>
                <td><?= $user['USERNAME'] ?></td>
                <td><?= $user['NAMA_LENGKAP'] ?></td>
                <td><?= $user['NO_TLP'] ?></td>
                <td><?= $user['EMAIL'] ?></td>
                <td><?= $user['ROLE'] ?></td>
                <td>
                    <a href="edituser.php?id=<?= $user['ID'] ?>">edit</a>
                </td>
                
            </tr>

            <?php endwhile; ?>
        </table>

    </main>
    
</body>
</html>