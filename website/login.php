<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
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

            <form id="form_login">
                <label for="username">Username</label>
                <input type="text" id="username" name="user_input" placeholder="username account" required>
                <label for="password">Password</label>
                <input type="text" id="password" name="pass" placeholder="Password account" required>
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
                
                const respon = await fetch('auth.php',{
                    method: 'POST',
                    body: form_data
                });
                
                const data = await respon.json();
                
                if (data.status === 'success') {
                    notiftes.innerText = data.status;
                    // fetch()
                    if(data.role === 'PENJUAL'){
                        window.location.href = 'penjual.php';
                    }
                    else if(data.role === 'MURID' || data.role === 'GURU'){
                        window.location.href = 'pembeli.php'
                    }
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

