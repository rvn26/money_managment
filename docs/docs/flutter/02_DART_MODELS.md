# 🎨 Dart Models - Kelola Uang API

Complete Dart models for all API responses.

## 📦 Base Models

### ApiResponse (Generic)
```dart
class ApiResponse<T> {
  final int statuscode;
  final String msg;
  final T? data;
  
  ApiResponse({
    required this.statuscode,
    required this.msg,
    this.data,
  });
  
  factory ApiResponse.fromJson(
    Map<String, dynamic> json,
    T Function(dynamic)? fromJsonT,
  ) {
    return ApiResponse<T>(
      statuscode: json['statuscode'] as int,
      msg: json['msg'] as String,
      data: fromJsonT != null && json['data'] != null
          ? fromJsonT(json['data'])
          : null,
    );
  }
  
  bool get isSuccess => statuscode >= 200 && statuscode < 300;
}
```

### PaginationResponse
```dart
class PaginationResponse<T> {
  final int statuscode;
  final String msg;
  final List<T> data;
  final Pagination pagination;
  
  PaginationResponse({
    required this.statuscode,
    required this.msg,
    required this.data,
    required this.pagination,
  });
  
  factory PaginationResponse.fromJson(
    Map<String, dynamic> json,
    T Function(Map<String, dynamic>) fromJsonT,
  ) {
    return PaginationResponse<T>(
      statuscode: json['statuscode'] as int,
      msg: json['msg'] as String,
      data: (json['data'] as List)
          .map((item) => fromJsonT(item as Map<String, dynamic>))
          .toList(),
      pagination: Pagination.fromJson(json['pagination'] as Map<String, dynamic>),
    );
  }
}

class Pagination {
  final int currentPage;
  final int lastPage;
  final int perPage;
  final int total;
  
  Pagination({
    required this.currentPage,
    required this.lastPage,
    required this.perPage,
    required this.total,
  });
  
  factory Pagination.fromJson(Map<String, dynamic> json) {
    return Pagination(
      currentPage: json['current_page'] as int,
      lastPage: json['last_page'] as int,
      perPage: json['per_page'] as int,
      total: json['total'] as int,
    );
  }
  
  bool get hasMore => currentPage < lastPage;
}
```

## 👤 User Model

```dart
class User {
  final int id;
  final String name;
  final String email;
  final DateTime? createdAt;
  final DateTime? updatedAt;
  
  User({
    required this.id,
    required this.name,
    required this.email,
    this.createdAt,
    this.updatedAt,
  });
  
  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      id: json['id'] as int,
      name: json['name'] as String,
      email: json['email'] as String,
      createdAt: json['created_at'] != null 
          ? DateTime.parse(json['created_at'] as String)
          : null,
      updatedAt: json['updated_at'] != null
          ? DateTime.parse(json['updated_at'] as String)
          : null,
    );
  }
  
  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'email': email,
    };
  }
}
```

## 💰 Pemasukan (Income) Model

```dart
class Pemasukan {
  final int id;
  final int idUser;
  final String jenis;
  final DateTime tanggal;
  final double total;
  final String? description;
  final DateTime? createdAt;
  final DateTime? updatedAt;
  final User? user;
  
  Pemasukan({
    required this.id,
    required this.idUser,
    required this.jenis,
    required this.tanggal,
    required this.total,
    this.description,
    this.createdAt,
    this.updatedAt,
    this.user,
  });
  
  factory Pemasukan.fromJson(Map<String, dynamic> json) {
    return Pemasukan(
      id: json['id'] as int,
      idUser: json['id_user'] as int,
      jenis: json['jenis'] as String,
      tanggal: DateTime.parse(json['tanggal'] as String),
      total: (json['total'] as num).toDouble(),
      description: json['description'] as String?,
      createdAt: json['created_at'] != null
          ? DateTime.parse(json['created_at'] as String)
          : null,
      updatedAt: json['updated_at'] != null
          ? DateTime.parse(json['updated_at'] as String)
          : null,
      user: json['user'] != null
          ? User.fromJson(json['user'] as Map<String, dynamic>)
          : null,
    );
  }
  
  Map<String, dynamic> toJson() {
    return {
      'jenis': jenis,
      'tanggal': tanggal.toIso8601String().split('T')[0],
      'total': total,
      'description': description,
    };
  }
}
```

## 💸 Pengeluaran (Expense) Model

