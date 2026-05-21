<?php

    require_once __DIR__ . "/../include/koneksi.php";
    require_once __DIR__ . "/../include/session/pembeliC.php";


    $idusers = $_SESSION['id_user'];


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
    <form id="upfile-form">
        <input type="hidden" name="id_users" value="<?= $idusers ?>">
        <label>foto</label>
        <input type="file" name="foto" >
        <button name="submit-pembeli" type="submit">ganti foto profil!</button>
    </form>

    <div id="notif"></div>

<script>
    document.getElementById("upfile-form").onsubmit = async (events) => {

        events.preventDefault();
        const dataForm = new FormData(this);
        const notif = document.getElementById("notif");
        try {

            const respon = await fetch('./../include/proses(universal)/upfile.php', {
                method: "POST",
                body: dataForm
            });

            const data = await respon.json();
            notif.innerText = data.message;
        } 
        
        catch (error) {
            console.error("Detail Error:", error);
            notif.innerText = error.message;
        }
    }
</script>

</body>
</html>