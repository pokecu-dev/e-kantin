<?php
    require_once __DIR__ . "/../include/session/adminC.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
   
    </style>
</head>
<body>
    <form data-ajax="true" data-action="./process/pro_addAdmin.php" data-notif="notif">
        
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

        <button type="submit">submit</button>

    </form>

    <div id="notif"></div>

    <script src="./../shared/js/script.js"></script>
</body>
</html>

