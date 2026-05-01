<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <style>
        *{
            color:black

       }
.container{
    margin-top: 40px;
}

.card{
    background-color:white;
}
    </style>
</head>

<body>
    <div >
    <div class="logo-mobile">
        <img src="../../source/website1/icon/logo.svg" alt="KantinKita">
    </div>

    <div class="logo-desktop">
        <img src="../../source/website1/icon/logo1.svg" alt="KantinKita">
    </div>

    <div class="top-nav">
        <nav class="menu">
            <a href="penjual.php">
                <img src="../../source/icon/pesanan2.svg" alt=" home"> <span class="nav-teks">History</span>
            </a>
            <a href="edit1.php">
                <img src="../../source/icon/edit1.svg" alt=""><span class="nav-teks">Edit</span>
            </a>
            <a href="profil.php">
                <img src="../../source/icon/user1.svg" alt=""><span class="nav-teks">Profil</span>
            </a>
        </nav>
        </div>
<div class="container">
    <div class="header">
        <img src="./../source/fotopengguna/mbakyaya.jpg" width="80" style="border-radius: 50%;">
        <div style="margin-left: 20px;">
            <h2>Mbak Yaya</h2>
            <p>PENJUAL</p>
        </div>
    </div>

    <div class="main-content">
        <div>
            <div class="card">
                <h3>Personal Information</h3>
                <div class="input-group">
                    <label>Email Address</label>
                    <input type="text" value="aris.setiawan@example.com" readonly>
                </div>
                <div class="input-group">
                    <label>Shipping Address</label>
                    <input type="text" value="Jl. Sudirman No. 123, Jakarta" readonly>
                </div>
            </div>

            <div class="card">
                <h3>Recent Orders</h3>
                <div class="order-item">
                    <span>UltraBoost Sneakers</span>
                    <span>Rp 2.499.000</span>
                </div>
            </div>
        </div>

        <div>
            <div class="card">
                <h3>Preferences</h3>
                <p>Push Notifications: [ON]</p>
                <p>Email Marketing: [OFF]</p>
                <hr>
                <p style="color: red; cursor: pointer;">Keluar Sesi</p>
            </div>
        </div>
    </div>
</div>

    </div>
</body>

</html>