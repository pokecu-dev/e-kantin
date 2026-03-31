<?php
    
    session_start();
    if(!isset($_SESSION['status']) || $_SESSION['status'] != 'success'){
        // echo $_SESSION['status'];
        header("location: login.php");
        exit();
    }

    $nama = $_SESSION['nama_lengkap'];

    echo $nama;
    

    echo 'pembeli';



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <a href="logout.php"><button>log out</button></a>
</body>
</html>