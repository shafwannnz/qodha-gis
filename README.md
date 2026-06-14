readme_content = """# 🗺️ Qodha GIS — Sistem Informasi Geografis Pemetaan Mitra

[![Laravel Version](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D%208.2-777bb4.svg)](https://www.php.net/)
[![License](https://img.shields.io/badge/License-MIT-blue.svg)](LICENSE)

**Qodha GIS** adalah aplikasi Sistem Informasi Geografis (GIS) berbasis web yang dibangun menggunakan framework **Laravel 11**. Proyek ini dirancang khusus untuk memvisualisasikan, mengelola, dan menganalisis data spasial lokasi mitra secara interaktif menggunakan integrasi data standar format GeoJSON.

---

## ✨ Fitur Utama

* **Peta Interaktif (Interactive Mapping):** Visualisasi koordinat geografis titik lokasi mitra secara langsung pada peta digital di browser.
* **Integrasi Data GeoJSON:** Memuat dan merender batas wilayah atau titik spasial melalui berkas dinamis `mitra.geojson`.
* **Arsitektur Layanan Terpisah (MitraFilterService):** Sistem filtrasi data mitra yang andal, dinamis, dan terisolasi di lapisan *Service* untuk performa pencarian lokasi yang optimal.
* **Sistem Manajemen Data (CRUD):** Kelola data mitra, koordinat, serta informasi pelengkap secara penuh melalui panel kendali admin.
* **Database Seeder Otomatis:** Setup lingkungan pengembangan awal secara instan dengan data demo mitra dan akun administrator langsung siap pakai.

---

## 📂 Struktur File Kustom (Core Features)

Aplikasi ini menggunakan kerangka dasar (*skeleton*) Laravel 11 dengan implementasi fitur kustom GIS pada direktori berikut: