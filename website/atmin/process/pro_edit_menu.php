<?php
    ob_start();
    require_once __DIR__ . "/../../include/proses(universal)/upfile.php";
    require_once __DIR__ . "/../../include/koneksi.php";

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        header('Content-Type: application/json');

        // echo $output;

        try {
            $id_menu = $_POST['id'] ?? null;
            $nama_menu = $_POST['nama_menu'] ?? null;
            $kategori = $_POST['kategori'] ?? null;
            $harga = $_POST['harga'] ?? null;
            $stok = $_POST['stok'] ?? null;

            // if (!$id_menu || !$nama_menu || !$kategori || !$harga || !$stok) {
            //     throw new Exception("Semua field (Nama, Kategori, Harga, Stok) harus diisi.");
            // }

            $sql_update_menu = "UPDATE tb_menu SET NAMA_MENU = ?, KATEGORI = ?, HARGA = ?, STOK = ? WHERE ID_MENU = ?";
            $stmt = $conn->prepare($sql_update_menu);
            $stmt->bind_param("ssiii", $nama_menu, $kategori, $harga, $stok, $id_menu);

            if (!$stmt->execute()) {
                throw new Exception("Gagal mengupdate data menu: " . $stmt->error);
            }
            $stmt->close();

            ob_clean();
            echo json_encode([
                'status' => 'success',
                'message' => 'Data menu berhasil diperbarui!'
            ]);

        } catch (Exception $e) {
            ob_clean();
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    } else {
        ob_clean();
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid request method.'
        ]);
    }
?>
