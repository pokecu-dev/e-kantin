<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scaleable=no">
    <title>Document</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

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

        /* body {
            font-size: 0.5em;
        }

        section {
            
            justify-content: center;
            display: grid;
            place-items: center;
            background-color: grey;
            margin: 10vh auto;
            width: 65vw;
            height: 50vh;
            
            align-items: center;
            display: flex;
            margin-top: 10vh;
    
        } */
    </style>

</head>

<body>
    <div class="page">
        <!-- LOGO -->
        <div class="logo">
            <img src="/source/icon/logo1.svg" alt="logokita">
        </div>

        <!-- --------/LOGO------------ -->


        <div class="card">

            <header>
                <h2>E-kantin login</h2>
            </header>

            <div id="notiftes">
                <!-- tes -->
            </div>

            <!-- <section>
                <h2>hai</h2>
            </section> -->

            <form id="form_login">
                <label for="username">Username</label>
                <br>
                <div class="input-box">
                    <input type="text" id="username" name="user_input" placeholder="username account" required>

                </div>
                <br>


                <label for="password">Password</label>

                <br>
                <div class="input-box">
                    <input type="password" id="password" name="pass" placeholder="Password account" required>

                </div>
                <br>

                <button type="submit" class="btn" id="btn">Login</button>
            </form>
            <p class="auth-footer">
                Belum punya akun?
                <a href="./register.php" class="auth-link">Daftar!</a>
</p>
            <div id="notif">
                <!-- berisi notif peringatan error dan sejenisnya -->
            </div>


        </div>

    </div>
    <script>
        document.getElementById('form_login').onsubmit = async (event) => {

            event.preventDefault();
            const notif = document.getElementById("notif");
            const form_data = new FormData(event.target);
            const notiftes = document.getElementById('notiftes');

            try {

                const respon = await fetch('auth.php', {
                    method: 'POST',
                    body: form_data
                });

                const data = await respon.json();
                if (data.status === 'success') {
                    // notiftes.innerText = data.status;

                    if (data.role === 'PENJUAL') {
                        window.location.href = 'penjual/penjual.php';
                    } else if (data.role === 'PEMBELI') {
                        window.location.href = 'pembeli/pembeli.php'
                    } else if (data.role === "ADMIN") {
                        window.location.href = 'atmin/admin.php'
                    } else {
                        notif.innerText = "role tidak dikenali!";
                    }
                } else {

                    notif.innerText = data.message;
                }


            } catch (error) {
                notif.innerText = "error" + error.message;
            }

        }
    </script>

</body>

</html>