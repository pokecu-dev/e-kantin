<?php

    require_once __DIR__ . "/../../include/koneksi.php";

    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        try {
            header('Content-Type: application/json');

            $id = $_POST['id'] ?? null;
            $username = trim($_POST['usn'] ?? '');
            $pass = $_POST['pass'] ?? '';
            $nama_lengkap = $_POST['nama_lengkap'] ?? '';
            $no_tlp = $_POST['no_tlp'] ?? '';
            $email = $_POST['email'] ?? '';
            $status = $_POST['status'] ?? '';

            if(empty($id) || empty($username)){
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Username dan ID tidak boleh kosong!'
                ]);
                exit;
            }

            if(!empty($pass)){
                
                $pass = password_hash($pass, PASSWORD_DEFAULT);
    
                $sql = "UPDATE users SET USERNAME= ? , PASS = ? , NAMA_LENGKAP = ? , NO_TLP = ? , EMAIL = ? , STATUS = ? WHERE ID = ? ";
                
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssssi", $username, $pass, $nama_lengkap, $no_tlp, $email, $status, $id);
                
                if($stmt->execute()){
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'Data pengguna berhasil diperbarui!'
                    ]);
                } else {
                    // Memicu catch jika execute mengembalikan false akibat duplicate key/error lain
                    throw new Exception($stmt->error);
                }
                $stmt->close();

            } else {
                $sql = "UPDATE users SET USERNAME= ? , NAMA_LENGKAP = ? , NO_TLP = ? , EMAIL = ? , STATUS = ? WHERE ID = ? ";
                
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssssi", $username, $nama_lengkap, $no_tlp, $email, $status, $id);
                
                if($stmt->execute()){
                    echo json_encode([
                        'status' => 'success',
                        'message' => 'Data pengguna berhasil diperbarui!'
                    ]);
                } else {
                    // Memicu catch jika execute mengembalikan false akibat duplicate key/error lain
                    throw new Exception($stmt->error);
                }
                $stmt->close();
            }

        }
        catch(Exception $e){
            $error_msg = $e->getMessage();
            $pesan_custom = 'Gagal memperbarui data.';

            // Deteksi jika penyebab gagalnya karena username/email kembar (Duplicate Entry)
            if (str_contains($error_msg, 'Duplicate entry')) {
                $pesan_custom = "Username '@$username' sudah terdaftar di sistem! Silakan gunakan nama lain.";
            } else {
                $pesan_custom .= ' ' . $error_msg;
            }

            echo json_encode([
                'status' => 'error',
                'message' => $pesan_custom
            ]);
        }
    }
?>