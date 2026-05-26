<?php

require_once __DIR__ . "/../include/koneksi.php";
require_once __DIR__ . "/../include/session/penjualC.php";

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
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk</title>
</head>
<style>
    /* ===== EDIT FORM MODAL STYLE ===== */

    body {
        font-family: 'Poppins', sans-serif;
        background: transparent;
        margin: 0;
        padding: 0;
    }

    form {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }


    .custom-file-upload {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        height: 44px;
        background-color: #fff7ed;
        /* Warna latar orange pudar biar soft */
        border: 2px dashed #fed7aa;
        /* Border putus-putus khas upload file */
        color: #f36f21;
        border-radius: 10px;
        padding: 0 14px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        box-sizing: border-box;
        transition: all 0.2s ease;
    }

    .custom-file-upload:hover {
        background-color: #ffedd5;
        border-color: #f36f21;
    }

    /* LABEL */
    label {
        font-size: 13px;
        font-weight: 600;
        color: #334155;
    }

    /* INPUT GLOBAL */
    input[type="text"],
    input[type="number"],
    select,
    textarea {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        outline: none;
        font-size: 14px;
        transition: 0.2s;
        background: #fff;
        box-sizing: border-box;
    }

    input:focus,
    select:focus,
    textarea:focus {
        border-color: #F47B20;
        box-shadow: 0 0 0 3px rgba(244, 123, 32, 0.15);
    }

    /* TEXTAREA */
    textarea {
        min-height: 90px;
        resize: none;
    }

    /* ===== ROW LAYOUT KANAN KIRI ===== */
    .form-row-split {
        display: flex;
        gap: 16px;
        width: 100%;
    }

    .form-col-item {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    /* ===== COUNTER GROUP STYLE (Tombol +/- Menempel Input) ===== */
    .counter-group {
        display: flex;
        align-items: center;
        width: 100%;
    }

    .counter-group button {
        background: #F47B20;
        border: none;
        color: white;
        width: 40px;
        height: 41px; /* Menyesuaikan tinggi input teks default */
        font-size: 16px;
        transition: 0.2s;
        cursor: pointer;
    }

    .counter-group button.btn-minus {
        border-radius: 10px 0 0 10px;
    }

    .counter-group button.btn-plus {
        border-radius: 0 10px 10px 0;
    }

    .counter-group button:hover {
        background: #d96516;
    }

    .counter-group input {
        text-align: center;
        font-weight: 600;
        border-radius: 0 !important; /* Hilangkan radius tengah agar menyatu dengan tombol */
        border-left: none;
        border-right: none;
    }

    /* SUBMIT BUTTON */
   .submit[type="submit"] {
        margin-top: 10px;
        background: #F47B20;
        color: white;
        border: none;
        padding: 12px;
        border-radius: 12px;
        font-weight: 700;
        transition: 0.2s;
        cursor: pointer;
    }

    .submit[type="submit"]:hover {
        background: #d96516;
        transform: translateY(-1px);
    }

    /* SELECT STYLE */
    select {
        cursor: pointer;
    }

    /* NOTIF */
    #notif {
        margin-top: 10px;
        font-size: 13px;
        color: #64748b;
    }

    /* RESPONSIVE */
    @media (max-width: 480px) {
        form {
            gap: 10px;
        }

        .form-row-split {
            flex-direction: column;
            gap: 12px;
        }

        input,
        select,
        textarea {
            font-size: 13px;
        }
    }
</style>

