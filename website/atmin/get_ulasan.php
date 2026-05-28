<?php
require_once '../include/koneksi.php'; // Pastikan di dalam ini nama variabelnya adalah $conn

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aksi']) && $_POST['aksi'] === 'hapus') {
    header('Content-Type: application/json');
    
    $id_ulasan = intval($_POST['id_ulasan']);
    
    // Sudah disesuaikan dengan variabel $conn, nama tabel 'rating', dan primary key 'ID_RATING'
    $query = mysqli_query($conn, "DELETE FROM rating WHERE ID_RATING = $id_ulasan");
    
    if ($query) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($conn)]);
    }
    exit; // Berhenti di sini jika yang dipanggil adalah proses hapus
}

$id_menu = $_GET['id'] ?? '';

if (empty($id_menu)) {
    echo "<div style='padding:20px; text-align:center; color:#64748b;'>ID Menu tidak valid.</div>";
    exit();
}

$q_menu = mysqli_query($conn, "SELECT NAMA_MENU FROM tb_menu WHERE ID_MENU = '$id_menu'");
$data_menu = mysqli_fetch_assoc($q_menu);

$query_review = "
    SELECT r.*, u.username 
    FROM rating r
    LEFT JOIN users u ON r.ID_USER = u.ID
    WHERE r.ID_MENU = ?
    ORDER BY r.ID_RATING DESC
";

$stmt = mysqli_prepare($conn, $query_review);
mysqli_stmt_bind_param($stmt, "i", $id_menu);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

?>
<div class="review-modal-container">
    <h2 style="margin-bottom: 16px; font-size: 18px; border-bottom: 2px solid #f5f5f5; padding-bottom: 10px; color: #333; font-family: 'Poppins', sans-serif;">
        <i class="fas fa-star" style="color: #F47B20;"></i> Daftar Ulasan: <span style="color: #F47B20;"><?= htmlspecialchars($data_menu['NAMA_MENU'] ?? 'Produk') ?></span>
    </h2>

    <div class="reviews-list" style="max-height: 380px; overflow-y: auto; display: flex; flex-direction: column; gap: 14px; padding-right: 5px; font-family: 'Poppins', sans-serif;">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($review = mysqli_fetch_assoc($result)): ?>
                
                <div class="review-item" style="background: #fafafa; padding: 14px; border-radius: 12px; border: 1px solid #eaeaea; position: relative;">
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px; padding-right: 40px;">
                        <strong style="font-size: 14px; color: #444;">
                            <i class="fas fa-user-circle"></i> @<?= htmlspecialchars($review['username'] ?? 'User') ?>
                        </strong>
                        <span style="font-size: 13px; color: #F47B20; font-weight: bold;">
                            <i class="fas fa-star"></i> <?= number_format($review['RATING'], 1) ?>
                        </span>
                    </div>
                    
                    <p style="font-size: 13px; color: #666; line-height: 1.4; font-style: italic; margin-bottom: 12px; padding-right: 10px;">
                        "<?= htmlspecialchars($review['DESK'] ?? 'Tidak ada deskripsi ulasan.') ?>"
                    </p>

                    <div style="position: absolute; right: 14px; bottom: 8px;">
                        <a href="javascript:void(0);" 
                           onclick="prosesHapusUlasan(<?= $review['ID_RATING'] ?>, <?= $id_menu ?>)" 
                           style="color: #dc3545; font-size: 12px; text-decoration: none; font-weight: 600; display: flex; align-items: center; gap: 4px;">
                            <i class="fas fa-trash-alt"></i> Hapus
                        </a>
                    </div>
                </div>

            <?php endwhile; ?>
        <?php else: ?>
            <div style="text-align: center; padding: 40px 20px; color: #aaa; font-size: 14px;">
                <i class="fas fa-comment-slash" style="font-size: 28px; margin-bottom: 8px; display: block; color: #cbd5e1;"></i>
                Belum ada ulasan untuk menu ini.
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function prosesHapusUlasan(idRating, idMenu) {
    if (confirm("Apakah Anda yakin ingin menghapus ulasan dari user ini?")) {
        
        fetch(`hapus_ulasan.php?id_rating=${idRating}&id_menu=${idMenu}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert(data.message);
                
                bukaModalUlasan(idMenu); 
            } else {
                alert("Gagal: " + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan koneksi database.');
        });
    }
}
</script>