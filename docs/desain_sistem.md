# 📐 Dokumentasi Desain Sistem & Skema Database - Kelola Uang

Dokumentasi ini menyajikan rancangan sistem terpadu untuk platform **Kelola Uang** (Kepitink), mencakup struktur kelas, alur aktivitas bisnis utama, urutan interaksi komponen, aliran data (DFD), serta definisi skema basis data secara detail.

---

## 1. Arsitektur Umum & Arsitektur Kelas (Class Diagram)

Aplikasi **Kelola Uang** mengadopsi arsitektur *Hybrid Monolithic* dengan backend berbasis **Laravel 12**. Backend ini bertindak sebagai penyedia Web Client (Livewire) sekaligus RESTful API Gateway dengan otentikasi JWT untuk Mobile Client (Flutter).

### Class Diagram (Mermaid)

Diagram di bawah menggambarkan relasi antara Controller, Service, dan Eloquent Model di dalam sistem backend Laravel:

```mermaid
classDiagram
    %% Hubungan Controller ke Model/Service
    AuthController ..> User : Mengelola Autentikasi
    GoogleAuthController ..> User : Mengelola Autentikasi Google
    GoogleAuthController --|> AuthController : extends
    BatasHarianController ..> BatasHarian : CRUD
    HutangController ..> Hutang : CRUD & Laporan
    HutangController ..> Pertemanan : Validasi Status Teman
    KategoriPengeluaranController ..> Kategori : CRUD
    KategoriTagihanController ..> KategoriTagihan : CRUD
    PemasukanController ..> Pemasukan : CRUD
    PengeluaranController ..> Pengeluaran : CRUD
    TagihanController ..> Tagihan : CRUD
    TagihanController ..> Pengeluaran : Otomatisasi Pengeluaran
    PertemananController ..> Pertemanan : Kelola Permintaan
    FcmTokenController ..> FcmToken : Kelola Token Device
    NotificationController ..> Notifikasi : Baca/Cari Notifikasi
    
    HutangController ..> FcmService : Kirim Push Notif
    PertemananController ..> FcmService : Kirim Push Notif
    TagihanController ..> FcmService : Kirim Push Notif
    
    %% Hubungan antar Model
    User "1" --> "*" Pemasukan : mencatat
    User "1" --> "*" Pengeluaran : mencatat
    User "1" --> "*" Kategori : memiliki
    User "1" --> "*" KategoriTagihan : memiliki
    User "1" --> "*" Tagihan : memiliki
    User "1" --> "0..1" BatasHarian : membatasi
    User "1" --> "*" Hutang : mencatat_sebagai_kreditur
    User "1" <-- "*" Hutang : dicatat_sebagai_debitur (id_teman)
    User "1" --> "*" Pertemanan : mengirim_pertemanan (id_user)
    User "1" <-- "*" Pertemanan : menerima_pertemanan (id_teman)
    User "1" --> "*" FcmToken : memiliki_token
    User "1" --> "*" Notifikasi : menerima_notifikasi

    Kategori "1" --> "*" Pengeluaran : mengelompokkan
    KategoriTagihan "1" --> "*" Tagihan : mengelompokkan

    class User {
        +int id
        +string name
        +string email
        +string password
        +string google_id
        +datetime email_verified_at
        +pemasukans()
        +pengeluarans()
        +tagihans()
        +batasHarian()
        +hutangs()
        +pertemanans()
        +fcmTokens()
        +notifikasis()
    }

    class Pemasukan {
        +int id
        +int id_user
        +date tanggal
        +string jenis
        +decimal total
        +string metode_pembayaran
        +string status
        +string deskripsi
        +user()
    }

    class Pengeluaran {
        +int id
        +int id_user
        +int id_kategori
        +date tanggal_pengeluaran
        +decimal total
        +text description
        +string tujuan
        +string metode_pembayaran
        +string status
        +user()
        +kategori()
    }

    class Kategori {
        +int id
        +int id_user
        +string nama
        +string deskripsi
        +string emoji
        +string warna
        +user()
        +pengeluarans()
    }

    class KategoriTagihan {
        +int id
        +int id_user
        +string nama
        +string deskripsi
        +string emoji
        +string warna
        +user()
        +tagihans()
    }

    class Tagihan {
        +int id
        +int id_user
        +int kategori
        +string nama
        +decimal nominal
        +date jatuh_tempo
        +string status
        +string metode_pembayaran
        +string pengulangan
        +string catatan
        +user()
        +kategoriTagihan()
    }

    class BatasHarian {
        +int id
        +int id_user
        +decimal batas
        +user()
    }

    class Hutang {
        +int id
        +int id_user
        +int id_teman
        +string nama
        +decimal jumlah
        +date tanggal_pinjaman
        +string status
        +string metode_pembayaran
        +string catatan
        +user()
        +teman()
    }

    class Pertemanan {
        +int id
        +int id_user
        +int id_teman
        +string status
        +user()
        +teman()
    }

    class FcmToken {
        +int id
        +int id_user
        +string token
        +string device_name
        +user()
    }

    class Notifikasi {
        +int id
        +int id_user
        +string judul
        +text pesan
        +string tipe
        +json data
        +datetime dibaca_at
        +user()
    }

    class FcmService {
        +sendNotification(User user, string title, string body, array data) bool
    }
```

---

## 2. Activity Diagram (Alur Proses Bisnis per Fitur - Swimlanes)

Berikut adalah diagram alur aktivitas dengan format **Swimlanes (User, Application, Database)** untuk masing-masing fitur utama dalam platform Kelola Uang:

### A. Fitur Autentikasi (Authentication)
Menunjukkan alur masuk sistem (Login) menggunakan kredensial email/password atau Google Sign-In yang terbagi dalam peran User, Application, dan Database.

