# 🚀 API Endpoints Reference

Complete API endpoints documentation for Kelola Uang.

**Base URL:** `http://your-domain.com/api`

---

## 🔐 Authentication

### Register
```
POST /auth/register
```
**Body:**
```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

### Login
```
POST /auth/login
```
**Body:**
```json
{
  "email": "john@example.com",
  "password": "password123"
}
```

### Login with Google
```
POST /auth/google
```
**Body:**
```json
{
  "id_token": "your_google_id_token_from_client_sdk"
}
```
**Response (Success 200):**
```json
{
  "statuscode": 200,
  "msg": "Login dengan Google berhasil.",
  "data": {
    "access_token": "eyJhbGciOi...",
    "token_type": "bearer",
    "expires_in": 3600,
    "user": {
      "id": 5,
      "name": "John Doe",
      "email": "john@example.com",
      "google_id": "10293847561029384756",
      "created_at": "2026-06-19T12:00:00.000000Z",
      "updated_at": "2026-06-19T12:00:00.000000Z"
    }
  }
}
```


### Logout
```
POST /auth/logout
Authorization: Bearer {token}
```

### Get Profile
```
GET /auth/profile
Authorization: Bearer {token}
```

### Refresh Token
```
POST /auth/refresh
Authorization: Bearer {token}
```

---

## 💰 Pemasukan (Income)

### List Pemasukan
```
GET /pemasukan?periode=bulan_ini&limit=10
Authorization: Bearer {token}
```

**Query Params:**
- `periode`: semua | bulan_ini | minggu_ini | custom (default: bulan_ini)
- `bulan_custom`: YYYY-MM (e.g., 2024-06) - for custom period
- `limit`: 10-100 (default: 10)

### Get Pemasukan by ID
```
GET /pemasukan/{id}
Authorization: Bearer {token}
```

### Create Pemasukan
```
POST /pemasukan
Authorization: Bearer {token}
```
**Body:**
```json
{
  "jenis": "Gaji",
  "tanggal": "2024-06-08",
  "total": 5000000,
  "description": "Gaji bulan Juni"
}
```

### Update Pemasukan
```
PUT /pemasukan/{id}
Authorization: Bearer {token}
```
**Body:** Same as Create

### Delete Pemasukan
```
DELETE /pemasukan/{id}
Authorization: Bearer {token}
```

---

## 💸 Pengeluaran (Expense)

### List Pengeluaran
```
GET /pengeluaran?periode=bulan_ini&limit=10
Authorization: Bearer {token}
```

**Query Params:** Same as Pemasukan

### Get Pengeluaran by ID
```
GET /pengeluaran/{id}
Authorization: Bearer {token}
```

### Create Pengeluaran
```
POST /pengeluaran
Authorization: Bearer {token}
```
**Body:**
```json
{
  "id_kategori": 1,
  "tanggal_pengeluaran": "2024-06-08",
  "total": 150000,
  "description": "Makan siang",
  "tujuan": "Restoran A",
  "metode_pembayaran": "Qris",
  "status": "paid"
}
```

**Fields:**
- `metode_pembayaran`: Qris | Bank | Dana | Gopay | Cash
- `status`: draft | approved | paid

### Update Pengeluaran
```
PUT /pengeluaran/{id}
Authorization: Bearer {token}
```
**Body:** Same as Create

### Delete Pengeluaran
```
DELETE /pengeluaran/{id}
Authorization: Bearer {token}
```

---

## 📄 Tagihan (Bills)

### List Tagihan
```
GET /tagihan?periode=bulan_ini&limit=10
Authorization: Bearer {token}
```

**Query Params:** Same as Pemasukan

### Get Tagihan by ID
```
GET /tagihan/{id}
Authorization: Bearer {token}
```

### Create Tagihan
```
POST /tagihan
Authorization: Bearer {token}
```
**Body:**
```json
{
  "id_kategori": 1,
  "nama": "Tagihan Listrik",
  "nominal": 500000,
  "jatuh_tempo": "2024-06-20",
  "status": "belum_dibayar",
  "metode_pembayaran": "Bank",
  "pengulangan": "bulanan",
  "catatan": "PLN"
}
```

**Fields:**
- `status`: belum_dibayar | lunas | terlambat
- `pengulangan`: sekali_bayar | bulanan | tahunan
- `metode_pembayaran`: Qris | Bank | Dana | Gopay | Cash

**Note:** Jika status = lunas, akan otomatis create pengeluaran

### Update Tagihan
```
PUT /tagihan/{id}
Authorization: Bearer {token}
```
**Body:** Same as Create

### Delete Tagihan
```
DELETE /tagihan/{id}
Authorization: Bearer {token}
```

---

## 💳 Hutang (Debts)

### List Hutang (Yang Saya Catat)
```
GET /hutang?periode=bulan_ini&limit=10
Authorization: Bearer {token}
```

### List Hutang Saya (Saya yang Berhutang)
```
GET /hutang/hutang-saya?periode=bulan_ini&limit=10
Authorization: Bearer {token}
```

### Get Hutang by ID
```
GET /hutang/{id}
Authorization: Bearer {token}
```

### Create Hutang
```
POST /hutang
Authorization: Bearer {token}
```

**Body (with Friend):**
```json
{
  "id_teman": 5,
  "jumlah": 500000,
  "tanggal_pinjaman": "2024-06-01",
  "metode_pembayaran": "Bank",
  "catatan": "Pinjaman modal usaha"
}
```

**Body (without Friend):**
```json
{
  "nama": "Budi Santoso",
  "jumlah": 250000,
  "tanggal_pinjaman": "2024-06-05",
  "metode_pembayaran": "Cash",
  "catatan": "Hutang darurat"
}
```

**Note:** 
- Jika `id_teman` diisi, sistem akan validasi pertemanan (harus status accepted)
- `nama` auto-fill dari data teman jika `id_teman` diisi

### Update Hutang
```
PUT /hutang/{id}
Authorization: Bearer {token}
```
**Body:**
```json
{
  "jumlah": 500000,
  "tanggal_pinjaman": "2024-06-01",
  "metode_pembayaran": "Bank",
  "status": "lunas",
  "catatan": "Sudah lunas"
}
```

**Fields:**
- `status`: belum_lunas | lunas | terlambat

### Delete Hutang
```
DELETE /hutang/{id}
Authorization: Bearer {token}
```

---

## 👥 Pertemanan (Friends)

### List Teman (Accepted)
```
GET /pertemanan?cari=john
Authorization: Bearer {token}
```

**Query Params:**
- `cari`: Search by name or email (optional)

### List Permintaan Masuk (Pending In)
```
GET /pertemanan/permintaan-masuk
Authorization: Bearer {token}
```

### List Permintaan Terkirim (Pending Out)
```
GET /pertemanan/permintaan-terkirim
Authorization: Bearer {token}
```

### Cari User by Email
```
POST /pertemanan/cari-user
Authorization: Bearer {token}
```
**Body:**
```json
{
  "email": "friend@example.com"
}
```

**Response:**
```json
{
  "statuscode": 200,
  "msg": "Pengguna ditemukan.",
  "data": {
    "user": {
      "id": 5,
      "name": "John Doe",
      "email": "john@example.com"
    },
    "friendship_status": null,
    "can_send_request": true
  }
}
```

### Kirim Permintaan Pertemanan
```
POST /pertemanan/kirim
Authorization: Bearer {token}
```
**Body:**
```json
{
  "email": "friend@example.com"
}
```

### Terima Permintaan
```
PUT /pertemanan/terima/{id}
Authorization: Bearer {token}
```

### Hapus/Tolak Pertemanan
```
DELETE /pertemanan/{id}
Authorization: Bearer {token}
```

**Use Cases:**
- Batalkan permintaan terkirim (pending yang saya kirim)
- Tolak permintaan masuk (pending yang masuk)
- Hapus teman (accepted)

---

## 🏷️ Kategori Pengeluaran

### List Kategori
```
GET /kategori/pengeluaran
Authorization: Bearer {token}
```

### Get Kategori by ID
```
GET /kategori/pengeluaran/{id}
Authorization: Bearer {token}
```

### Create Kategori
```
POST /kategori/pengeluaran
Authorization: Bearer {token}
```
**Body:**
```json
{
  "nama": "Makanan",
  "deskripsi": "Pengeluaran untuk makanan"
}
```

### Update Kategori
```
PUT /kategori/pengeluaran/{id}
Authorization: Bearer {token}
```
**Body:** Same as Create

### Delete Kategori
```
DELETE /kategori/pengeluaran/{id}
Authorization: Bearer {token}
```

---

## 🏷️ Kategori Tagihan

### List Kategori Tagihan
```
GET /kategori/tagihan
Authorization: Bearer {token}
```

### Get Kategori Tagihan by ID
```
GET /kategori/tagihan/{id}
Authorization: Bearer {token}
```

### Create Kategori Tagihan
```
POST /kategori/tagihan
Authorization: Bearer {token}
```
**Body:**
```json
{
  "nama_kategori": "Utilitas",
  "deskripsi": "Tagihan listrik, air, dll"
}
```

### Update Kategori Tagihan
```
PUT /kategori/tagihan/{id}
Authorization: Bearer {token}
```
**Body:** Same as Create

### Delete Kategori Tagihan
```
DELETE /kategori/tagihan/{id}
Authorization: Bearer {token}
```

---

## 📊 Dashboard

### Get Dashboard Data
```
GET /dashboard
Authorization: Bearer {token}
```

**Response:**
```json
{
  "statuscode": 200,
  "msg": "Dashboard data berhasil diambil.",
  "data": {
    "total_pemasukan": 10000000,
    "total_pengeluaran": 5000000,
    "saldo": 5000000,
    "jumlah_tagihan_menunggu": 3,
    "batas_harian": 200000,
    "sisa_batas_harian": 150000
  }
}
```

---

## 💵 Batas Harian

### Get Batas Harian
```
GET /batas-harian
Authorization: Bearer {token}
```

### Set/Update Batas Harian
```
POST /batas-harian
Authorization: Bearer {token}
```
**Body:**
```json
{
  "batas": 200000
}
```

### Delete Batas Harian
```
DELETE /batas-harian
Authorization: Bearer {token}
```

---

## 📋 Standard Response Format

### Success Response
```json
{
  "statuscode": 200,
  "msg": "Success message",
  "data": { ... }
}
```

### Error Response
```json
{
  "statuscode": 422,
  "msg": "Validation Error.",
  "data": {
    "email": ["The email field is required."]
  }
}
```

### Pagination Response
```json
{
  "statuscode": 200,
  "msg": "Data berhasil diambil.",
  "data": [ ... ],
  "pagination": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 10,
    "total": 47
  }
}
```

---

## 🎯 Common HTTP Status Codes

- `200` - OK (Success)
- `201` - Created (Resource created successfully)
- `400` - Bad Request (Invalid request)
- `401` - Unauthorized (Invalid or missing token)
- `404` - Not Found (Resource not found)
- `422` - Unprocessable Entity (Validation error)
- `500` - Internal Server Error (Server error)

---

## 💡 Tips

1. Always include `Authorization: Bearer {token}` header for protected endpoints
2. Default periode is `bulan_ini` for all transaction endpoints
3. Use `periode=semua` to get all data without filter
4. Maximum limit per page is 100
5. Dates should be in `YYYY-MM-DD` format
6. Numbers (money) should be sent as number, not string
7. Always check `statuscode` in response to determine success/failure
