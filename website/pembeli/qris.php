<?php 

    require_once __DIR__ . "/../include/koneksi.php";
    require_once __DIR__ . "/../include/session/pembeliC.php";

    $id_kantin = isset($_GET['id_kantin']) ? (int)$_GET['id_kantin'] : 0;
    $id_user = $_SESSION['id_users'] ?? 0;
    $trx = isset($_GET['trx']) ? (int)$_GET['trx'] : 0;

    $sql = "SELECT * FROM list_kantin WHERE id = $id_kantin";
    $query = $conn->query($sql);
    $result = $query->fetch_assoc();

    $qris = $result['QRIS'] ?? '';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    <?php if($qris): ?>

            <img src="/source/qris/<?= $qris ?>" alt="">
            <br>
            <a href="./pembeli.php"><- kembali ke beranda</a>
            <a href="./struckdigital.php?trx=<?= $trx ?>">struk -> </a>

        <?php else:?>
            <p>kantin ini tidak menyediakan metode qris!</p>
            <a href="./pembeli.php"><- kembali ke beranda</a>
    
    <?php endif ?>



</body>
</html>