```mermaid
flowchart LR
    subgraph User["User"]
        direction TB
        Auth_Start([Mulai]) --> Auth_Choose[Pilih Metode Login]
        Auth_Choose -->|Kredensial| Auth_Input[Input Email & Password]
        Auth_Choose -->|Google| Auth_Google[Otentikasi Akun Google]
    end
    
    subgraph Application["Application"]
        direction TB
        Auth_Validate[Validasi Kredensial / Google Token]
        Auth_Check{Valid / Terdaftar?}
        Auth_Session[Buat Sesi / Token JWT]
        Auth_Dashboard[Tampilkan Dashboard]
    end
    
    subgraph Database["Database"]
        direction TB
        Auth_DbCek[Cek User / Simpan User Baru jika Google baru]
    end
    
    Auth_Input --> Auth_Validate
    Auth_Google --> Auth_Validate
    Auth_Validate --> Auth_Check
    Auth_Check -->|Ya| Auth_Session
    Auth_Check -->|Tidak| Auth_Choose
    Auth_Session --> Auth_DbCek
    Auth_DbCek --> Auth_Dashboard
    Auth_Dashboard --> Auth_End([Selesai])
```

### B. Fitur Pemasukan (Income) - CRUD Lengkap
Menunjukkan seluruh proses penambahan, penayangan, pembaruan, dan penghapusan transaksi pemasukan dana dengan visualisasi swimlane.

```mermaid
flowchart LR
    subgraph User["User"]
        direction TB
        Inc_Start([Mulai]) --> Inc_Action{Pilih Operasi CRUD}
        Inc_Action -->|Create| Inc_InputC[Input Data Pemasukan]
        Inc_Action -->|Read| Inc_View[Pilih Menu Pemasukan]
        Inc_Action -->|Update| Inc_ChooseU[Pilih Transaksi & Input Perubahan]
        Inc_Action -->|Delete| Inc_ChooseD[Pilih Transaksi & Konfirmasi Hapus]
    end
    
    subgraph Application["Application"]
        direction TB
        Inc_ValidateC[Validasi Data]
        Inc_CheckC{Valid?}
        Inc_Request[Kirim Permintaan Simpan / Update / Hapus / Ambil]
        Inc_Display[Tampilkan Daftar & Total Saldo Baru]
    end
    
    subgraph Database["Database"]
        direction TB
        Inc_DbExec[Simpan / Update / Hapus / Query Data Pemasukan]
    end
    
    Inc_InputC --> Inc_ValidateC
    Inc_ChooseU --> Inc_ValidateC
    Inc_ValidateC --> Inc_CheckC
    Inc_CheckC -->|Ya| Inc_Request
    Inc_CheckC -->|Tidak| Inc_Action
    Inc_View --> Inc_Request
    Inc_ChooseD --> Inc_Request
    Inc_Request --> Inc_DbExec
    Inc_DbExec --> Inc_Display
    Inc_Display --> Inc_End([Selesai])
```

### C. Fitur Pengeluaran (Expense) - CRUD Lengkap
Alur pencatatan, pembaruan, penghapusan, dan visualisasi chart pengeluaran dengan pengecekan batas harian anggaran.

```mermaid
flowchart LR
    subgraph User["User"]
        direction TB
        Exp_Start([Mulai]) --> Exp_Action{Pilih Operasi CRUD}
        Exp_Action -->|Create| Exp_InputC[Input Data Pengeluaran]
        Exp_Action -->|Read| Exp_View[Buka Menu Pengeluaran & Laporan]
        Exp_Action -->|Update| Exp_ChooseU[Pilih Transaksi & Input Perubahan]
        Exp_Action -->|Delete| Exp_ChooseD[Pilih Transaksi & Konfirmasi Hapus]
    end
    
    subgraph Application["Application"]
        direction TB
        Exp_Validate[Validasi Data]
        Exp_Check{Valid?}
        Exp_Request[Kirim Request Simpan / Update / Hapus / Ambil]
        Exp_LimitCek[Hitung Pengeluaran Hari Ini & Bandingkan Batas Harian]
        Exp_LimitStatus{Batas Harian Terlampaui?}
        Exp_Alert[Tampilkan Indikator Merah]
        Exp_Display[Tampilkan Daftar & Chart Kategori]
    end
    
    subgraph Database["Database"]
        direction TB
        Exp_DbExec[Simpan / Update / Hapus / Query Pengeluaran]
    end
    
    Exp_InputC --> Exp_Validate
    Exp_ChooseU --> Exp_Validate
    Exp_Validate --> Exp_Check
    Exp_Check -->|Ya| Exp_Request
    Exp_Check -->|Tidak| Exp_Action
    Exp_View --> Exp_Request
    Exp_ChooseD --> Exp_Request
    Exp_Request --> Exp_DbExec
    Exp_DbExec --> Exp_LimitCek
    Exp_LimitCek --> Exp_LimitStatus
    Exp_LimitStatus -->|Ya| Exp_Alert
    Exp_LimitStatus -->|Tidak| Exp_Display
    Exp_Alert --> Exp_Display
    Exp_Display --> Exp_End([Selesai])
```

### D. Fitur Batas Harian (Daily Limit)
Alur pemantauan pengeluaran harian terhadap batas anggaran harian yang dikonfigurasi.

```mermaid
flowchart LR
    subgraph User["User"]
        direction TB
        Lim_Start([Mulai]) --> Lim_Action{Pilih Aksi}
        Lim_Action -->|Atur / Ubah| Lim_Input[Masukkan Nominal Batas Harian]
        Lim_Action -->|Hapus| Lim_Delete[Klik Nonaktifkan]
    end
    
    subgraph Application["Application"]
        direction TB
        Lim_Validate[Validasi Input]
        Lim_Check{Valid?}
        Lim_Request[Kirim Permintaan Simpan / Hapus]
        Lim_Calc[Hitung Sisa Anggaran Hari Ini]
        Lim_Display[Perbarui Tampilan Dashboard]
    end
    
    subgraph Database["Database"]
        direction TB
        Lim_DbExec[Simpan / Hapus dari batas_harians]
    end
    
    Lim_Input --> Lim_Validate
    Lim_Validate --> Lim_Check
    Lim_Check -->|Ya| Lim_Request
    Lim_Check -->|Tidak| Lim_Action
    Lim_Delete --> Lim_Request
    Lim_Request --> Lim_DbExec
    Lim_DbExec --> Lim_Calc
    Lim_Calc --> Lim_Display
    Lim_Display --> Lim_End([Selesai])
```

