<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    
    session_start();
    require_once __DIR__ . '/include/classes/user.php';
    include 'include/koneksi.php';

        
    

    $username_in = $_POST['user_input'];
    $pass_in = $_POST['pass'];
    
    $userOBJ = new user($conn);
    $userdata = $userOBJ->get_data($username_in,$pass_in);

    header('content-type: application/json');

    if ($userdata) {
        // $_SESSION['status'] = 'login';
        $_SESSION['status'] = 'success';
        $_SESSION['id_user'] = $userdata['ID'];
        $_SESSION['nama_lengkap'] = $userdata['NAMA_LENGKAP'];
        $_SESSION['role'] = $userdata['ROLE'];
        
        echo json_encode([
            'status' => 'success',
            'role' => $userdata['ROLE']
            ]);
        
        
        
    }
    else{
        echo json_encode(['status' => 'error', 'message' => 'Username/Password salah bwang!']);
    }
        
          
/*
    // $user = strtolower($user);
    // $query = "SELECT * FROM user WHERE USERNAME = '$user'";

    // $result = mysqli_query($conn,$query);
    // $pm = new pembeli();

    // if (mysqli_num_rows($result) > 0) {
        // $data = mysqli_fetch_assoc($result);

        // if($pass == $data['PASS']){
            // echo "selamat datang " . $data['NAMA_LENGKAP'] . "!";
            
            
        // }

        // else{
            // echo "info login salah";
        // }
    // }
    // else{
        // echo "info login salah";
        // }
*/

?>
