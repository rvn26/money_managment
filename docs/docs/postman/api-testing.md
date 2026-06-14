# Dokumentasi Pengujian API dengan Postman (Fitur Notifikasi & Integrasi)

Dokumentasi ini menjelaskan langkah-langkah pengujian API pada backend Laravel aplikasi **Kepitink (Kelola Uang)** menggunakan Postman. Koleksi Postman siap pakai telah disediakan di dalam proyek ini untuk mempermudah proses testing.

---

## 1. Persiapan Awal

### Lokasi File Koleksi Postman
Koleksi Postman dapat ditemukan di direktori proyek Anda:
`[kelola_uang_api.postman_collection.json](file:///c:/Users/ervan/Herd/kelola_uang/docs/postman/kelola_uang_api.postman_collection.json)`

### Langkah-langkah Import ke Postman:
1. Buka aplikasi **Postman**.
2. Klik tombol **Import** di pojok kiri atas.
3. Drag & drop file `kelola_uang_api.postman_collection.json` atau cari file tersebut di direktori lokal Anda.
4. Klik **Import** untuk mengonfirmasi. Koleksi bernama **"Kepitink Money Management API"** akan muncul di sidebar kiri Anda.

---

## 2. Variabel Koleksi (Collection Variables)

Koleksi ini menggunakan fitur variabel bawaan Postman agar Anda tidak perlu mengetik ulang domain atau token autentikasi pada setiap request.

Untuk melihat atau mengedit variabel ini:
1. Klik pada nama Koleksi (**Kepitink Money Management API**).
2. Pilih tab **Variables**.
3. Di sana Anda akan melihat variabel berikut:

| Variabel | Nilai Default | Deskripsi |
| :--- | :--- | :--- |
| `base_url` | `http://127.0.0.1:8000` | Alamat server backend Laravel Anda (dapat disesuaikan jika menggunakan port/host lain). |
| `token` | *(kosong)* | Menyimpan JWT Token autentikasi. Nilai ini akan **terisi otomatis** setelah Anda menjalankan request **Login User**. |
| `notif_id` | `1` | ID notifikasi spesifik yang akan diuji (misal untuk endpoint tandai dibaca). |
| `pertemanan_id` | `1` | ID hubungan pertemanan (misal untuk menyetujui/menolak permintaan). |
| `hutang_id` | `1` | ID catatan hutang spesifik. |

---

## 3. Autentikasi Otomatis (JWT Token)

Semua endpoint kecuali **Register** dan **Login** membutuhkan autentikasi Bearer Token. Koleksi ini telah dikonfigurasi untuk menggunakan variabel `{{token}}` sebagai token Bearer di tingkat koleksi (*Collection-level Auth*).

> [!TIP]
> **Script Otomatisasi Token:**
> Request **Login User** dan **Refresh Token** memiliki script pengujian (*Tests tab*) yang secara otomatis mengambil `access_token` dari response JSON dan memperbarui variabel `{{token}}` koleksi. Anda cukup melakukan login sekali, dan semua endpoint lainnya langsung dapat diakses tanpa perlu melakukan copy-paste token secara manual.

---

## 4. Struktur Folder & Endpoint yang Tersedia

### Folder 1: Authentication
Gunakan folder ini untuk membuat akun baru dan melakukan proses login.

#### a. Register User (`POST /api/auth/register`)
Mendaftarkan akun user baru di sistem.
*   **Body (JSON):**
    ```json
    {
        "name": "Ervandi User 1",
        "email": "user1@example.com",
        "password": "password123",
        "c_password": "password123"
    }
    ```
*   **Response (200 OK):**
    ```json
    {
        "statuscode": 200,
        "msg": "User registered successfully. Please check your email to verify your account.",
        "data": {
            "user": {
                "id": 1,
                "name": "Ervandi User 1",
                "email": "user1@example.com",
                "created_at": "2026-06-12T10:00:00.000000Z"
            }
        }
    }
    ```

#### b. Login User (`POST /api/auth/login`)
Melakukan autentikasi untuk mendapatkan JWT Token.
*   **Body (JSON):**
    ```json
    {
        "email": "user1@example.com",
        "password": "password123"
    }
    ```