### E. Fitur Tagihan (Bills) - CRUD Lengkap & Pelunasan
Siklus pembuatan tagihan, pengeditan data, pelunasan otomatis (terhubung ke pengeluaran), dan penghapusan tagihan.

```mermaid
flowchart LR
    subgraph User["User"]
        direction TB
        Bill_Start([Mulai]) --> Bill_Action{Pilih Aksi Tagihan}
        Bill_Action -->|Create| Bill_InputC[Input Data Tagihan & Jatuh Tempo]
        Bill_Action -->|Read| Bill_View[Buka Daftar Tagihan]
        Bill_Action -->|Update| Bill_ChooseU[Pilih Tagihan & Input Perubahan]
        Bill_Action -->|Delete| Bill_ChooseD[Pilih Tagihan & Konfirmasi Hapus]
        Bill_Action -->|Bayar| Bill_Pay[Klik Lunasi Tagihan]
    end
    
    subgraph Application["Application"]
        direction TB
        Bill_Validate[Validasi Data]
        Bill_Check{Valid?}
        Bill_Request[Kirim Permintaan]
        Bill_ProcessPay[Proses Pembayaran: Ubah Status Tagihan]
        Bill_AutoExpense[Buat Record Pengeluaran Otomatis Kategori Tagihan]
        Bill_Display[Perbarui Tampilan List Tagihan]
    end
    
    subgraph Database["Database"]
        direction TB
        Bill_DbExec[Simpan / Update / Hapus / Query Tagihan]
        Bill_DbExpense[Cari/Buat Kategori Tagihan & Simpan Pengeluaran Baru]
    end
    
    Bill_InputC --> Bill_Validate
    Bill_ChooseU --> Bill_Validate
    Bill_Validate --> Bill_Check
    Bill_Check -->|Ya| Bill_Request
    Bill_Check -->|Tidak| Bill_Action
    Bill_View --> Bill_Request
    Bill_ChooseD --> Bill_Request
    Bill_Pay --> Bill_ProcessPay
    
    Bill_Request --> Bill_DbExec
    Bill_ProcessPay --> Bill_DbExec
    Bill_DbExec -->|Jika Aksi Bayar| Bill_AutoExpense
    Bill_AutoExpense --> Bill_DbExpense
    Bill_DbExpense --> Bill_Display
    Bill_DbExec -->|Jika Aksi Biasa| Bill_Display
    Bill_Display --> Bill_End([Selesai])
```

### F. Fitur Pertemanan (Friends) - CRUD-like
Alur pengiriman permintaan teman, penampilan daftar teman aktif/permintaan masuk/keluar, tindakan konfirmasi (terima/tolak), dan penghapusan pertemanan.

```mermaid
flowchart LR
    subgraph User["User"]
        direction TB
        Fr_Start([Mulai]) --> Fr_Action{Pilih Aksi}
        Fr_Action -->|Cari & Kirim| Fr_Search[Input Email Pengguna Lain]
        Fr_Action -->|Lihat List| Fr_View[Lihat Daftar Teman / Permintaan]
        Fr_Action -->|Konfirmasi| Fr_Confirm[Terima / Tolak Permintaan]
        Fr_Action -->|Hapus| Fr_Delete[Pilih Teman & Klik Hapus]
    end
    
    subgraph Application["Application"]
        direction TB
        Fr_SearchRequest[Kirim Request Cari & Kirim / Konfirmasi / Hapus]
        Fr_CheckUser{Pengguna Ditemukan?}
        Fr_Fcm[Kirim Notifikasi FCM ke Penerima]
        Fr_Display[Tampilkan Daftar / Pesan Sukses]
    end
    
    subgraph Database["Database"]
        direction TB
        Fr_DbCek[Cek Tabel users]
        Fr_DbExec[Simpan / Update / Hapus dari pertemanans]
    end
    
    Fr_Search --> Fr_SearchRequest
    Fr_Confirm --> Fr_SearchRequest
    Fr_Delete --> Fr_SearchRequest
    Fr_View --> Fr_SearchRequest
    
    Fr_SearchRequest --> Fr_DbCek
    Fr_DbCek --> Fr_CheckUser
    Fr_CheckUser -->|Ya| Fr_DbExec
    Fr_CheckUser -->|Tidak| Fr_Display
    Fr_DbExec -->|Jika Kirim/Terima| Fr_Fcm
    Fr_Fcm --> Fr_Display
    Fr_DbExec -->|Jika Hapus/Lihat| Fr_Display
    Fr_Display --> Fr_End([Selesai])
```

### G. Fitur Hutang (Debt) - CRUD Lengkap & Kolaboratif
Alur pembuatan hutang (manual/kolaboratif), penampilan laporan piutang/hutang saya, proses pembaruan data/status pelunasan, dan penghapusan.

```mermaid
flowchart LR
    subgraph User["User"]
        direction TB
        Debt_Start([Mulai]) --> Debt_Action{Pilih Aksi Hutang}
        Debt_Action -->|Create| Debt_InputC[Input Data Hutang]
        Debt_Action -->|Read| Debt_View[Lihat Piutang / Hutang Saya]
        Debt_Action -->|Update| Debt_ChooseU[Pilih Record & Input Perubahan / Lunasi]
        Debt_Action -->|Delete| Debt_ChooseD[Pilih Record & Konfirmasi Hapus]
    end
    
    subgraph Application["Application"]
        direction TB
        Debt_Validate[Validasi Data]
        Debt_Check{Valid?}
        Debt_Request[Kirim Permintaan Simpan / Update / Hapus / Ambil]
        Debt_Fcm[Kirim Notifikasi FCM ke Teman jika kolaboratif]
        Debt_Display[Tampilkan Ringkasan & List Hutang]
    end
    
    subgraph Database["Database"]
        direction TB
        Debt_DbCek[Cek Relasi Pertemanan accepted]
        Debt_DbExec[Simpan / Update / Hapus / Query Tabel hutangs]
    end
    
    Debt_InputC --> Debt_Validate
    Debt_ChooseU --> Debt_Validate
    Debt_Validate --> Debt_Check
    Debt_Check -->|Ya| Debt_Request
    Debt_Check -->|Tidak| Debt_Action
    Debt_View --> Debt_Request
    Debt_ChooseD --> Debt_Request
    
    Debt_Request --> Debt_DbCek
    Debt_DbCek --> Debt_DbExec
    Debt_DbExec -->|Jika Kolaboratif Baru| Debt_Fcm
    Debt_Fcm --> Debt_Display
    Debt_DbExec -->|Jika Manual / Lainnya| Debt_Display
    Debt_Display --> Debt_End([Selesai])
```

