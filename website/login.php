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
            justify-content: center;

            background-color: #F5F5F5;
            min-height: 100vh;
            display: flex;


        }

        .logo img {
            max-width: 27vw;
            max-height: 60px;
            min-width: 150px;
            height: auto;


        }

        .logo {
            margin: 40px 0 65px 0;
            align-items: center;
            position: absolute;
            padding-top: 0;
            justify-content: center;
        }

        .card {
            width: 100%;
            max-width: 420px;
            max-height: 85%;
            background: #FFFfff;
            color: black;
            box-shadow: 0 2px 3px #aeaeae;
            border-radius: 20px;
            padding: 30px 45px;
            border: 1px solid #652f05;
            justify-content: center;
            align-items: center;
            margin-top: 130px;

            /* justify-content: center;
                display: grid;
                place-items: center;
                background-color: white;
                margin: 10vh auto;
                width: 65vw;
                height: 50vh; */
            /* align-items: center; */
            /* display: flex; */
            /* margin-top: 10vh; */

        }

        .card h1 {
            font-size: 36px;
            text-align: center;

        }

        .card label {
            font-size: 15px;
            padding-top: 30px;
        }

        .card .input-box {
            width: 100%;
            height: 50px;
            margin: 30px 0;
        }

        .input-box input {
            width: 100%;
            height: 100%;
            background: transparent;
            border: none;
            outline: none;
            border-bottom: 2px solid black;
            padding: 20px 45px 20px 20px;
        }

        .btn {
            width: 100%;
            border: none;
            outline: none;
            font-size: 14px;
            height: 40px;
            border-radius: 20px;
            color: white;
            margin: 20px 0 15px;
            background-color: #F47B20;
            box-shadow: 0 2px 5px #492509;
        }

        #notif {
            color: #d61010;
        }

        @media (max-width:768px) {
            .card {
                width: 85%;
                /* max-height: 100%; */
                justify-content: center;
                margin-left: auto;
                margin-right: auto;
            }

            .card label {
                margin-top: 10px;
                padding: 10px;
                font-size: 10px;
            }

            .card h1 {
                font-size: 28px;
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

    <!-- LOGO -->
    <div class="logo">
        <img src="/source/icon/logo1.svg" alt="logokita">
    </div>

    <!-- --------/LOGO------------ -->

    <main>
        <section class="card">

            <header>
                <h1>E-kantin login</h1>
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

            <div id="notif">
                <!-- berisi notif peringatan error dan sejenisnya -->
            </div>

        </section>
    </main>

    <script>
        document.getElementById('form_login').onsubmit = async (event) {

            event.preventDefault();
            const notif = document.getElementById("notif");git
            const form_data = new FormData(event.target);
            const notiftes = document.getElementById('notiftes');

            try {
                // notiftes.innerText = 'woi'

                const respon = await fetch('auth.php', {
                    method: 'POST',
                    body: form_data
                });

                const data = await respon.json();
                if (data.status === 'success') {
                    notiftes.innerText = data.status;

                    if (data.role === 'PENJUAL') {
                        window.location.href = 'penjual/penjual.php';
                    } else if (data.role === 'MURID' || data.role === 'GURU') {
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