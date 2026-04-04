<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scaleable=no">
    <title>Document</title>

    <style>
        
        @media (max-width:762px){
            
            body {
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
                
                /* align-items: center; */
                /* display: flex; */
                /* margin-top: 10vh; */
    
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
    
    <main>
        <section class="box">
            
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
                <input type="text" id="username" name="user_input" placeholder="username account" required>
                <br>
                <br>
                <label for="password">Password</label>
                <br>
                <input type="text" id="password" name="pass" placeholder="Password account" required>
                <br>
                <br>
                <button type="submit">Login</button>
            </form>
            
            <div id="notif">
                <!-- berisi notif peringatan error dan sejenisnya -->
            </div>

        </section>
    </main>
    
    <script>

        document.getElementById('form_login').onsubmit = async function (event) {
            
            event.preventDefault();
            const notif = document.getElementById("notif");
            const form_data = new FormData(this);
            const notiftes = document.getElementById('notiftes');
            
            try {
                notiftes.innerText = 'woi'
                
                const respon = await fetch('auth.php',{
                    method: 'POST',
                    body: form_data
                });
                
                const data = await respon.json();
                if (data.status === 'success') {
                    notiftes.innerText = data.status;
                    
                    if(data.role === 'PENJUAL'){
                        window.location.href = 'penjual/penjual.php';
                    }
                    else if(data.role === 'MURID' || data.role === 'GURU'){
                        window.location.href = 'pembeli/pembeli.php'
                    }
                    // else if(data.role === "ADMIN"){

                    // }
                    else{
                        notif.innerText = "role tidak dikenali!";
                    }
                }
                else{
                    notif.innerText = data.message;
                }


            }

            catch (error) {
                notif.innerText = "error" + error.message; 
            }

        }

    </script>

</body>
</html>