### H. Fitur Notifikasi (Notifications)
Alur perolehan riwayat notifikasi masuk beserta pembacaan pesan.

```mermaid
flowchart LR
    subgraph User["User"]
        direction TB
        Not_Start([Mulai]) --> Not_Action{Pilih Aksi}
        Not_Action -->|Lihat Notifikasi| Not_View[Buka Halaman Notifikasi]
        Not_Action -->|Tandai Dibaca| Not_Read[Klik Notifikasi / Baca Semua]
    end
    
    subgraph Application["Application"]
        direction TB
        Not_Request[Kirim Permintaan Ambil / Baca]
        Not_Display[Tampilkan Notifikasi & Update Badge UI]
    end
    
    subgraph Database["Database"]
        direction TB
        Not_DbExec[Ambil data unread / Update dibaca_at = NOW()]
    end
    
    Not_View --> Not_Request
    Not_Read --> Not_Request
    Not_Request --> Not_DbExec
    Not_DbExec --> Not_Display
    Not_Display --> Not_End([Selesai])
```

---

## 3. Sequence Diagram (Interaksi Komponen per Fitur)

Berikut adalah urutan panggilan pesan (message sequence) antar subsistem/komponen aplikasi untuk tiap-tiap fitur utama menggunakan pendekatan Robustness/MVC (User -> Boundary/View -> Controller -> Entity/Model):

### A. Fitur Autentikasi (Authentication)
```mermaid
sequenceDiagram
    autonumber
    actor User as User
    participant MenuLogin as Menu Login
    participant LoginController as Login Controller
    participant Dashboard as Dashboard
    
    User->>MenuLogin: Login(Username&Password)
    MenuLogin->>LoginController: ValidasiLogin()
    
    alt Gagal
        LoginController->>User: Tampilkan Notifikasi(Gagal)
    else Berhasil
        LoginController->>Dashboard: masukDashboard()
        Dashboard->>User: Tampilkan Dashboard()
    end
```

#### Alur Google Sign-In (Mobile Client ke Server)
```mermaid
sequenceDiagram
    autonumber
    actor User as User (Flutter Client)
    participant SDK as Google Sign-In SDK
    participant API as GoogleAuthController
    participant DB as Database
    
    User->>SDK: Klik "Masuk dengan Google"
    SDK-->>User: Pilih Akun Google
    User->>SDK: Menyetujui pilihan akun
    SDK-->>User: Return id_token
    User->>API: POST /api/auth/google {id_token}
    
    Note over API: Verifikasi token via Google API certs
    API->>DB: Cari user berdasarkan email
    
    alt User Ditemukan
        DB-->>API: Data User (Update google_id jika kosong)
    else User Baru
        API->>DB: Buat user baru (google_id, verified email)
        DB-->>API: Data User Baru
    end
    
    API-->>User: Return JWT Token & Data User
```


### B. Fitur Pemasukan (Income)
```mermaid
sequenceDiagram
    autonumber
    actor User as User
    participant FormPemasukan as Form Pemasukan
    participant PemasukanController as Pemasukan Controller
    participant PemasukanModel as Pemasukan Model
    participant SaldoUI as UI Saldo
    
    User->>FormPemasukan: IsiFormulir(tanggal, jenis, total, metode, deskripsi)
    FormPemasukan->>PemasukanController: SimpanPemasukan(data)
    PemasukanController->>PemasukanModel: create(data)
    PemasukanModel-->>PemasukanController: Return Success
    
    alt Gagal
        PemasukanController->>User: Tampilkan Notifikasi(Gagal)
    else Berhasil
        PemasukanController->>SaldoUI: PerbaruiTampilanSaldo()
        SaldoUI->>User: Tampilkan Pesan Sukses & Total Saldo
    end
```

### C. Fitur Pengeluaran (Expense)
```mermaid
sequenceDiagram
    autonumber
    actor User as User
    participant FormPengeluaran as Form Pengeluaran
    participant PengeluaranController as Pengeluaran Controller
    participant PengeluaranModel as Pengeluaran Model
    participant LimitUI as UI Batas Harian
    
    User->>FormPengeluaran: IsiFormulir(tanggal, kategori, total, metode, tujuan, deskripsi)
    FormPengeluaran->>PengeluaranController: SimpanPengeluaran(data)
    PengeluaranController->>PengeluaranModel: create(data)
    PengeluaranModel-->>PengeluaranController: Return Success
    
    PengeluaranController->>LimitUI: HitungPengeluaranHariIni()
    
    alt Batas Harian Terlampaui
        LimitUI->>User: Tampilkan Alert Merah & Notifikasi
    else Batas Harian Normal
        LimitUI->>User: Tampilkan Pesan Sukses
    end
```

### D. Fitur Batas Harian (Daily Limit)
```mermaid
sequenceDiagram
    autonumber
    actor User as User
    participant FormBatas as Form Batas Harian
    participant BatasController as BatasHarian Controller
    participant BatasModel as BatasHarian Model
    participant Dashboard as Dashboard
    
    User->>FormBatas: InputBatas(nominal)
    FormBatas->>BatasController: AturBatasHarian(nominal)
    BatasController->>BatasModel: updateOrCreate(nominal)
    BatasModel-->>BatasController: Return Success
    BatasController->>Dashboard: RefreshStatusLimit()
    Dashboard->>User: Tampilkan Sisa Anggaran Terkini
```

