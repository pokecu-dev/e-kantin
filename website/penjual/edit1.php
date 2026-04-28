<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>KantinKita</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #ff7a00;
          
        }
        .container{
            margin-top: 20px;
        }

        .teks {
            font-family: 'Poppins', sans-serif;

            /* font-family: "Poppins";
            font-size: 32px;
            font-family: Poppins; */
            font-weight: 200;
            font-style: Light;
            font-size: 32px;
            padding: 20px;
            line-height: 29px;
            letter-spacing: -2%;

        }

        .teks b {
            font-weight: 550;
            /* bold */
        }



        /* CSS FOTO KANTIN  */
        .parent {
            font-family: 'Poppins', sans-serif;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));

            /* justify-content: center;
    /* horizontal 
    align-items: center; */
            /* flex-wrap: wrap; */

        }

        .div1 {
             gap: 8px;
            /* grid-row: span 1 / span 1; */
            width: 100%;
            color: black;
            justify-content: center;
            text-align: center;
        }

        /* .div1 a {
            display: block;
            text-align: center;
            text-decoration: none;
            color: white;
            background-color: #B09B83;
            padding: 10px 25px 10px 25px;
            font-size: 20px;
            border-radius: 50px;
            place-items: center;
            justify-self: center;

        } */

        .div1 img {

            width: 100%;
            height: auto;
            object-fit: cover;
            border-radius: 20px;
            display: block;

        }

        .nama {
            font-size: 14px;
            font-weight: 600;
            margin-top: 6px;
        }

        .rating {
            font-size: 12px;
            color: #777;
        }

        .harga {
            color: #ff7a00;
            font-weight: 700;
            font-size: 14px;
        }

        .btn {
            background: #ff7a00;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 8px;
            margin-top: 5px;
            cursor: pointer;
        }

        .card-menu {
            width: 90%;
            background-color: white;
            border-radius: 10px;
            align-items: center;
            display: flex;
            flex-direction: column;
            padding: 10px;
            margin: 8px;
            gap: 8px;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
        }

        .text {
            font-family: 'Poppins', sans-serif;
        }

       

        .kantin-btn {
            position: absolute;
            top: 75%;
            left: 50%;
            transform: translate(-50%, -50%);
            /* Pas di tengah */
            background: rgba(230, 126, 34, 0.9);
            /* Oranye agak transparan */
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 12px;
            font-weight: 700;
            cursor: pointer;
            white-space: nowrap;
        }

        /* Sembunyikan scrollbar untuk Chrome, Safari dan Opera */
        

        /*
        }

    

        /* .menu-menu {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        } */
        /* 
        .menu-card {
            width: 420px;
            background-color: white;
            color: black;
            border-radius: 20px;
            padding: 30px;
            margin: 40px auto;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        } */



        .kategori {
            padding: 10px;
            display: flex;

        }

        .kat-btn {
            flex: 1;
            display: flex;
            border-radius: 10px;
            border: none;
            margin: 5px;
            align-items: center;
            justify-content: center;
            background-color: #fff;
        }

        .kat-btn span {
            font-family: 'Poppins', sans-serif;
            font-size: 16px;
            padding: 0 5px 0 0;
        }

        .kat-btn img {
            width: 45px;
            height: auto;
        }

        .btn a{
            text-decoration: none;
            color: #fff;
        }

        @media (max-width: 880px) {

            .parent {
                /* display: grid;
                grid-template-columns: repeat(2, 1fr);
                padding: 10px; */
                scroll-behavior: smooth;
            }

            .div1 {

                width: 100%;
                max-width: 300px;

            }

            .slide {
                min-width: 100%;
                height: 180px;
                border-radius: 20px;
                position: relative;
                overflow: hidden;
                scroll-snap-align: center;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            }
        }
    </style>
</head>

<body>


    <!-- LOGO -->
     <div class="logo-mobile">
        <img src="../../source/website1/icon/logo.svg" alt="KantinKita">
    </div>

    <div class="logo-desktop">
        <img src="../../source/website1/icon/logo1.svg" alt="KantinKita">
    </div>
    <!-- --------/LOGO------------ -->
    <!-- --------/LOGO------------ -->
    <div class="top-nav">
        <nav class="menu">
            <a href="penjual.php">
                <img src="../../source/website1/icon/pesanan2.svg" alt=" home"> <span class="nav-teks">History</span>
            </a>
            <a href="edit1.php">
                <img src="../../source/website1/icon/edit1.svg" alt=""><span class="nav-teks">Edit</span>
            </a>
            <a href="#">
                <img src="../../source/website1/icon/user1.svg" alt=""><span class="nav-teks">Profil</span>
            </a>
        </nav>

    </div>


    <div class="container">

       

        <div class="kategori">
            <button class="kat-btn">
                <img src="./../source/website1/icon/makanan.svg"> <span> Makanan</span>
            </button>
            <button class="kat-btn">
                <img src="./../source/website1/icon/minuman.svg"> <span> Minuman</span>
            </button>
            <button class="kat-btn">
                <img src="./../source/website1/icon/snack.svg"> <span> Camilan</span>
            </button>
        </div>

        <!-- -----------SLIDE--------------  -->
        <!-- -----------/  SLIDE--------------  -->
    </div>


<div class="parent">

   <div class="card-menu">
        <div class="div1">
        <img src="../../source/website1/fotomenu/mieayam.jpg">
        <h3 class="nama"> batagor </h3>
        <p class="rating">Rating: 5.5 ★★★</p>
        <span class="harga"> Rp 10k</span>
       <button class="btn"><a href="editproduk">Edit</a></button>
        </div>
    </div>
     <div class="card-menu">
        <div class="div1">
        <img src="../../source/website1/fotomenu/mieayam.jpg">
        <h3 class="nama"> batagor </h3>
        <p class="rating">Rating: 5.5 ★★★</p>
        <span class="harga"> Rp 10k</span>
       <button class="btn"><a href="editproduk">Edit</a></button>
        </div>
    </div>
   <div class="card-menu">
        <div class="div1">
        <img src="../../source/website1/fotomenu/mieayam.jpg">
        <h3 class="nama"> batagor </h3>
        <p class="rating">Rating: 5.5 ★★★</p>
        <span class="harga"> Rp 10k</span>
       <button class="btn"><a href="editproduk">Edit</a></button>
        </div>
    </div>
    <div class="card-menu">
        <div class="div1">
        <img src="../../source/website1/fotomenu/mieayam.jpg">
        <h3 class="nama"> batagor </h3>
        <p class="rating">Rating: 5.5 ★★★</p>
        <span class="harga"> Rp 10k</span>
       <button class="btn"><a href="editproduk">Edit</a><button>
        </div>
    </div>
    <div class="card-menu">
        <div class="div1">
        <img src="../../source/website1/fotomenu/mieayam.jpg">
        <h3 class="nama"> batagor </h3>
        <p class="rating">Rating: 5.5 ★★★</p>
        <span class="harga"> Rp 10k</span>
       <button class="btn"><a href="editproduk">Edit</a></button>
        </div>
    </div>
    </div>
</body>

</html>