```dart
class Pengeluaran {
  final int id;
  final int idUser;
  final int idKategori;
  final DateTime tanggalPengeluaran;
  final double total;
  final String? description;
  final String tujuan;
  final String metodePembayaran; // Qris, Bank, Dana, Gopay, Cash
  final String status; // draft, approved, paid
  final DateTime? createdAt;
  final DateTime? updatedAt;
  final User? user;
  final Kategori? kategori;
  
  Pengeluaran({
    required this.id,
    required this.idUser,
    required this.idKategori,
    required this.tanggalPengeluaran,
    required this.total,
    this.description,
    required this.tujuan,
    required this.metodePembayaran,
    required this.status,
    this.createdAt,
    this.updatedAt,
    this.user,
    this.kategori,
  });
  
  factory Pengeluaran.fromJson(Map<String, dynamic> json) {
    return Pengeluaran(
      id: json['id'] as int,
      idUser: json['id_user'] as int,
      idKategori: json['id_kategori'] as int,
      tanggalPengeluaran: DateTime.parse(json['tanggal_pengeluaran'] as String),
      total: (json['total'] as num).toDouble(),
      description: json['description'] as String?,
      tujuan: json['tujuan'] as String,
      metodePembayaran: json['metode_pembayaran'] as String,
      status: json['status'] as String,
      createdAt: json['created_at'] != null
          ? DateTime.parse(json['created_at'] as String)
          : null,
      updatedAt: json['updated_at'] != null
          ? DateTime.parse(json['updated_at'] as String)
          : null,
      user: json['user'] != null
          ? User.fromJson(json['user'] as Map<String, dynamic>)
          : null,
      kategori: json['kategori'] != null
          ? Kategori.fromJson(json['kategori'] as Map<String, dynamic>)
          : null,
    );
  }
  
  Map<String, dynamic> toJson() {
    return {
      'id_kategori': idKategori,
      'tanggal_pengeluaran': tanggalPengeluaran.toIso8601String().split('T')[0],
      'total': total,
      'description': description,
      'tujuan': tujuan,
      'metode_pembayaran': metodePembayaran,
      'status': status,
    };
  }
}
```

## 🏷️ Kategori Model

```dart
class Kategori {
  final int id;
  final int idUser;
  final String nama;
  final String? deskripsi;
  final DateTime? createdAt;
  final DateTime? updatedAt;
  
  Kategori({
    required this.id,
    required this.idUser,
    required this.nama,
    this.deskripsi,
    this.createdAt,
    this.updatedAt,
  });
  
  factory Kategori.fromJson(Map<String, dynamic> json) {
    return Kategori(
      id: json['id'] as int,
      idUser: json['id_user'] as int,
      nama: json['nama'] as String,
      deskripsi: json['deskripsi'] as String?,
      createdAt: json['created_at'] != null
          ? DateTime.parse(json['created_at'] as String)
          : null,
      updatedAt: json['updated_at'] != null
          ? DateTime.parse(json['updated_at'] as String)
          : null,
    );
  }
  
  Map<String, dynamic> toJson() {
    return {
      'nama': nama,
      'deskripsi': deskripsi,
    };
  }
}
```

## 📄 Tagihan (Bill) Model

```dart
class Tagihan {
  final int id;
  final int idUser;
  final int kategori;
  final String nama;
  final double nominal;
  final DateTime jatuhTempo;
  final String status; // belum_dibayar, lunas, terlambat
  final String metodePembayaran; // Qris, Bank, Dana, Gopay, Cash
  final String pengulangan; // sekali_bayar, bulanan, tahunan
  final String? catatan;
  final DateTime? createdAt;
  final DateTime? updatedAt;
  final KategoriTagihan? kategoriTagihan;
  
  Tagihan({
    required this.id,
    required this.idUser,
    required this.kategori,
    required this.nama,
    required this.nominal,
    required this.jatuhTempo,
    required this.status,
    required this.metodePembayaran,
    required this.pengulangan,
    this.catatan,
    this.createdAt,
    this.updatedAt,
    this.kategoriTagihan,
  });
  
  factory Tagihan.fromJson(Map<String, dynamic> json) {
    return Tagihan(
      id: json['id'] as int,
      idUser: json['id_user'] as int,
      kategori: json['kategori'] as int,
      nama: json['nama'] as String,
      nominal: (json['nominal'] as num).toDouble(),
      jatuhTempo: DateTime.parse(json['jatuh_tempo'] as String),
      status: json['status'] as String,
      metodePembayaran: json['metode_pembayaran'] as String,
      pengulangan: json['pengulangan'] as String,
      catatan: json['catatan'] as String?,
      createdAt: json['created_at'] != null
          ? DateTime.parse(json['created_at'] as String)
          : null,
      updatedAt: json['updated_at'] != null
          ? DateTime.parse(json['updated_at'] as String)
          : null,
      kategoriTagihan: json['kategori_tagihan'] != null
          ? KategoriTagihan.fromJson(json['kategori_tagihan'] as Map<String, dynamic>)
          : null,
    );
  }
  
  Map<String, dynamic> toJson() {
    return {
      'id_kategori': kategori,
      'nama': nama,
      'nominal': nominal,
      'jatuh_tempo': jatuhTempo.toIso8601String().split('T')[0],
      'status': status,
      'metode_pembayaran': metodePembayaran,
      'pengulangan': pengulangan,
      'catatan': catatan,
    };
  }
}

class KategoriTagihan {
  final int id;
  final int idUser;
  final String namaKategori;
  final String? deskripsi;
  final DateTime? createdAt;
  final DateTime? updatedAt;
  
  KategoriTagihan({
    required this.id,
    required this.idUser,
    required this.namaKategori,
    this.deskripsi,
    this.createdAt,
    this.updatedAt,
  });
  
  factory KategoriTagihan.fromJson(Map<String, dynamic> json) {
    return KategoriTagihan(
      id: json['id'] as int,
      idUser: json['id_user'] as int,
      namaKategori: json['nama_kategori'] as String,
      deskripsi: json['deskripsi'] as String?,
      createdAt: json['created_at'] != null
          ? DateTime.parse(json['created_at'] as String)
          : null,
      updatedAt: json['updated_at'] != null
          ? DateTime.parse(json['updated_at'] as String)
          : null,
    );
  }
}
```