### E. Fitur Tagihan (Bills)
```mermaid
sequenceDiagram
    autonumber
    actor User as User
    participant MenuTagihan as Menu Tagihan
    participant TagihanController as Tagihan Controller
    participant TagihanModel as Tagihan Model
    participant PengeluaranModel as Pengeluaran Model
    
    User->>MenuTagihan: KlikLunasiTagihan(id)
    MenuTagihan->>TagihanController: ProsesPelunasan(id)
    TagihanController->>TagihanModel: updateStatus(id, lunas)
    TagihanModel-->>TagihanController: Return Success
    
    Note over TagihanController: Otomatisasi Siklus Tagihan
    TagihanController->>PengeluaranModel: createAutomaticExpense(nominal, 'Tagihan')
    PengeluaranModel-->>TagihanController: Return Success
    
    TagihanController->>MenuTagihan: HapusTagihanDariDaftarAktif()
    MenuTagihan->>User: Tampilkan Notifikasi & Update Tagihan Lunas
```

### F. Fitur Pertemanan (Friends)
```mermaid
sequenceDiagram
    autonumber
    actor UserA as User A (Pengirim)
    actor UserB as User B (Penerima)
    participant MenuTeman as Menu Pertemanan
    participant TemanController as Pertemanan Controller
    participant TemanModel as Pertemanan Model
    participant FCM as FCM Service
    
    UserA->>MenuTeman: InputEmailTeman(emailB)
    MenuTeman->>TemanController: KirimPermintaan(emailB)
    TemanController->>TemanModel: create(id_user=A, id_teman=B, status=pending)
    TemanModel-->>TemanController: Return Success
    TemanController->>FCM: sendNotification(B, 'Permintaan Teman')
    FCM->>UserB: Kirim Push Notification
    TemanController->>UserA: Tampilkan Notifikasi(Terkirim)
    
    Note over UserB, MenuTeman: Tindakan Penerimaan oleh User B
    UserB->>MenuTeman: Klik Terima Permintaan
    MenuTeman->>TemanController: TerimaPermintaan(id)
    TemanController->>TemanModel: update(status=accepted)
    TemanModel-->>TemanController: Return Success
    TemanController->>UserB: Tampilkan Notifikasi(Pertemanan Aktif)
```

### G. Fitur Hutang & Notifikasi FCM (Debt & FCM)
```mermaid
sequenceDiagram
    autonumber
    actor UserA as User A (Kreditur)
    actor UserB as User B (Debitur)
    participant DeviceB as App Mobile B (Flutter)
    participant FormHutang as Form Hutang
    participant HutangController as Hutang Controller
    participant HutangModel as Hutang Model
    participant NotifModel as Notifikasi Model
    participant FCM as FCM Service
    participant GoogleFCM as Google FCM Gateway
    
    Note over UserB, DeviceB: Registrasi Perangkat (Token FCM)
    DeviceB->>HutangController: POST /api/fcm-token
    HutangController->>DB: INSERT/UPDATE token
    DB-->>HutangController: Success
    HutangController-->>DeviceB: HTTP 200 OK
    
    Note over UserA, FormHutang: Transaksi Hutang & Notifikasi
    UserA->>FormHutang: CatatHutang(id_teman=B, jumlah, catatan)
    FormHutang->>HutangController: SimpanHutang(data)
    HutangController->>HutangModel: create(data)
    HutangModel-->>HutangController: Return Success
    HutangController->>NotifModel: create(id_user=B, tipe=hutang)
    NotifModel-->>HutangController: Return Success
    HutangController->>FCM: sendNotification(B, 'Hutang Baru')
    FCM->>GoogleFCM: POST https://fcm.googleapis.com/...
    GoogleFCM->>DeviceB: Kirim Push Notification
    DeviceB->>UserB: Tampilkan Notifikasi di HP
    HutangController->>UserA: Tampilkan Notifikasi(Hutang Berhasil Dicatat)
```

### H. Fitur Notifikasi (Membaca Notifikasi)
```mermaid
sequenceDiagram
    autonumber
    actor User as User
    participant MenuNotif as Menu Notifikasi
    participant NotifController as Notification Controller
    participant NotifModel as Notifikasi Model
    
    User->>MenuNotif: Klik Notifikasi / Baca Semua
    MenuNotif->>NotifController: TandaiDibaca(id)
    NotifController->>NotifModel: update(dibaca_at = NOW())
    NotifModel-->>NotifController: Return Success
    NotifController->>MenuNotif: PerbaruiBadgeNotifikasi()
    MenuNotif->>User: Hilangkan Badge Merah di UI
```

---


## 4. Data Flow Diagram (DFD)

### DFD Level 0 (Context Diagram)
Context diagram menggambarkan aliran data antara entitas luar (Pengguna, Google FCM Gateway, Google OAuth) dengan sistem utama Kelola Uang.

```mermaid
flowchart LR
    %% Entitas
    User["👤 Pengguna (Web & Mobile)"]
    FCM["🔥 Google FCM Gateway"]
    GoogleAuth["🔑 Google OAuth Service"]
    
    %% Sistem
    System(("💻 Platform Kelola Uang<br/>(Backend & API Gateway)"))
    
    %% Aliran Masuk ke Sistem
    User -->|1. Registrasi & Login Kredensial<br/>2. Data Transaksi Pemasukan & Pengeluaran<br/>3. Data Pengaturan Batas Harian<br/>4. Permintaan Teman & Transaksi Hutang<br/>5. FCM Token Perangkat| System
    GoogleAuth -->|Token & Data Profil Google| System
    
    %% Aliran Keluar dari Sistem
    System -->|1. Data Ringkasan Dashboard & Grafik Laporan<br/>2. Riwayat Transaksi & Status Tagihan<br/>3. Status Pertemanan & Hutang-Piutang<br/>4. Daftar Notifikasi Sistem| User
    
    System -->|Payload Notifikasi (FCM Protokol v1)| FCM
    FCM -->|Push Notification Real-time| User
    
    User -->|Login menggunakan Akun Google| GoogleAuth
```

### DFD Level 1 (Diagram Proses)
Membagi sistem menjadi 6 proses utama serta interaksinya dengan data store (tabel-tabel database).

