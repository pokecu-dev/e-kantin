<?php
    require_once __DIR__ . "/../include/session/adminC.php";
    require_once __DIR__ . "/../include/koneksi.php";

    // 1. Ambil data user yang BELUM memiliki kantin untuk dimasukkan ke <select>
    // Catatan: Pastikan nama kolom 'id_penjual' di tabel list_kantin sudah sesuai dengan phpMyAdmin kamu ya!
    $sql_user = "SELECT u.ID, u.NAMA_LENGKAP 
                 FROM users u
                 LEFT JOIN list_kantin k ON u.ID = k.id_penjual
                 WHERE k.id_penjual IS NULL AND u.ROLE = 'PENJUAL'"; 

    $result_user = $conn->query($sql_user);
?>

<style>
    .modal-header-title {
        font-size: 20px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 20px;
        border-bottom: 2px solid #f1f5f9;
        padding-bottom: 12px;
        text-align: left;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
        margin-bottom: 16px;
        text-align: left;
    }

    .form-group label {
        font-size: 13px;
        font-weight: 600;
        color: #64748b;
    }

    .form-group input[type="text"],
    .form-group select {
        height: 44px;
        border-radius: 12px;
        border: 1px solid #cbd5e1;
        padding-inline: 14px;
        font-family: 'Poppins', sans-serif;
        font-size: 14px;
        outline: none;
        transition: 0.2s;
        background: #f8fafc;
        width: 100%;
    }

    .form-group input[type="text"]:focus,
    .form-group select:focus {
        border-color: #F47B20;
        background: white;
        box-shadow: 0 0 0 4px rgba(244, 123, 32, 0.1);
    }

    /* Style Box Upload Foto Banner */
    .file-upload-wrapper {
        position: relative;
        width: 100%;
        height: 110px;
        border: 2px dashed #cbd5e1;
        border-radius: 12px;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        gap: 6px;
        cursor: pointer;
        background: #f8fafc;
        transition: 0.2s;
        overflow: hidden;
    }

    .file-upload-wrapper:hover {
        border-color: #F47B20;
        background: rgba(244, 123, 32, 0.02);
    }

    .file-upload-wrapper i {
        font-size: 24px;
        color: #94a3b8;
    }

    .file-upload-wrapper span {
        font-size: 12px;
        color: #64748b;
    }

    .file-upload-wrapper input[type="file"] {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        cursor: pointer;
    }

    /* Container Preview Image */
    .preview-img-container {
        display: none;
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
        background: white;
    }

    .preview-img-container img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Tombol Aksi */
    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 24px;
        border-top: 1px solid #f1f5f9;
        padding-top: 16px;
    }

    .btn-batal {
        padding: 10px 20px;
        border-radius: 999px;
        background: #f1f5f9;
        color: #64748b;
        border: none;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: 0.2s;
    }

    .btn-batal:hover {
        background: #e2e8f0;
        color: #1e293b;
    }

    .btn-simpan {
        padding: 10px 24px;
        border-radius: 999px;
        background: #F47B20;
        color: white;
        border: none;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-simpan:hover {
        background: #db6a16;
    }
</style>

<div class="modal-header-title">Tambah Outlet Baru</div>

<form action="proses_tambah_outlet.php" method="POST" enctype="multipart/form-data">
    
    <div class="form-group">
        <label for="nama_kantin">Nama Outlet / Kantin</label>
        <input type="text" name="nama_kantin" id="nama_kantin" required placeholder="Masukkan nama kantin..." autocomplete="off">
    </div>

    <div class="form-group">
        <label for="id_user">Pemilik Kantin</label>
        <select name="id_user" id="id_user" required>
            <option value="">Pilih Pemilik yang Tersedia</option>
            <?php 
            if ($result_user && $result_user->num_rows > 0) {
                while ($user = $result_user->fetch_assoc()) {
                    echo "<option value='".$user['ID']."'>".htmlspecialchars($user['NAMA_LENGKAP'])."</option>";
                }
            } else {
                echo "<option value='' disabled>Semua penjual sudah memiliki kantin</option>";
            }
            ?>
        </select>
    </div>

    <div class="form-group">
        <label>Foto Banner Outlet</label>
        <div class="file-upload-wrapper">
            <i class="fas fa-cloud-upload-alt"></i>
            <span>Klik untuk pilih foto kantin</span>
            <input type="file" id="foto_kantin" name="foto_kantin" accept="image/*" onchange="previewImage(this)" required>
            
            <div class="preview-img-container" id="previewContainer">
                <img id="imagePreview" src="" alt="Preview">
            </div>
        </div>
    </div>

    <div class="form-actions">
        <button type="button" class="btn-batal" onclick="paksaTutupModal()">Batal</button>
        <button type="submit" class="btn-simpan">
            <i class="fas fa-save"></i> Simpan Outlet
        </button>
    </div>
</form>

<script>
    function previewImage(input) {
        const file = input.files[0];
        if (file) {
            const reader = new FileReader();
            const previewContainer = document.getElementById('previewContainer');
            const imagePreview = document.getElementById('imagePreview');
            
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                previewContainer.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    }
</script>