*   **Response (200 OK):**
    ```json
    {
        "statuscode": 200,
        "msg": "User login successfully.",
        "data": {
            "access_token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
            "token_type": "bearer",
            "expires_in": 3600
        }
    }
    ```

#### c. Get Profile (`GET /api/auth/profile`)
Mendapatkan informasi detail user yang sedang aktif.
*   **Headers:** `Authorization: Bearer {{token}}`
*   **Response (200 OK):**
    ```json
    {
        "status": "success",
        "message": "User profile fetched",
        "data": {
            "id": 1,
            "name": "Ervandi User 1",
            "email": "user1@example.com",
            "email_verified_at": "2026-06-12T10:05:00.000000Z"
        }
    }
    ```

---

### Folder 2: FCM Token Management
Digunakan untuk mendaftarkan dan menghapus token perangkat ponsel cerdas agar server Laravel dapat mengirim notifikasi FCM ke HP yang tepat.

#### a. Simpan / Update FCM Token (`POST /api/fcm-token`)
Mendaftarkan FCM Token dari perangkat Flutter ke sistem backend.
*   **Headers:** `Authorization: Bearer {{token}}`
*   **Body (JSON):**
    ```json
    {
        "token": "fcm-token-test-dummy-string-123456789",
        "device_name": "Postman Testing Client (Samsung Galaxy S24)"
    }
    ```
*   **Response (200 OK):**
    ```json
    {
        "statuscode": 200,
        "msg": "FCM token berhasil disimpan.",
        "data": {
            "id_user": 1,
            "token": "fcm-token-test-dummy-string-123456789",
            "device_name": "Postman Testing Client (Samsung Galaxy S24)",
            "updated_at": "2026-06-12T10:10:00.000000Z",
            "created_at": "2026-06-12T10:10:00.000000Z",
            "id": 1
        }
    }
    ```

#### b. Hapus FCM Token (`DELETE /api/fcm-token`)
Menghapus FCM Token tertentu (biasanya dipanggil saat user melakukan Logout dari HP).
*   **Headers:** `Authorization: Bearer {{token}}`
*   **Body (JSON):**
    ```json
    {
        "token": "fcm-token-test-dummy-string-123456789"
    }
    ```
*   **Response (200 OK):**
    ```json
    {
        "statuscode": 200,
        "msg": "FCM token berhasil dihapus.",
        "data": []
    }
    ```

---

### Folder 3: Notification History
Endpoint untuk mengambil riwayat notifikasi *in-app* yang tersimpan di database serta mengubah status dibaca.

#### a. Get Daftar Notifikasi (`GET /api/notifikasi`)
Mendapatkan semua notifikasi yang pernah masuk untuk user ini (diurutkan dari yang terbaru).
*   **Headers:** `Authorization: Bearer {{token}}`
*   **Query Params:**
    *   `page`: Halaman ke-n (default: `1`)
    *   `limit`: Jumlah data per halaman (default: `15`, maks: `100`)
*   **Response (200 OK):**
    ```json
    {
        "statuscode": 200,
        "msg": "Daftar notifikasi berhasil diambil.",
        "data": [
            {
                "id": 5,
                "id_user": 1,
                "judul": "Permintaan Pertemanan",
                "pesan": "Teman User 2 mengirim permintaan pertemanan.",
                "tipe": "pertemanan",
                "dibaca_at": null,
                "created_at": "2026-06-12T10:15:30.000000Z",
                "updated_at": "2026-06-12T10:15:30.000000Z"
            }
        ],
        "pagination": {
            "current_page": 1,
            "last_page": 1,
            "per_page": 15,
            "total": 1
        }
    }
    ```

#### b. Get Jumlah Notifikasi Belum Dibaca (`GET /api/notifikasi/belum-dibaca`)
Menampilkan angka counter notifikasi belum dibaca (badge counter).
*   **Headers:** `Authorization: Bearer {{token}}`
*   **Response (200 OK):**
    ```json
    {
        "statuscode": 200,
        "msg": "Jumlah notifikasi belum dibaca.",
        "data": {
            "count": 1
        }
    }
    ```

