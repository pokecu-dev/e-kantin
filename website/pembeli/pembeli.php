<?php
    
    session_start();
    if(!isset($_SESSION['status']) || $_SESSION['status'] != 'success'){
        // echo $_SESSION['status'];
        header("location: ../login.php");
        exit();
    }

    $nama = $_SESSION['nama_lengkap'];

    echo $nama . '<br> <br>';
    

    echo ' sebagai pembeli';



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <br>
    <br>
    <h2>tombol log out</h2>
    <!-- <a href="/logout.php"><button>log out</button></a> -->
    <a href="../logout.php"><button>log out</button></a>

    <br>
    <p>tes up file</p>
    <form action="upfile.php" method="post" enctype="multipart/form-data">
        <label for="myfile">pilih file:</label>
        <input type="file" id="myfile" name="filename">
        <input type="submit" value="unggah">
    </form>
    
</body>
</html>