# Simple LMS - PSS Capstone Project

Proyek ini menyediakan lingkungan Dockerized untuk Laravel 11, termasuk PHP, Nginx, dan MySQL untuk keperluan pengembangan. Ikuti langkah-langkah di bawah untuk menjalankan proyek ini.

Proyek ini dibuat untuk memenuhi tugas UAS mata kuliah Pemrograman Sisi Server oleh Naufal Zhafif Pradipta (A11.2022.14474).

---

## Deskripsi Proyek

Aplikasi ini bertujuan untuk menyediakan platform Learning Management System (LMS) sederhana untuk mengelola data pengguna, materi kursus, dan aktivitas pembelajaran.

### **Fitur Utama**

1. **Manajemen Course**  
   Pengelolaan kursus termasuk membuat, mengedit, menghapus, dan menampilkan kursus.
2. **Course Content Management**  
   Pengelolaan materi pembelajaran di setiap kursus.
3. **Course Member Management**  
   Manajemen anggota kursus (student dan teacher).
4. **Comment System**  
   Sistem komentar untuk diskusi pada setiap kursus.

---

## **Fitur Tambahan**

### **1. Register (+1)**

-   **Fungsi**: Memungkinkan calon pengguna untuk mendaftar dengan mengisi biodata dan data login.
-   **Endpoint**:
    -   **POST** `api/v1/register` _(throttled)_

---

### **2. Course Announcements (+4)**

Fitur ini memungkinkan teacher membuat pengumuman untuk course tertentu.

-   **Endpoint**:
    -   **POST** `api/v1/announcements`
        -   Menambahkan pengumuman (hanya teacher).
    -   **GET** `api/v1/courses/{id}/announcements`
        -   Menampilkan semua pengumuman pada kursus (teacher dan student).
    -   **PUT** `api/v1/announcements/{id}`
        -   Mengedit pengumuman (hanya teacher).
    -   **DELETE** `api/v1/announcements/{id}`
        -   Menghapus pengumuman (hanya teacher).

---

### **3. Course Feedback (+4)**

Fitur untuk mengumpulkan umpan balik dari student terhadap kursus.

-   **Endpoint**:
    -   **POST** `api/v1/courses/{courseId}/feedbacks`
        -   Menambahkan feedback (student).
    -   **GET** `api/v1/courses/{courseId}/feedbacks`
        -   Menampilkan semua feedback.
    -   **GET** `api/v1/courses/{courseId}/feedbacks/{id}`
        -   Menampilkan detail feedback tertentu.
    -   **PUT** `api/v1/courses/{courseId}/feedbacks/{id}`
        -   Mengedit feedback (hanya feedback milik student).
    -   **DELETE** `api/v1/courses/{courseId}/feedbacks/{id}`
        -   Menghapus feedback (hanya feedback milik student).

---

### **4. Course Categories Management (+4)**

Fitur untuk mengelola kategori kursus dan mengaitkannya dengan kursus.

-   **Endpoint**:

    -   **POST** `api/v1/categories`
        -   Membuat kategori baru (teacher).
    -   **GET** `api/v1/categories`
        -   Menampilkan semua kategori.
    -   **GET** `api/v1/categories/{id}`
        -   Menampilkan detail kategori.
    -   **DELETE** `api/v1/categories/{id}`
        -   Menghapus kategori.

-   **Peningkatan**:
    -   **Kolom Kategori di Course**
        -   Menambahkan kolom kategori (opsional) saat membuat atau mengedit kursus.

---

### **5. API Rate Limiting (+4)**

Untuk melindungi API dari penyalahgunaan, beberapa batasan diberlakukan:

-   **Limit Register**: 1 IP hanya dapat melakukan **5 kali register** dalam sehari.
-   **Limit Comment**: 1 student hanya dapat memposting **10 komentar** dalam 1 jam.
-   **Limit Course Creation**: 1 teacher hanya dapat membuat **1 kursus** dalam sehari.
-   **Limit Content Creation**: 1 teacher hanya dapat membuat **10 konten** dalam 1 jam.

---

## Dokumentasi API

Dokumentasi API tambahan untuk fitur baru dapat diakses di: http://localhost:8080/docs/api

---

## Prasyarat

Pastikan Anda telah menginstal:

-   [Docker](https://docs.docker.com/get-docker/)
-   [Docker Compose](https://docs.docker.com/compose/install/)

## Memulai

### Langkah 1: Clone Repository

```bash
git clone https://github.com/naufalzp/simple-lms-capstone.git
cd simple-lms-capstone
```

`Pastikan Docker Engine sudah berjalan sebelum melanjutkan.`

### Langkah 2: Build dan Jalankan Kontainer Docker

Pastikan berada di direktori utama proyek (`simple-lms-capstone`) dan jalankan perintah berikut untuk membangun dan memulai kontainer:

```bash
docker-compose up -d --build
```

### Langkah 3: Atur Izin

Untuk menghindari masalah izin, atur izin untuk direktori penyimpanan (`storage`) dan cache Laravel:

```bash
docker-compose exec app chmod -R 777 /var/www/storage /var/www/bootstrap/cache
```

### Langkah 4: Konfigurasi Laravel

1. Install dependensi Laravel menggunakan Composer:

    ```bash
    docker exec simple-lms-app composer install
    ```

2. Salin file `.env.example` menjadi `.env`:

    ```bash
    docker exec simple-lms-app cp .env.example .env
    ```

3. Generate application key:

    ```bash
    docker exec simple-lms-app php artisan key:generate
    ```

### Langkah 5: Atur Konfigurasi Database

Perbarui konfigurasi database di file `.env`:

```dotenv
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=simple_lms
DB_USERNAME=simple_lms
DB_PASSWORD=laravel
```

### Langkah 6: Jalankan Migrasi

Jalankan migrasi untuk membuat tabel dan mengisi data awal:

```bash
docker exec simple-lms-app php artisan migrate --seed
```

### Mengakses Aplikasi

Setelah kontainer berjalan, Anda bisa mengakses aplikasi Laravel di: [http://localhost:8080](http://localhost:8080)

## Dokumentasi API

Dokumentasi API dapat diakses di: [http://localhost:8080/docs/api](http://localhost:8080/docs/api)

## Menghentikan Kontainer

Untuk menghentikan kontainer Docker, jalankan:

```bash
docker-compose down
```

## Perintah Tambahan

-   **Membangun Ulang Kontainer**: Jika Anda melakukan perubahan pada `Dockerfile` atau `docker-compose.yml`, Anda dapat membangun ulang kontainer dengan:

    ```bash
    docker-compose up -d --build
    ```

-   **Mengakses Kontainer PHP**: Untuk masuk ke dalam kontainer PHP dan menjalankan perintah Artisan, gunakan:

    ```bash
    docker exec -it simple-lms-app bash
    ```

-   **Mengakses Kontainer MySQL**: Untuk masuk ke dalam kontainer MySQL, gunakan:

    ```bash
    docker exec -it mysql mysql -u simple_lms -p
    ```

    Masukkan password `laravel` untuk mengakses database.

## Struktur Proyek

-   `Dockerfile`: Mendefinisikan lingkungan PHP-FPM.
-   `docker-compose.yml`: Mengatur kontainer Laravel, Nginx, dan MySQL.
-   `nginx/laravel.conf`: File konfigurasi Nginx untuk menjalankan aplikasi Laravel.
-   `mysql-data/`: Direktori penyimpanan data MySQL (dibuat otomatis oleh Docker).