```mermaid
flowchart TD
    %% Entitas
    User["👤 Pengguna (Web/Mobile Client)"]
    FCM["🔥 Google FCM Gateway"]
    
    %% Proses
    P1(("1.0<br/>Manajemen Akun &<br/>Autentikasi"))
    P2(("2.0<br/>Pencatatan Transaksi<br/>& Batas Harian"))
    P3(("3.0<br/>Pengelolaan Tagihan<br/>& Otomatisasi"))
    P4(("4.0<br/>Manajemen Hubungan<br/>Pertemanan"))
    P5(("5.0<br/>Pencatatan Hutang<br/>piutang Lintas User"))
    P6(("6.0<br/>Distribusi & Layanan<br/>Notifikasi"))
    
    %% Data Store
    subgraph Database["🗄️ Database Tables"]
        DS_Users[("users")]
        DS_Pemasukans[("pemasukans")]
        DS_Pengeluarans[("pengeluarans")]
        DS_Kategoris[("kategoris / kategori_tagihans")]
        DS_Tagihans[("tagihans")]
        DS_BatasHarians[("batas_harians")]
        DS_Hutangs[("hutangs")]
        DS_Pertemanans[("pertemanans")]
        DS_FcmTokens[("fcm_tokens")]
        DS_Notifikasis[("notifikasis")]
    end
    
    %% Hubungan Proses 1.0 (Auth)
    User -->|Kirim Kredensial / Google Auth| P1
    P1 -->|Cek & Simpan User| DS_Users
    P1 -->|Kirim JWT Token / Status Sesi| User
    
    %% Hubungan Proses 2.0 (Transaksi)
    User -->|Input Transaksi & Batas| P2
    P2 -->|Simpan Pemasukan| DS_Pemasukans
    P2 -->|Simpan Pengeluaran| DS_Pengeluarans
    P2 -->|Gunakan Kategori| DS_Kategoris
    P2 -->|Simpan Batas Harian| DS_BatasHarians
    P2 -->|Kirim Ringkasan Saldo & Laporan| User
    
    %% Hubungan Proses 3.0 (Tagihan)
    User -->|Buat / Bayar Tagihan| P3
    P3 -->|Simpan / Update Tagihan| DS_Tagihans
    P3 -.->|Memicu Pembuatan Pengeluaran otomatis| P2
    P3 -->|Kirim Status Tagihan Terbaru| User
    
    %% Hubungan Proses 4.0 (Pertemanan)
    User -->|Cari Teman & Kirim Permintaan| P4
    P4 -->|Simpan Status Relasi| DS_Pertemanans
    P4 -.->|Kirim Trigger Notifikasi Teman| P6
    P4 -->|Kirim Status Pertemanan| User
    
    %% Hubungan Proses 5.0 (Hutang)
    User -->|Catat Hutang Pinjaman| P5
    P5 -->|Validasi Status Teman| DS_Pertemanans
    P5 -->|Simpan Data Hutang| DS_Hutangs
    P5 -.->|Kirim Trigger Notifikasi Hutang| P6
    P5 -->|Kirim Ringkasan Hutang-Piutang| User
    
    %% Hubungan Proses 6.0 (Notifikasi)
    User -->|Daftarkan FCM Token Perangkat| P6
    P6 -->|Simpan Token Perangkat| DS_FcmTokens
    P6 -->|Simpan Riwayat Notifikasi| DS_Notifikasis
    P6 -->|Ambil Token Perangkat| DS_FcmTokens
    P6 -->|Kirim Payload Push Notif| FCM
    FCM -->|Push Notification| User
    P6 -->|Kirim Riwayat Notifikasi| User
```

---

## 5. Database Schema Definitions (Definisi Skema Basis Data)

Berikut adalah struktur tabel-tabel database Kelola Uang lengkap dengan tipe data, batasan (constraints), dan keterangan fungsionalnya.

### 1. `users`
Menyimpan data identitas utama akun pengguna untuk autentikasi sistem.
| Nama Kolom | Tipe Data | Key | Nullable | Default | Keterangan |
| :--- | :--- | :---: | :---: | :--- | :--- |
| `id` | bigint unsigned | PK | No | *auto_increment* | ID unik pengguna |
| `name` | varchar(255) | | No | | Nama lengkap pengguna |
| `email` | varchar(255) | Unique | No | | Email unik pengguna (untuk login/cari teman) |
| `email_verified_at`| timestamp | | Yes | NULL | Tanggal verifikasi email |
| `password` | varchar(255) | | No | | Hash sandi pengguna (bcrypt) |
| `google_id` | varchar(255) | | Yes | NULL | ID unik dari Google Sign-In |
| `remember_token` | varchar(100) | | Yes | NULL | Token sesi "remember me" web browser |
| `created_at` | timestamp | | Yes | NULL | Tanggal pembuatan record |
| `updated_at` | timestamp | | Yes | NULL | Tanggal pembaruan record |

### 2. `password_reset_tokens`
Menyimpan token reset kata sandi pengguna.
| Nama Kolom | Tipe Data | Key | Nullable | Default | Keterangan |
| :--- | :--- | :---: | :---: | :--- | :--- |
| `email` | varchar(255) | PK | No | | Email pengguna yang melakukan reset |
| `token` | varchar(255) | | No | | Token acak reset password |
| `created_at` | timestamp | | Yes | NULL | Tanggal pembuatan token |

### 3. `sessions`
Menyimpan data sesi aktif pengguna yang mengakses platform via Web Browser.
| Nama Kolom | Tipe Data | Key | Nullable | Default | Keterangan |
| :--- | :--- | :---: | :---: | :--- | :--- |
| `id` | varchar(255) | PK | No | | ID Sesi unik dari browser |
| `user_id` | bigint unsigned | FK, Index| Yes | NULL | Relasi ke `users.id` |
| `ip_address` | varchar(45) | | Yes | NULL | Alamat IP pengguna |
| `user_agent` | text | | Yes | NULL | Informasi browser / perangkat user |
| `payload` | longtext | | No | | Data serialized dari sesi web |
| `last_activity` | int | Index | No | | Unix timestamp aktivitas terakhir |

