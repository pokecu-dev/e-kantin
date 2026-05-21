<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scaleable=no">
    <title>Register Upgrade</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<style>
 * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #F5F5F5;
            justify-content: center;

            background-color: #F5F5F5;
            min-height: 100vh;
            display: flex;
        }

        .page {
            min-height: 100vh;
            display: flex;
            width: 80%;
            max-width: 600px;
            justify-content: center;
            align-items: center;
            position: relative;
            /* padding-top: 60px; kasih ruang buat logo */
        }

        .logo img {
            max-width: 27vw;
            max-height: 60px;
            min-width: 150px;
            height: auto;


        }

        .logo {

            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
        }


        .card {

            align-items: center;
            justify-content: center;
            width: 100%;
            max-width: 600px;

            max-height: 85%;
            background: white;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        h2 {
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

        label {
            font-size: 13px;
            font-weight: 600;
            display: block;
            margin-top: 10px;
        }

        input {
            width: 100%;
            padding: 10px;
            border: none;
            border-bottom: 1px solid #CBD5E1;
            outline: none;
            transition: 0.2s;
        }

        input:focus {
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

        button[type="submit"] {
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

        button[disabled] {
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
            h2 {
                font-size: 1.6rem;
            }
        }

    /* TOAST */
    /* .toast {
        position: fixed;
        top: 20px;
        right: 20px;
        background: #1E293B;
        color: white;
        padding: 12px 16px;
        border-radius: 10px;
        opacity: 0;
        transform: translateY(-10px);
        transition: 0.3s;
    }

    .toast.show {
        opacity: 1;
        transform: translateY(0);
    } */
</style>

<body>

    <div class="page">

        <div class="logo">
            <img src="/source/icon/logo1.svg" alt="logo">
        </div>

        <div class="toast" id="toast"></div>

        <div class="card">
            <h2>Daftar</h2>
            <p class="note">username otomatis jadi huruf kecil</p>

            <form id="form" data-ajax="true" data-action="./pro_regis.php" data-notif="notif">

                <label>Username</label>
                <input type="text" name="usn" id="usn">

                <label>Password</label>
                <div class="pass-box">
                    <input type="password" name="pass" id="pass">

                </div>

                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" id="nama">

                <label>No Telepon</label>
                <input type="text" name="no_tlp" id="telp">

                <label>Email</label>
                <input type="email" name="email" id="email">

                <button type="submit" id="btn">Daftar</button>

            </form>

            <p class="auth-footer">
                Sudah punya akun? <a href="./login.php">Login</a>
            </p>
        </div>

    </div>


</body>

</html>