## 💳 Hutang (Debt) Model

```dart
class Hutang {
  final int id;
  final int idUser;
  final int? idTeman;
  final String nama;
  final double jumlah;
  final DateTime tanggalPinjaman;
  final String status; // belum_lunas, lunas, terlambat
  final String metodePembayaran; // Qris, Bank, Dana, Gopay, Cash
  final String? catatan;
  final DateTime? createdAt;
  final DateTime? updatedAt;
  final User? user;
  final User? teman;
  
  Hutang({
    required this.id,
    required this.idUser,
    this.idTeman,
    required this.nama,
    required this.jumlah,
    required this.tanggalPinjaman,
    required this.status,
    required this.metodePembayaran,
    this.catatan,
    this.createdAt,
    this.updatedAt,
    this.user,
    this.teman,
  });
  
  factory Hutang.fromJson(Map<String, dynamic> json) {
    return Hutang(
      id: json['id'] as int,
      idUser: json['id_user'] as int,
      idTeman: json['id_teman'] as int?,
      nama: json['nama'] as String,
      jumlah: (json['jumlah'] as num).toDouble(),
      tanggalPinjaman: DateTime.parse(json['tanggal_pinjaman'] as String),
      status: json['status'] as String,
      metodePembayaran: json['metode_pembayaran'] as String,
      catatan: json['catatan'] as String?,
      createdAt: json['created_at'] != null
          ? DateTime.parse(json['created_at'] as String)
          : null,
      updatedAt: json['updated_at'] != null
          ? DateTime.parse(json['updated_at'] as String)
          : null,
      user: json['user'] != null
          ? User.fromJson(json['user'] as Map<String, dynamic>)
          : null,
      teman: json['teman'] != null
          ? User.fromJson(json['teman'] as Map<String, dynamic>)
          : null,
    );
  }
  
  Map<String, dynamic> toJson() {
    return {
      'id_teman': idTeman,
      'nama': nama,
      'jumlah': jumlah,
      'tanggal_pinjaman': tanggalPinjaman.toIso8601String().split('T')[0],
      'metode_pembayaran': metodePembayaran,
      'catatan': catatan,
    };
  }
  
  Map<String, dynamic> toJsonUpdate() {
    return {
      'jumlah': jumlah,
      'tanggal_pinjaman': tanggalPinjaman.toIso8601String().split('T')[0],
      'metode_pembayaran': metodePembayaran,
      'status': status,
      'catatan': catatan,
    };
  }
}
```

## 👥 Pertemanan (Friendship) Model

```dart
class Pertemanan {
  final int id;
  final int idUser;
  final int idTeman;
  final String status; // pending, accepted
  final DateTime? createdAt;
  final DateTime? updatedAt;
  final User? user;
  final User? teman;
  
  Pertemanan({
    required this.id,
    required this.idUser,
    required this.idTeman,
    required this.status,
    this.createdAt,
    this.updatedAt,
    this.user,
    this.teman,
  });
  
  factory Pertemanan.fromJson(Map<String, dynamic> json) {
    return Pertemanan(
      id: json['id'] as int,
      idUser: json['id_user'] as int,
      idTeman: json['id_teman'] as int,
      status: json['status'] as String,
      createdAt: json['created_at'] != null
          ? DateTime.parse(json['created_at'] as String)
          : null,
      updatedAt: json['updated_at'] != null
          ? DateTime.parse(json['updated_at'] as String)
          : null,
      user: json['user'] != null
          ? User.fromJson(json['user'] as Map<String, dynamic>)
          : null,
      teman: json['teman'] != null
          ? User.fromJson(json['teman'] as Map<String, dynamic>)
          : null,
    );
  }
}
```

