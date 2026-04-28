<?php
require_once '../include/koneksi.php';

if (!isset($_GET['id'])) {
    echo "Menu tidak ditemukan!";
    exit;
}