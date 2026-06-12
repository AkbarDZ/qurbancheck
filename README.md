# 🐄 Sistem Manajemen Qurban

Aplikasi berbasis web komprehensif untuk mengelola operasional peternakan persiapan qurban. Sistem ini dirancang untuk mendigitalisasi alur kerja mulai dari pendataan hewan, rekam medis, verifikasi kelayakan syariat, hingga pemantauan logistik pakan.

## ✨ Fitur Utama

Aplikasi ini dibagi menjadi 6 modul utama:

* **🗃️ Master Data:** Pengelolaan data referensi (Tipe Ternak, Ras Ternak, Kandang, dan Kriteria Kelayakan Qurban) menggunakan antarmuka Nav-Tabs terintegrasi AJAX (CRUD dinamis).
* **🐄 Manajemen Ternak:** Pendataan identitas hewan secara mendetail termasuk nomor *eartag*, ras, dan pencatatan log pertumbuhan berat badan.
* **🏥 Kesehatan & Perawatan:** Modul rekam medis untuk mencatat riwayat penyakit, pengobatan, serta pengarsipan Surat Keterangan Kesehatan Hewan (SKKH).
* **✅ Pemeriksaan Syariat:** Sistem *Quality Control* (QC) otomatis berbasis *checklist*. Menentukan status kelayakan qurban berdasarkan hukum Islam (mendeteksi kriteria *is_fatal* cacat hewan).
* **🌾 Logistik Pakan:** Pencatatan ketersediaan stok pakan di gudang dan riwayat distribusi konsumsi pakan ke setiap kandang.
* **⚙️ Manajemen Pengguna:** Pengaturan akses akun admin dan pegawai.

## 🛠️ Teknologi yang Digunakan

* **Backend:** [Laravel](https://laravel.com/) (PHP)
* **Frontend:** HTML5, CSS3, Vanilla JavaScript (Fetch API)
* **UI Framework:** [Bootstrap 5](https://getbootstrap.com/)
* **Database:** MySQL