## 📊 Dashboard Model

```dart
class DashboardData {
  final double totalPemasukan;
  final double totalPengeluaran;
  final double saldo;
  final int jumlahTagihanMenunggu;
  final double? batasHarian;
  final double? sisaBatasHarian;
  final List<TransaksiTerbaru>? transaksiTerbaru;
  final List<TagihanMenunggu>? tagihanMenunggu;
  
  DashboardData({
    required this.totalPemasukan,
    required this.totalPengeluaran,
    required this.saldo,
    required this.jumlahTagihanMenunggu,
    this.batasHarian,
    this.sisaBatasHarian,
    this.transaksiTerbaru,
    this.tagihanMenunggu,
  });
  
  factory DashboardData.fromJson(Map<String, dynamic> json) {
    return DashboardData(
      totalPemasukan: (json['total_pemasukan'] as num).toDouble(),
      totalPengeluaran: (json['total_pengeluaran'] as num).toDouble(),
      saldo: (json['saldo'] as num).toDouble(),
      jumlahTagihanMenunggu: json['jumlah_tagihan_menunggu'] as int,
      batasHarian: json['batas_harian'] != null 
          ? (json['batas_harian'] as num).toDouble()
          : null,
      sisaBatasHarian: json['sisa_batas_harian'] != null
          ? (json['sisa_batas_harian'] as num).toDouble()
          : null,
    );
  }
}
```

## 💵 Batas Harian Model

```dart
class BatasHarian {
  final int id;
  final int idUser;
  final double batas;
  final DateTime? createdAt;
  final DateTime? updatedAt;
  
  BatasHarian({
    required this.id,
    required this.idUser,
    required this.batas,
    this.createdAt,
    this.updatedAt,
  });
  
  factory BatasHarian.fromJson(Map<String, dynamic> json) {
    return BatasHarian(
      id: json['id'] as int,
      idUser: json['id_user'] as int,
      batas: (json['batas'] as num).toDouble(),
      createdAt: json['created_at'] != null
          ? DateTime.parse(json['created_at'] as String)
          : null,
      updatedAt: json['updated_at'] != null
          ? DateTime.parse(json['updated_at'] as String)
          : null,
    );
  }
  
  Map<String, dynamic> toJson() {
    return {
      'batas': batas,
    };
  }
}
```

## 🔍 Search User Response Model

```dart
class SearchUserResponse {
  final User user;
  final String? friendshipStatus; // null, pending, accepted
  final bool canSendRequest;
  
  SearchUserResponse({
    required this.user,
    this.friendshipStatus,
    required this.canSendRequest,
  });
  
  factory SearchUserResponse.fromJson(Map<String, dynamic> json) {
    return SearchUserResponse(
      user: User.fromJson(json['user'] as Map<String, dynamic>),
      friendshipStatus: json['friendship_status'] as String?,
      canSendRequest: json['can_send_request'] as bool,
    );
  }
}
```

## 🎯 Enums

```dart
enum MetodePembayaran {
  qris('Qris'),
  bank('Bank'),
  dana('Dana'),
  gopay('Gopay'),
  cash('Cash');
  
  final String value;
  const MetodePembayaran(this.value);
}

enum StatusHutang {
  belumLunas('belum_lunas'),
  lunas('lunas'),
  terlambat('terlambat');
  
  final String value;
  const StatusHutang(this.value);
}

enum StatusTagihan {
  belumDibayar('belum_dibayar'),
  lunas('lunas'),
  terlambat('terlambat');
  
  final String value;
  const StatusTagihan(this.value);
}

enum StatusPengeluaran {
  draft('draft'),
  approved('approved'),
  paid('paid');
  
  final String value;
  const StatusPengeluaran(this.value);
}

enum Pengulangan {
  sekaliBayar('sekali_bayar'),
  bulanan('bulanan'),
  tahunan('tahunan');
  
  final String value;
  const Pengulangan(this.value);
}

enum Periode {
  semua('semua'),
  bulanIni('bulan_ini'),
  mingguIni('minggu_ini'),
  custom('custom');
  
  final String value;
  const Periode(this.value);
}
```

---

**Note:** Semua model sudah include null-safety dan proper type casting. Copy paste models ini ke folder `lib/models/` di project Flutter Anda.