#### c. Tandai Satu Notifikasi Dibaca (`PUT /api/notifikasi/{id}/baca`)
Menandai notifikasi tertentu sebagai sudah dibaca (`dibaca_at` diisi timestamp saat ini).
*   **Headers:** `Authorization: Bearer {{token}}`
*   **Response (200 OK):**
    ```json
    {
        "statuscode": 200,
        "msg": "Notifikasi ditandai sudah dibaca.",
        "data": {
            "id": 5,
            "id_user": 1,
            "judul": "Permintaan Pertemanan",
            "pesan": "Teman User 2 mengirim permintaan pertemanan.",
            "tipe": "pertemanan",
            "dibaca_at": "2026-06-12T10:18:22.000000Z"
        }
    }
    ```

#### d. Tandai Semua Notifikasi Dibaca (`PUT /api/notifikasi/baca-semua`)
Menandai semua notifikasi unread milik user aktif sebagai sudah dibaca secara massal.
*   **Headers:** `Authorization: Bearer {{token}}`
*   **Response (200 OK):**
    ```json
    {
        "statuscode": 200,
        "msg": "Semua notifikasi ditandai sudah dibaca.",
        "data": []
    }
    ```

---

### Folder 4: Pertemanan (Trigger Notifikasi)
Endpoint untuk mengelola hubungan pertemanan. Aksi di folder ini akan memicu pengiriman notifikasi FCM ke HP teman yang dituju.

#### a. Cari Pengguna Lain (`POST /api/pertemanan/cari-user`)
Mencari user berdasarkan email sebelum mengirim permintaan pertemanan.
*   **Headers:** `Authorization: Bearer {{token}}`
*   **Body (JSON):**
    ```json
    {
        "email": "user2@example.com"
    }
    ```
*   **Response (200 OK):**
    ```json
    {
        "statuscode": 200,
        "msg": "Pengguna ditemukan.",
        "data": {
            "user": {
                "id": 2,
                "name": "Teman User 2",
                "email": "user2@example.com"
            },
            "friendship_status": null,
            "can_send_request": true
        }
    }
    ```

#### b. Kirim Permintaan Pertemanan (`POST /api/pertemanan/kirim`)
Mengirimkan permintaan pertemanan ke email user tujuan. Mengirim notifikasi tipe `pertemanan` ke user tersebut.
*   **Headers:** `Authorization: Bearer {{token}}`
*   **Body (JSON):**
    ```json
    {
        "email": "user2@example.com"
    }
    ```
*   **Response (201 Created):**
    ```json
    {
        "statuscode": 201,
        "msg": "Permintaan pertemanan berhasil dikirim.",
        "data": {
            "id_user": 1,
            "id_teman": 2,
            "status": "pending",
            "updated_at": "2026-06-12T10:25:00.000000Z",
            "created_at": "2026-06-12T10:25:00.000000Z",
            "id": 3
        }
    }
    ```

#### c. Terima Permintaan Pertemanan (`PUT /api/pertemanan/terima/{id}`)
Menerima pertemanan dari orang lain. Mengirim notifikasi balasan bahwa pertemanan telah diterima.
*   **Headers:** `Authorization: Bearer {{token}}`
*   **Response (200 OK):**
    ```json
    {
        "statuscode": 200,
        "msg": "Permintaan pertemanan berhasil diterima.",
        "data": {
            "id": 3,
            "id_user": 1,
            "id_teman": 2,
            "status": "accepted"
        }
    }
    ```

---

### Folder 5: Hutang (Trigger Notifikasi)
Endpoint manajemen pencatatan hutang-piutang. Menyimpan atau memperbarui status hutang yang dikaitkan dengan teman akan otomatis memicu pengiriman notifikasi FCM ke HP teman tersebut.

#### a. Catat Hutang Baru (`POST /api/hutang`)
Mencatat transaksi hutang baru. Jika `id_teman` disertakan, server akan mengirim notifikasi ke teman tersebut.
*   **Headers:** `Authorization: Bearer {{token}}`
*   **Body (JSON):**
    ```json
    {
        "id_teman": 2,
        "jumlah": 75000,
        "tanggal_pinjaman": "2026-06-12",
        "metode_pembayaran": "Dana",
        "catatan": "Pinjam buat beli makan siang"
    }
    ```
*   **Response (201 Created):**
    ```json
    {
        "statuscode": 201,
        "msg": "Hutang berhasil ditambahkan.",
        "data": {
            "id": 8,
            "id_user": 1,
            "id_teman": 2,
            "nama": "Teman User 2",
            "jumlah": 75000,
            "tanggal_pinjaman": "2026-06-12",
            "metode_pembayaran": "Dana",
            "status": "belum_lunas",
            "catatan": "Pinjam buat beli makan siang"
        }
    }
    ```

