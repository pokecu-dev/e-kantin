<?php
    require_once __DIR__ . "/../include/koneksi.php";
    require_once __DIR__ . "/../include/classes/adduserClasses/murid.php";
    // deklarasi
    $usn = $_POST['usn'];
    $pass = $_POST['pass'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $no_tlp = $_POST['no_tlp'];
    $email = $_POST['email'];
    $nisn = $_POST['nisn'];
    $kelas_input = $_POST['id_kelas'];
    $tempat_lahir = $_POST['tempat_lahir'];
    $tanggal_lahir = $_POST['tanggal_lahir'];
    $alamat_rumah = $_POST['alamat_rumah'];

    $idkelas;

    $usn = strtolower($usn);
    
    // pengecekan id kelas
    if(is_numeric($kelas_input)){
        $idkelas = $kelas_input;
    }
    else{
        $kelasstr = $conn->real_escape_string($kelas_input);

        $sql = "SELECT * FROM kelas WHERE KELAS = '$kelas_input'";
        $query = $conn->query($sql);

        if($query->num_rows > 0){
            $data = $query->fetch_assoc();
            $idkelas = $data["ID"];
        }
        else{
            echo "kelas tidak ditemukan!";
            exit;
        }
        
    }


    // new obj dari murid.php
    $muridobj = new Murid($conn);
    // add murid
    $hasil = $muridobj->add(
        $usn,
        $pass,
        $nama_lengkap,
        $no_tlp,
        $email,
        $nisn,
        $idkelas,
        $tempat_lahir,
        $tanggal_lahir,
        $alamat_rumah
        );

    if ($hasil) {
        echo "berhasil tambah murid YEYYYYYYYYY";
    }
    else{
        echo "cieee gagall <br> nih info kenapa bisa gagal: " . $conn->error;
    }

    

?>
