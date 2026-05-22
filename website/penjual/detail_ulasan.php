<?php
require_once '../include/koneksi.php';

$id_menu = $_GET['id'] ?? '';

if (empty($id_menu)) {
    echo "<div style='padding:20px; text-align:center;'>ID Menu tidak valid.</div>";
    exit();
}

// Query mengambil nama menu untuk judul modal
$q_menu = mysqli_query($conn, "SELECT NAMA_MENU FROM tb_menu WHERE ID_MENU = '$id_menu'");
$data_menu = mysqli_fetch_assoc($q_menu);

// Query ambil ulasan dari tabel rating di-join ke tabel users untuk tau siapa yang ngasih ulasan
// Query ambil ulasan dari tabel rating di-join ke tabel users
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
    <h2 style="margin-bottom: 16px; font-size: 18px; border-bottom: 2px solid #f5f5f5; padding-bottom: 10px; color: #333;">
        <i class="fas fa-star" style="color: #F47B20;"></i> Daftar Ulasan: <span style="color: #F47B20;"><?= htmlspecialchars($data_menu['NAMA_MENU'] ?? 'Produk') ?></span>
    </h2>

    <div class="reviews-list" style="max-height: 450px; overflow-y: auto; display: flex; flex-direction: column; gap: 14px; padding-right: 5px;">
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($review = mysqli_fetch_assoc($result)): ?>
                <div class="review-item" style="background: #fafafa; padding: 14px; border-radius: 12px; border: 1px solid #eaeaea;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                        <strong style="font-size: 14px; color: #444;">
                            <i class="fas fa-user-circle"></i> @<?= htmlspecialchars($review['username'] ?? 'User') ?>
                        </strong>
                        <span style="font-size: 13px; color: #F47B20; font-weight: bold;">
                            <i class="fas fa-star"></i> <?= number_format($review['RATING'], 1) ?>
                        </span>
                    </div>
                    <p style="font-size: 13px; color: #666; line-height: 1.4; font-style: italic;">
                        "<?= htmlspecialchars($review['DESK'] ?? 'Tidak ada deskripsi ulasan.') ?>"
                    </p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div style="text-align: center; padding: 30px; color: #aaa; font-size: 14px;">
                <i class="fas fa-comment-slash" style="font-size: 24px; margin-bottom: 8px; display: block;"></i>
                Belum ada ulasan untuk menu ini.
            </div>
        <?php endif; ?>
    </div>
</div>