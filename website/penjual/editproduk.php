<?php

    require_once __DIR__ . "/../include/koneksi.php";

    $search_user = isset($_GET['id']) ? $_GET['id'] : '';

    if (!empty($search_user)) {
        $keyword = "%" . $search_user . "%";

        // Query mengambil semua kolom kecuali PASS sesuai gambar
        // Kita cari berdasarkan USERNAME, NAMA_LENGKAP, atau ID
        $sql = "SELECT * FROM tb_menu 
                WHERE ID_MENU = ? ";

        $stmt = $conn->prepare($sql);
        $id_search = is_numeric($search_user) ? intval($search_user) : 0;
        $stmt->bind_param("i", $id_search);

        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();
        
        
        $stmt->close();
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    /* ===== EDIT FORM MODAL STYLE ===== */

body{
    font-family: 'Poppins', sans-serif;
    background: transparent;
}

form{
    display: flex;
    flex-direction: column;
    gap: 12px;
}

/* LABEL */
label{
    font-size: 13px;
    font-weight: 600;
    color: #334155;
}

/* INPUT GLOBAL */
input[type="text"],
input[type="number"],
select,
textarea{
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    outline: none;
    font-size: 14px;
    transition: 0.2s;
    background: #fff;
}

input:focus,
select:focus,
textarea:focus{
    border-color: #F47B20;
    box-shadow: 0 0 0 3px rgba(244,123,32,0.15);
}

/* TEXTAREA */
textarea{
    min-height: 90px;
    resize: none;
}

/* ===== BUTTON GROUP (stok & harga + / -) ===== */
button{
    cursor: pointer;
}

.jumlah{
    display: flex;
    flex-direction: column;
    gap: 8px;
}

/* wrapper tombol +/- */
.jumlah button,
button[type="button"]{
    background: #F47B20;
    border: none;
    color: white;
    width: 36px;
    height: 36px;
    border-radius: 10px;
    font-size: 16px;
    transition: 0.2s;
}

.jumlah button:hover,
button[type="button"]:hover{
    background: #d96516;
}

/* input angka dalam row */
.jumlah input{
    text-align: center;
}

/* wrapper +/- harga */
#harga,
#stok{
    text-align: center;
    font-weight: 600;
}

/* SUBMIT BUTTON */
button[type="submit"]{
    margin-top: 10px;
    background: #F47B20;
    color: white;
    border: none;
    padding: 12px;
    border-radius: 12px;
    font-weight: 700;
    transition: 0.2s;
}

button[type="submit"]:hover{
    background: #d96516;
    transform: translateY(-1px);
}

/* SELECT STYLE */
select{
    cursor: pointer;
}

/* NOTIF */
#notif{
    margin-top: 10px;
    font-size: 13px;
    color: #64748b;
}

/* RESPONSIVE */
@media (max-width: 480px){
    form{
        gap: 10px;
    }

    input, select, textarea{
        font-size: 13px;
    }
}
</style>
<body>

    <form data-ajax="true" data-action="./pro_editproduk.php" data-notif="notif">
        <input type="hidden" name="id_menu" value="<?= $data['ID_MENU'] ?>">

        <label>Nama Menu</label>
        <input type="text" name="nama_menu" value="<?= htmlspecialchars($data['NAMA_MENU']) ?>">

        <label>Harga</label>
        <!-- <input type="number" name="harga" value=""> -->
        <button type="button" onclick="UpdateHarga(-500)">-</button>
        <input type="number"  name="harga" id="harga" value="<?= $data['HARGA'] ?>" min="1">
        <button type="button" onclick="UpdateHarga(500)">+</button>
         
        
        <div class="jumlah">
            <label >stok:D</label>
            <button type="button" onclick="UpdateStock(-1)">-</button>
            <input type="number" name="stok" id="stok" value="<?= $data['STOK'] ?>" min="0" >
            <button type="button" onclick="UpdateStock(1)">+</button>
         </div>


        <label>Kategori</label>
        <select name="kategori">
            <option <?= $data['KATEGORI']=='makanan'?'selected':'' ?>>makanan</option>
            <option <?= $data['KATEGORI']=='minuman'?'selected':'' ?>>minuman</option>
            <option <?= $data['KATEGORI']=='snack'?'selected':'' ?>>snack</option>
        </select>

        
        <label>status</label>
        <select name="status" id="status">
            <option value="tersedia" <?= $data['STATUS']=='tersedia'?'selected':'' ?>>tersedia</option>
            <option value="habis" <?= $data['STATUS']=='habis'?'selected':'' ?>>habis</option>
        </select>

        <label>desk</label>
        <textarea name="desk"><?= $data['DESK'] ?></textarea>

        <button type="submit">submit</button>
    <!-- stok, status, desk, foto preview + upload -->
    </form>

    <div id="notif">

    </div>
    <script src="./../shared/js/script.js"></script>
    <script>

        document.addEventListener("DOMContentLoaded", () => {

            const inputSTOCK = document.getElementById("stok");
            const inputHARGA = document.getElementById("harga");
            const inputSTATUS = document.getElementById("status");
       
            const hargaAwal = parseInt(inputHARGA.value) || 0;
            console.log(inputSTATUS);

            function statusCek(StokSekarang){
                if(StokSekarang <= 0){
                    inputSTATUS.value = "habis";
                }
                else{
                    inputSTATUS.value = "tersedia";
                }
            }

            window.UpdateHarga = function (step){

                let newValH = parseInt(inputHARGA.value) + step;

                if(newValH >= 500 && newValH % 500 == 0){
                    inputHARGA.value = newValH;
                } 

            }

            window.UpdateStock = function (step){
                let newValS = parseInt(inputSTOCK.value) + step;

                // inputSTOCK.value = newValS;
                if (newValS >= 0) {
                    inputSTOCK.value = newValS;
                    statusCek(newValS);
                }
            }

            inputSTOCK.oninput = (e) => {
                let target = e.target;
                let Value = parseInt(target.value);
                // let currentStock = getstock();

                if(target.value === "" || isNaN(Value) || Value < 1){
                    target.value = 0;
                    Value = 0;
                    
                }

                statusCek(Value);
                
            }

            inputHARGA.oninput = (e) => {
                let target = e.target;
                let Value = parseInt(target.value);

                let tmp = Value;

                
                if(target.value === "" || isNaN(Value) || Value < 500){
                    target.value = 500;
                    Value = 500;
                }

                else if(Value % 500 != 0){
                    target.value = hargaAwal;
                }
            }



        });



        const inputSTOCK = document.getElementById("stok");
        const inputHARGA = document.getElementById("harga");
        const inputSTATUS = document.getElementById("status");
        // const getstock =() => parseInt(document.getElementById("stok").innerText);

        console.log(inputSTATUS)

            
        inputSTOCK.oninput = (e) => {
            let Value = parseInt(e.value);
            // let currentStock = getstock();

            if(e.value === "" || isNaN(Value) || Value < 1){
                e.value = 0;
            }
            else if(Value == 0){

            }

        }

    </script>
</body>
</html>