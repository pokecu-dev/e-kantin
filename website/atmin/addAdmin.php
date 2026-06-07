<?php
    require_once __DIR__ . "/../include/session/adminC.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
   <style>
           .text{
            text-align: center;
            margin-bottom: 5px;
            font-size: 2rem;
        }

        .note {
            text-align: center;
            font-size: 12px;
            color: #64748B;
            margin-bottom: 15px;
        }

        .yaya {
            font-size: 13px;
            font-weight: 600;
            display: block;
            margin-top: 10px;
        }

        .input-box {
            width: 100%;
            padding: 10px;
            border: none;
            border-bottom: 1px solid #CBD5E1;
            outline: none;
            transition: 0.2s;
        }

        .input-box:focus {
            border-bottom: 2px solid #F47B20;
        }

        .pass-box {
            display: flex;
            align-items: center;
        }

        .pass-box button {
            border: none;
            background: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn[type="submit"] {
            width: 100%;
            margin-top: 20px;
            padding: 10px;
            border: none;
            border-radius: 20px;
            background: #F47B20;
            color: white;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn[disabled] {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .auth-footer {
            text-align: center;
            margin-top: 12px;
            font-size: 13px;
            color: #64748B;
        }

        .auth-footer a {
            color: #F47B20;
            text-decoration: none;
            font-weight: 600;
        }


        /* Mengubah ukuran saat layar di bawah 768px (Tablet & HP) */
        @media (max-width: 768px) {
            .text {
                font-size: 1.6rem;
            }
        }
   </style>
</head>
<body>
    <h2 class="text">Tambah Admin</h2>
    <form data-ajax="true" data-action="./process/pro_addAdmin.php" data-notif="notif">
        
        <label class="yaya">Username</label>
        <input type="text" name="usn" class="input-box" required>

        <label class="yaya">Password</label>
        <input type="password" name="pass" class="input-box" required>

        <label class="yaya">Nama Lengkap</label>
        <input type="text" name="nama_lengkap" class="input-box" required>

        <label class="yaya">Nomor telpeon</label>
        <input type="text" id="no_tlp" name="no_tlp" class="input-box" required>

        <label class="yaya">Email</label>
        <input type="email" name="email" class="input-box" required>

        <button type="submit" class="btn">Tambah</button>

    </form>

    <div id="notif"></div>

    <script src="./../shared/js/script.js"></script>
    <script>
        document.getElementById("no_tlp").addEventListener('input', function(e) {
            this.style.borderBottom = '2px solid #F47B20'
            document.getElementById('notif').innerHTML = '';
            let value = this.value;
            let hasPlus = value.startsWith('+');

            let numbers = value.replace(/\D/g, '');

            let formatted = '';

            if (hasPlus) formatted += '+';
            else formatted += '+';

            if (numbers.length > 0) {
              formatted += numbers.substring(0, 2);
            }
            if (numbers.length > 2) {
              formatted += ' ' + numbers.substring(2, 5);
            }
            if (numbers.length > 5) {
              formatted += ' ' + numbers.substring(5, 9);
            }
            if (numbers.length > 9) {
              formatted += ' ' + numbers.substring(9, 13);
            }

            this.value = formatted;

            }) 
    </script>
</body>
</html>

