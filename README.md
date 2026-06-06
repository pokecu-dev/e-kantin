# e-kantin

adalah website buatan kelompok 2

untuk memulai

- pastikan sudah mempunyai docker

jalankan

- docker-compose up -d --build

Pertama Kali Clone

1. git clone
2. bikin .env (copy dari .env.example, isi valuenya)
3. docker-compose up -d --build
4. tunggu container jalan
5. jalanin script import →git
6. buka localhost:8080

Kalau Pull

> fe / be push perubahan PHP doang
> → git pull
> → langsung refresh browser, selesai ✅ (karena volume)

> fe / be push update docker-compose.yml
> → git pull
> → docker-compose up -d
> → selesai ✅

> fe / be push update Dockerfile
> → git pull
> → docker-compose up -d --build
> → selesai ✅

> fe / be push update init.sql (struktur DB berubah)
> → git pull
> → ./script_DB/import_db.sh
> → selesai ✅

## struktur:

penjelasan docker(ai)
syntax

1. Menjalankan & Membangun

   docker-compose up -d : Membangun dan menjalankan semua layanan (PHP & DB). Wajib dipakai saat pertama kali mulai.
   docker-compose up -d --build : Sama seperti di atas, tapi memaksa Docker menginstal ulang "jeroan" server (pakai ini jika kamu mengubah isi Dockerfile).

2. Mengontrol (Sehari-hari)

   docker-compose stop : Mematikan mesin sementara. RAM laptop jadi lega, tapi kontainer dan data tetap aman (Tutup Toko).
   docker-compose start : Menyalakan kembali mesin yang sudah di-stop. Sangat cepat (Buka Toko).
   docker-compose restart: Mematikan lalu menyalakan ulang secara otomatis.

3. Membersihkan
   docker-compose down : Menghapus semua kontainer dan jaringan proyek. Hati-hati: Data database hilang jika kamu tidak pakai fitur volumes.
   docker-compose down -v: Menghapus semuanya, TERMASUK data di dalam volume (Bersih total sampai akar).

4. Memantau

   docker-compose ps : Melihat daftar layanan yang sedang berjalan atau mati.
   docker-compose logs -f : Melihat catatan (log) error yang terjadi di server secara real-time.

# Rekomendasi: Untuk ngoding sehari-hari, cukup gunakan stop dan start agar laptop tidak berat dan data aman.

# | jangan jalanin docker-compose down |

> tunnel
> 1.wajib isi .env dengan token
> 2.jalankan `docker compose --profile tunnel-aktif up -d --force-recreate cloudflare-tunnel`

tidak wajib untuk extension devcontainer

> untuk container
> kalau php tidak jalan,jalanin
> ctrl + c untuk membuat php/apache berhenti

ukuran media (note untuk fe:D)

HP (Portrait): Default (Tidak perlu media query)
HP (Landscape): @media (min-width: 480px) { ... }

Tablet: @media (min-width: 768px) { ... }
Laptop/Desktop Kecil: @media (min-width: 1024px) { ... }
Desktop Besar/Monitor: @media (min-width: 1200px) { ... }
Layar TV/4K: @media (min-width: 1600px) { ... }
sc:gemini ai

/script_data/ -> berisi script impor dan ekspor db
berisi 2 tipe extensi,bash dan powershell

- gunakan bash( .sh) jika memakai terminal wsl/linux/macos
- gunakan powershell( .ps1) jika memakai powershell

- fe / be :sesudah pull jalanin impor,
- fe / be(jika db update):sebelum push jalanin ekspor

> impor akan mengisi mysql_data menggunakan file init.sql,

> ekspor akan mengisi init.sql menggunakan folder mysql_data

db aseli terletak di mysql_data,karena di simpan di situ:v

### NOTE:

- JIKA SC PS1 TIDAK BISA JALAN,BUKA POWERSHELL JALAN KAN `Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser` LALU KETIK Y,JANGAN PROTES DULU,
- JIKA SC SH TIDAK JALAN,KETIK `chmod +x script/import.sh script/export.sh`