### 4. `kategoris`
Menyimpan kategori pengeluaran dinamis yang dibuat dan disesuaikan oleh masing-masing pengguna.
| Nama Kolom | Tipe Data | Key | Nullable | Default | Keterangan |
| :--- | :--- | :---: | :---: | :--- | :--- |
| `id` | bigint unsigned | PK | No | *auto_increment* | ID unik kategori pengeluaran |
| `nama` | varchar(255) | | No | | Nama kategori (misal: "Makanan", "Transportasi") |
| `deskripsi` | varchar(255) | | No | | Keterangan singkat kategori |
| `id_user` | bigint unsigned | FK | No | | Relasi ke `users.id` (Cascade On Delete) |
| `emoji` | varchar(255) | | Yes | NULL | Karakter emoji visual (misal: "🍔", "🚗") |
| `warna` | varchar(255) | | Yes | NULL | Kode warna heksadesimal (misal: "#FF5733") |
| `created_at` | timestamp | | Yes | NULL | Tanggal pembuatan record |
| `updated_at` | timestamp | | Yes | NULL | Tanggal pembaruan record |

### 5. `pengeluarans`
Menyimpan catatan transaksi pengeluaran (arus kas keluar) pengguna.
| Nama Kolom | Tipe Data | Key | Nullable | Default | Keterangan |
| :--- | :--- | :---: | :---: | :--- | :--- |
| `id` | bigint unsigned | PK | No | *auto_increment* | ID unik transaksi pengeluaran |
| `id_user` | bigint unsigned | FK | No | | Relasi ke `users.id` (Cascade On Delete) |
| `id_kategori` | bigint unsigned | FK | No | | Relasi ke `kategoris.id` (Cascade On Delete) |
| `tanggal_pengeluaran`| date | | No | | Tanggal terjadinya pengeluaran |
| `total` | decimal(15,2) | | No | | Nominal uang yang dikeluarkan |
| `description` | text | | No | | Deskripsi detail belanja / pengeluaran |
| `tujuan` | varchar(255) | | Yes | NULL | Nama toko atau penerima pembayaran |
| `metode_pembayaran`| enum | | No | 'Cash' | Pilihan: 'Qris', 'Bank', 'Dana', 'Gopay', 'Cash' |
| `status` | enum | | No | 'draft' | Status pengeluaran: 'draft', 'approved', 'paid' |
| `created_at` | timestamp | | Yes | NULL | Tanggal pembuatan record |
| `updated_at` | timestamp | | Yes | NULL | Tanggal pembaruan record |

### 6. `pemasukans`
Menyimpan catatan transaksi pemasukan (arus kas masuk) pengguna.
| Nama Kolom | Tipe Data | Key | Nullable | Default | Keterangan |
| :--- | :--- | :---: | :---: | :--- | :--- |
| `id` | bigint unsigned | PK | No | *auto_increment* | ID unik transaksi pemasukan |
| `id_user` | bigint unsigned | FK | No | | Relasi ke `users.id` (Cascade On Delete) |
| `tanggal` | date | | No | | Tanggal diterimanya pemasukan |
| `jenis` | enum | | No | 'gaji' | Pilihan: 'gaji', 'bonus', 'penjualan', 'investasi', 'lain-lain' |
| `total` | decimal(15,2) | | No | | Nominal uang masuk |
| `metode_pembayaran`| enum | | No | 'Cash' | Pilihan: 'Qris', 'Bank', 'Dana', 'Gopay', 'Cash' |
| `status` | enum | | No | 'pending' | Status pemasukan: 'pending', 'lunas' |
| `deskripsi` | varchar(255) | | No | | Keterangan singkat pemasukan |
| `created_at` | timestamp | | Yes | NULL | Tanggal pembuatan record |
| `updated_at` | timestamp | | Yes | NULL | Tanggal pembaruan record |

### 7. `kategori_tagihans`
Menyimpan data kategori tagihan dinamis yang dimiliki oleh pengguna.
| Nama Kolom | Tipe Data | Key | Nullable | Default | Keterangan |
| :--- | :--- | :---: | :---: | :--- | :--- |
| `id` | bigint unsigned | PK | No | *auto_increment* | ID unik kategori tagihan |
| `id_user` | bigint unsigned | FK | No | | Relasi ke `users.id` (Cascade On Delete) |
| `nama` | varchar(255) | | No | | Nama kategori tagihan (misal: "Utilitas", "Bulanan") |
| `deskripsi` | varchar(255) | | No | | Penjelasan singkat kategori tagihan |
| `emoji` | varchar(255) | | Yes | NULL | Karakter emoji visual kategori tagihan |
| `warna` | varchar(255) | | Yes | NULL | Kode warna heksadesimal kategori tagihan |
| `created_at` | timestamp | | Yes | NULL | Tanggal pembuatan record |
| `updated_at` | timestamp | | Yes | NULL | Tanggal pembaruan record |

### 8. `tagihans`
Menyimpan kewajiban pembayaran berkala yang wajib dilunasi oleh pengguna.
| Nama Kolom | Tipe Data | Key | Nullable | Default | Keterangan |
| :--- | :--- | :---: | :---: | :--- | :--- |
| `id` | bigint unsigned | PK | No | *auto_increment* | ID unik tagihan |
| `id_user` | bigint unsigned | FK | No | | Relasi ke `users.id` (Cascade On Delete) |
| `kategori` | bigint unsigned | FK | No | | Relasi ke `kategori_tagihans.id` (Cascade On Delete/Update) |
| `nama` | varchar(255) | | No | | Nama tagihan (misal: "Tagihan Listrik PLN") |
| `nominal` | decimal(15,2) | | No | | Jumlah tagihan yang harus dibayar |
| `jatuh_tempo` | date | | No | | Tanggal batas akhir pembayaran |
| `status` | enum | | No | 'belum_dibayar' | Pilihan: 'belum_dibayar', 'lunas', 'terlambat' |
| `metode_pembayaran`| enum | | No | 'Cash' | Pilihan: 'Qris', 'Bank', 'Dana', 'Gopay', 'Cash' |
| `pengulangan` | enum | | No | 'sekali_bayar'| Periode tagihan: 'sekali_bayar', 'bulanan', 'tahunan' |
| `catatan` | varchar(255) | | No | | Catatan tambahan tagihan |
| `created_at` | timestamp | | Yes | NULL | Tanggal pembuatan record |
| `updated_at` | timestamp | | Yes | NULL | Tanggal pembaruan record |

