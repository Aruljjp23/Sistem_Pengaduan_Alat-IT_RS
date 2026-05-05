# Sistem Pengaduan Alat IT Rumah Sakit

Sistem Pengaduan Alat IT Rumah Sakit merupakan aplikasi berbasis web yang digunakan untuk mempermudah proses pelaporan kerusakan perangkat IT di lingkungan rumah sakit. Sistem ini memungkinkan pengguna (user) untuk mengirim laporan pengaduan dan admin untuk mengelola serta menindaklanjuti laporan tersebut.

## Fitur Utama

### User
- Mengirim pengaduan kerusakan alat IT
- Melihat status pengaduan
- Riwayat laporan

### Admin
- Login admin
- Dashboard monitoring pengaduan
- Kelola data pengaduan
- Update status pengaduan
- Detail laporan

## Teknologi yang Digunakan

- PHP
- MySQL / MariaDB
- HTML, CSS, JavaScript
- Bootstrap
- SweetAlert

## Persyaratan Sistem

Pastikan sudah terinstall:

- Laragon
- PHP >= 7.x
- MySQL / MariaDB
- Web Browser (Chrome / Edge)

## Cara Instalasi

1. **Clone Repository**
   git clone https://github.com/Aruljjp23/Sistem_Pengaduan_Alat-IT_RS.git
   
3. **Pindahkan ke Folder Laragon**
   - Letakkan project ke folder :
     C:\laragon\www\
     
4. **Buat Database**
   - Jalankan Laragon → klik Start All
   - Buka :
     http://localhost/phpmyadmin
   - Buat database :
     sistem_pengaduan
     
5. **Import Database**
   - Import file sipitrs.sql yang ada di project ke database tadi
     
4. **Konfigurasi Database**
   - Buka file config.php atau .env
   - Sesuaikan :
     DB_HOST=localhost
     DB_USER=root
     DB_PASS=
     DB_NAME=sistem_pengaduan

## Tujuan Sistem
- Mempermudah pelaporan kerusakan alat IT
- Mempercepat penanganan masalah
- Mengelola data pengaduan secara terpusat

## Author
**Arul Jeconiah Jaya Pratama**

Fullstack Developer