<body>

    <form data-ajax="true" data-action="./pro_editproduk.php" data-notif="notif" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $data['ID_MENU'] ?>">

        <label>Nama Menu</label>
        <input type="text" name="nama_menu" value="<?= htmlspecialchars($data['NAMA_MENU']) ?>">

        <div class="form-row-split">
            
            <div class="form-col-item">
                <label>Harga</label>
                <div class="counter-group">
                    <button type="button" class="btn-minus" onclick="UpdateHarga(-500)">-</button>
                    <input type="number" name="harga" id="harga" value="<?= $data['HARGA'] ?>" min="500">
                    <button type="button" class="btn-plus" onclick="UpdateHarga(500)">+</button>
                </div>
            </div>

            <div class="form-col-item">
                <label>Stok</label>
                <div class="counter-group">
                    <button type="button" class="btn-minus" onclick="UpdateStock(-1)">-</button>
                    <input type="number" name="stok" id="stok" value="<?= $data['STOK'] ?>" min="0">
                    <button type="button" class="btn-plus" onclick="UpdateStock(1)">+</button>
                </div>
            </div>

        </div>

        <label>Kategori</label>
        <select name="kategori">
            <option <?= $data['KATEGORI'] == 'makanan' ? 'selected' : '' ?>>makanan</option>
            <option <?= $data['KATEGORI'] == 'minuman' ? 'selected' : '' ?>>minuman</option>
            <option <?= $data['KATEGORI'] == 'snack' ? 'selected' : '' ?>>snack</option>
        </select>


        <label>Status</label>
        <select name="status" id="status">
            <option value="tersedia" <?= $data['STATUS'] == 'tersedia' ? 'selected' : '' ?>>tersedia</option>
            <option value="habis" <?= $data['STATUS'] == 'habis' ? 'selected' : '' ?>>habis</option>
        </select>

        <label>Deskripsi</label>
        <textarea name="desk"><?= htmlspecialchars($data['DESK']) ?></textarea>

        <label>Foto Menu (Kosongkan jika tidak diubah)</label>
        <input type="hidden" name="type" value="photo-menu">

        <label for="edit_foto" class="custom-file-upload">
            <i class="fa-solid fa-cloud-arrow-up"></i> <span id="file-chosen">Pilih Foto Menu...</span>
        </label>

        <input type="file" name="upfile" id="edit_foto" accept="image/*" style="display: none;" onchange="updateFileName(this)">


        <div class="action-group-modal" style="margin-top: 15px; border-top: 1px solid #e2e8f0; padding-top: 10px;">
            <div class="modal-action-row" style="display: flex; gap: 12px; margin-top: 15px; width: 100%;">

                <button type="submit" class="submit" style="flex: 2; height: 48px; justify-content: center; margin-top: 0;">
                    <i class="fas fa-save"></i> Simpan
                </button>

                <a href="process/proses_nonaktif.php?id=<?= urlencode($data['ID_MENU']); ?>"
                    onclick="return confirm('⚠️ Yakin ingin menghapus menu ini? Produk tidak akan tampil lagi di daftar menu.');"
                    style="flex: 1; display: flex; align-items: center; justify-content: center; gap: 8px; height: 48px; background: #fee2e2; color: #dc2626; border-radius: 12px; text-decoration: none; font-weight: 600; font-size: 14px; transition: 0.2s;">
                    <i class="fas fa-trash-alt"></i> Hapus
                </a>

            </div>
        </div>
    </form>

    <div id="notif"></div>

    <script src="./../shared/js/script.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {

            const inputSTOCK = document.getElementById("stok");
            const inputHARGA = document.getElementById("harga");
            const inputSTATUS = document.getElementById("status");

            const hargaAwal = parseInt(inputHARGA.value) || 500;

            function statusCek(StokSekarang) {
                if (StokSekarang <= 0) {
                    inputSTATUS.value = "habis";
                } else {
                    inputSTATUS.value = "tersedia";
                }
            }

            window.UpdateHarga = function(step) {
                let newValH = (parseInt(inputHARGA.value) || 0) + step;
                if (newValH >= 500 && newValH % 500 == 0) {
                    inputHARGA.value = newValH;
                }
            }

            window.UpdateStock = function(step) {
                let newValS = (parseInt(inputSTOCK.value) || 0) + step;
                if (newValS >= 0) {
                    inputSTOCK.value = newValS;
                    statusCek(newValS);
                }
            }

            inputSTOCK.oninput = (e) => {
                let target = e.target;
                let Value = parseInt(target.value);

                if (target.value === "" || isNaN(Value) || Value < 0) {
                    target.value = 0;
                    Value = 0;
                }
                statusCek(Value);
            }

            inputHARGA.oninput = (e) => {
                let target = e.target;
                let Value = parseInt(target.value);

                if (target.value === "" || isNaN(Value) || Value < 500) {
                    target.value = 500;
                } else if (Value % 500 != 0) {
                    // Opsional: jika ingin otomatis mengembalikan ke harga awal jika kelipatan tidak pas saat diketik manual
                    // target.value = hargaAwal;
                }
            }
        });

        function updateFileName(input){
            const fileNameDisplay = document.getElementById('file-chosen');
            if(input.files.length > 0){
                fileNameDisplay.textContent = input.files[0].name;
            } else {
                fileNameDisplay.textContent = 'Pilih Foto Menu...';
            }
        }
    </script>
</body>

</html>