### 9. `batas_harians`
Menyimpan pengaturan batasan pengeluaran harian pengguna.
| Nama Kolom | Tipe Data | Key | Nullable | Default | Keterangan |
| :--- | :--- | :---: | :---: | :--- | :--- |
| `id` | bigint unsigned | PK | No | *auto_increment* | ID unik batas harian |
| `id_user` | bigint unsigned | FK | No | | Relasi ke `users.id` (Cascade On Delete) |
| `batas` | decimal(15,2) | | No | | Jumlah nominal batas pengeluaran harian |
| `created_at` | timestamp | | Yes | NULL | Tanggal pembuatan record |
| `updated_at` | timestamp | | Yes | NULL | Tanggal pembaruan record |

### 10. `hutangs`
Menyimpan transaksi hutang-piutang pengguna (baik kolaboratif dengan teman terdaftar maupun manual).
| Nama Kolom | Tipe Data | Key | Nullable | Default | Keterangan |
| :--- | :--- | :---: | :---: | :--- | :--- |
| `id` | bigint unsigned | PK | No | *auto_increment* | ID unik transaksi hutang |
| `id_user` | bigint unsigned | FK | No | | Kreditur / Pembuat record (Relasi ke `users.id`) |
| `id_teman` | bigint unsigned | FK | Yes | NULL | Debitur terdaftar (Relasi ke `users.id`, Null On Delete)|
| `nama` | varchar(255) | | No | | Nama kontak manual (jika debitur tidak terdaftar) |
| `jumlah` | decimal(15,2) | | No | | Nominal jumlah hutang |
| `tanggal_pinjaman` | date | | No | | Tanggal peminjaman uang |
| `status` | enum | | No | 'belum_lunas' | Pilihan: 'belum_lunas', 'lunas', 'terlambat' |
| `metode_pembayaran`| enum | | No | | Pilihan: 'Qris', 'Bank', 'Dana', 'Gopay', 'Cash' |
| `catatan` | varchar(255) | | Yes | NULL | Keterangan tambahan transaksi hutang |
| `created_at` | timestamp | | Yes | NULL | Tanggal pembuatan record |
| `updated_at` | timestamp | | Yes | NULL | Tanggal pembaruan record |

### 11. `pertemanans`
Menyimpan relasi pertemanan antar dua pengguna terdaftar di sistem.
| Nama Kolom | Tipe Data | Key | Nullable | Default | Keterangan |
| :--- | :--- | :---: | :---: | :--- | :--- |
| `id` | bigint unsigned | PK | No | *auto_increment* | ID unik pertemanan |
| `id_user` | bigint unsigned | FK, Unique| No | | Pengirim permintaan teman (Relasi ke `users.id`) |
| `id_teman` | bigint unsigned | FK, Unique| No | | Penerima permintaan teman (Relasi ke `users.id`) |
| `status` | enum | | No | 'pending' | Status pertemanan: 'pending', 'accepted' |
| `created_at` | timestamp | | Yes | NULL | Tanggal pengiriman permintaan teman |
| `updated_at` | timestamp | | Yes | NULL | Tanggal persetujuan pertemanan |

> [!NOTE]  
> Terdapat indeks unik gabungan (composite unique index) untuk kolom `['id_user', 'id_teman']` untuk mencegah pengiriman permintaan ganda di antara dua pengguna yang sama.

### 12. `fcm_tokens`
Menyimpan Firebase Cloud Messaging (FCM) token perangkat mobile milik pengguna yang aktif.
| Nama Kolom | Tipe Data | Key | Nullable | Default | Keterangan |
| :--- | :--- | :---: | :---: | :--- | :--- |
| `id` | bigint unsigned | PK | No | *auto_increment* | ID unik token perangkat |
| `id_user` | bigint unsigned | FK, Unique| No | | Relasi ke `users.id` (Cascade On Delete) |
| `token` | varchar(255) | Unique | No | | Token unik FCM perangkat dari Firebase SDK |
| `device_name` | varchar(255) | | Yes | NULL | Nama perangkat (misal: "Samsung S24 Ultra", "iPhone 15 Pro") |
| `created_at` | timestamp | | Yes | NULL | Tanggal registrasi token |
| `updated_at` | timestamp | | Yes | NULL | Tanggal pembaruan token |

> [!NOTE]  
> Terdapat indeks unik gabungan `['id_user', 'token']` untuk memastikan satu perangkat hanya terdaftar satu kali untuk setiap akun pengguna.

### 13. `notifikasis`
Menyimpan riwayat notifikasi sistem dan transaksi yang dikirimkan kepada masing-masing pengguna.
| Nama Kolom | Tipe Data | Key | Nullable | Default | Keterangan |
| :--- | :--- | :---: | :---: | :--- | :--- |
| `id` | bigint unsigned | PK | No | *auto_increment* | ID unik notifikasi |
| `id_user` | bigint unsigned | FK, Index| No | | Relasi ke `users.id` (Cascade On Delete) |
| `judul` | varchar(255) | | No | | Judul notifikasi (misal: "Permintaan Pertemanan") |
| `pesan` | text | | No | | Isi deskripsi/pesan notifikasi |
| `tipe` | varchar(255) | | No | | Jenis notifikasi (misal: "pertemanan", "hutang", "sistem")|
| `data` | json | | Yes | NULL | Data payload tambahan dalam format JSON |
| `dibaca_at` | timestamp | Index | Yes | NULL | Tanggal & waktu notifikasi dibaca oleh pengguna |
| `created_at` | timestamp | | Yes | NULL | Tanggal masuknya notifikasi |
| `updated_at` | timestamp | | Yes | NULL | Tanggal perubahan status notifikasi |

> [!NOTE]  
> Terdapat composite index `['id_user', 'dibaca_at']` untuk mempercepat query pencarian notifikasi yang belum dibaca (unread notifications).
