

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <form action="iyah.php" method="post" enctype="multipart/form-data">
    
        <input type="file" name="UpFile" id="UpFile">
        <input type="submit" value="Upload File" name="submit">
    </form>
</body>
</html>


<?php
    require_once __DIR__ . "/include/classes/upfile/upfile.php";

    if(isset($_POST['submit'])){

        
    }

   
?>