#### b. Update Detail & Status Hutang (`PUT /api/hutang/{id}`)
Memperbarui status pembayaran hutang (misal dari `belum_lunas` ke `lunas`). Mengirim notifikasi update status ke HP teman terkait.
*   **Headers:** `Authorization: Bearer {{token}}`
*   **Body (JSON):**
    ```json
    {
        "jumlah": 75000,
        "tanggal_pinjaman": "2026-06-12",
        "metode_pembayaran": "Dana",
        "status": "lunas",
        "catatan": "Pinjam buat beli makan siang - SUDAH LUNAS"
    }
    ```
*   **Response (200 OK):**
    ```json
    {
        "statuscode": 200,
        "msg": "Hutang berhasil diperbarui.",
        "data": {
            "id": 8,
            "id_user": 1,
            "id_teman": 2,
            "nama": "Teman User 2",
            "jumlah": 75000,
            "tanggal_pinjaman": "2026-06-12",
            "metode_pembayaran": "Dana",
            "status": "lunas",
            "catatan": "Pinjam buat beli makan siang - SUDAH LUNAS"
        }
    }
    ```

---

## 5. Skenario Uji Coba End-to-End (E2E) Notifikasi FCM

Untuk menguji apakah notifikasi FCM benar-benar masuk ke HP Flutter secara real-time melalui Postman, ikuti skenario simulasi 2 User berikut:

### Langkah 1: Persiapkan HP Flutter (User 2)
1. Jalankan aplikasi Flutter di HP Anda atau Emulator.
2. Login sebagai **User 2** (`user2@example.com`).
3. Dapatkan FCM token perangkat Anda di console Flutter (Flutter biasanya mem-print token baru saat inisialisasi).

### Langkah 2: Registrasi Token FCM User 2 ke Backend (melalui Postman)
1. Di Postman, lakukan **Login** sebagai **User 2** (`user2@example.com`) agar variabel token koleksi terisi sebagai User 2.
2. Jalankan request **Simpan / Update FCM Token** dengan memasukkan nilai FCM token HP Anda (dari Langkah 1) pada body request.
3. Sekarang HP User 2 resmi terdaftar untuk menerima notifikasi dari backend.

### Langkah 3: Login sebagai User 1 di Postman
1. Di Postman, jalankan request **Login User** menggunakan akun **User 1** (`user1@example.com`).
2. Script Postman akan memperbarui variabel `{{token}}` koleksi Anda menjadi token milik **User 1**.

### Langkah 4: Trigger Notifikasi (Kirim Permintaan Pertemanan)
1. Jalankan request **Kirim Permintaan Pertemanan** dengan memasukkan email `"user2@example.com"`.
2. Klik **Send**.
3. **Hasil:**
    *   Backend Laravel akan memproses permintaan pertemanan.
    *   Backend membuat data riwayat notifikasi in-app untuk User 2.
    *   Backend mengirim push notification via FCM HTTP v1 ke HP User 2.
    *   **Perangkat HP / Emulator User 2 akan langsung memunculkan push notification di sistem tray HP Anda!**

---

## 6. Pengujian Lewat Command Line (CURL Fallback)

Jika Anda ingin menguji secara cepat menggunakan terminal tanpa Postman, Anda bisa menyalin perintah CURL di bawah ini:

### 1. Login User
```bash
curl -X POST http://127.0.0.1:8000/api/auth/login \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -d '{"email": "user1@example.com", "password": "password123"}'
```

### 2. Simpan FCM Token (Ganti `<TOKEN_JWT>` dengan token hasil login)
```bash
curl -X POST http://127.0.0.1:8000/api/fcm-token \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer <TOKEN_JWT>" \
  -d '{"token": "token-fcm-hp-anda", "device_name": "Xiaomi Redmi Note 13"}'
```

### 3. Get Jumlah Notifikasi Belum Dibaca
```bash
curl -X GET http://127.0.0.1:8000/api/notifikasi/belum-dibaca \
  -H "Accept: application/json" \
  -H "Authorization: Bearer <TOKEN_JWT>"
```
