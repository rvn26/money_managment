# Dokumentasi Integrasi Push Notification (FCM) — Flutter

Dokumentasi lengkap untuk mengintegrasikan Firebase Cloud Messaging (FCM) push notification pada aplikasi Flutter **Kepitink** dengan backend Laravel.

---

## Daftar Isi

1. [Setup Firebase Project](#1-setup-firebase-project)
2. [Setup Flutter — Firebase Core & Messaging](#2-setup-flutter--firebase-core--messaging)
3. [Konfigurasi Platform (Android & iOS)](#3-konfigurasi-platform-android--ios)
4. [Inisialisasi Firebase di Flutter](#4-inisialisasi-firebase-di-flutter)
5. [Notification Service — Kode Utama](#5-notification-service--kode-utama)
6. [Integrasi dengan Auth Flow (Login/Logout)](#6-integrasi-dengan-auth-flow-loginlogout)
7. [API Reference — Endpoint Notifikasi](#7-api-reference--endpoint-notifikasi)
8. [Menampilkan Notifikasi di UI](#8-menampilkan-notifikasi-di-ui)
9. [Handle Notification Tap (Navigation)](#9-handle-notification-tap-navigation)
10. [Background & Terminated Notification](#10-background--terminated-notification)
11. [Tipe Notifikasi & Data Payload](#11-tipe-notifikasi--data-payload)
12. [Troubleshooting](#12-troubleshooting)

---

## 1. Setup Firebase Project

### 1.1 Buat Project di Firebase Console

1. Buka [Firebase Console](https://console.firebase.google.com/)
2. Klik **"Add project"** → masukkan nama project (misal: `Kepitink`)
3. Ikuti wizard sampai project dibuat
4. Catat **Project ID** (terlihat di Project Settings → General)

### 1.2 Tambahkan App Android

1. Di Firebase Console → Project Overview → klik ikon **Android**
2. Masukkan **Android package name** (lihat di `android/app/build.gradle` → `applicationId`)
   ```
   Contoh: com.kepitink.app
   ```
3. Download file **`google-services.json`**
4. Simpan file tersebut di: `android/app/google-services.json`

### 1.3 Tambahkan App iOS (Opsional)

1. Di Firebase Console → Project Overview → klik ikon **iOS**
2. Masukkan **iOS Bundle ID** (lihat di Xcode → Runner → General → Bundle Identifier)
3. Download file **`GoogleService-Info.plist`**
4. Simpan file tersebut di: `ios/Runner/GoogleService-Info.plist`

### 1.4 Download Service Account Key (untuk Backend Laravel)

1. Firebase Console → **Project Settings** → **Service Accounts**
2. Klik **"Generate new private key"**
3. Download file JSON → simpan di backend Laravel:
   ```
   storage/app/firebase/service-account.json
   ```
4. Isi variabel di `.env` backend:
   ```env
   FIREBASE_PROJECT_ID=kepitink-xxxxx    # Ganti dengan Project ID kamu
   FIREBASE_CREDENTIALS=app/firebase/service-account.json
   ```

---

## 2. Setup Flutter — Firebase Core & Messaging

### 2.1 Install Dependencies

Jalankan di terminal Flutter project:

```bash
flutter pub add firebase_core
flutter pub add firebase_messaging
flutter pub add flutter_local_notifications
```

**`pubspec.yaml`** akan otomatis berisi:

```yaml
dependencies:
  firebase_core: ^3.x.x
  firebase_messaging: ^15.x.x
  flutter_local_notifications: ^18.x.x
```

### 2.2 Install FlutterFire CLI

```bash
dart pub global activate flutterfire_cli
```

### 2.3 Konfigurasi Otomatis dengan FlutterFire CLI

```bash
flutterfire configure --project=kepitink-xxxxx
```

> Ganti `kepitink-xxxxx` dengan Firebase Project ID kamu.

Perintah ini akan:
- Menghasilkan file `lib/firebase_options.dart`
- Mengonfigurasi `google-services.json` (Android)
- Mengonfigurasi `GoogleService-Info.plist` (iOS)

---

## 3. Konfigurasi Platform (Android & iOS)

### 3.1 Android

#### `android/build.gradle` (project-level)

Pastikan terdapat classpath google-services:

```gradle
buildscript {
    dependencies {
        // ... existing dependencies
        classpath 'com.google.gms:google-services:4.4.2'
    }
}
```

#### `android/app/build.gradle` (app-level)

```gradle
apply plugin: 'com.google.gms.google-services'

android {
    // Pastikan minSdk minimal 21
    defaultConfig {
        minSdk = 21
        // ...
    }
}
```

#### `android/app/src/main/AndroidManifest.xml`

Tambahkan permission dan metadata:

```xml
<manifest xmlns:android="http://schemas.android.com/apk/res/android">

    <!-- Permission untuk notifikasi (Android 13+) -->
    <uses-permission android:name="android.permission.POST_NOTIFICATIONS"/>
    <!-- Permission internet (biasanya sudah ada) -->
    <uses-permission android:name="android.permission.INTERNET"/>

    <application ...>

        <!-- Default notification channel -->
        <meta-data
            android:name="com.google.firebase.messaging.default_notification_channel_id"
            android:value="kepitink_notifications" />

        <!-- Default notification icon -->
        <meta-data
            android:name="com.google.firebase.messaging.default_notification_icon"
            android:resource="@mipmap/ic_launcher" />

        <!-- ... existing activities -->
    </application>
</manifest>
```

### 3.2 iOS (Opsional)

1. Buka `ios/Runner.xcworkspace` di Xcode
2. Runner → Signing & Capabilities → **+ Capability**:
   - Tambahkan **Push Notifications**
   - Tambahkan **Background Modes** → centang **Remote notifications**
3. Pastikan `ios/Runner/Info.plist` sudah berisi `FirebaseAppDelegateProxyEnabled`:
   ```xml
   <key>FirebaseAppDelegateProxyEnabled</key>
   <false/>
   ```

---

## 4. Inisialisasi Firebase di Flutter

### `lib/main.dart`

```dart
import 'package:firebase_core/firebase_core.dart';
import 'package:flutter/material.dart';
import 'firebase_options.dart';
import 'services/notification_service.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();

  // 1. Inisialisasi Firebase
  await Firebase.initializeApp(
    options: DefaultFirebaseOptions.currentPlatform,
  );

  // 2. Inisialisasi Notification Service
  await NotificationService.instance.initialize();

  runApp(const MyApp());
}
```

---

## 5. Notification Service — Kode Utama

Buat file `lib/services/notification_service.dart`:

```dart
import 'dart:convert';
import 'dart:io';

import 'package:firebase_messaging/firebase_messaging.dart';
import 'package:flutter/foundation.dart';
import 'package:flutter_local_notifications/flutter_local_notifications.dart';
import 'package:http/http.dart' as http;

/// Handler untuk notifikasi saat app di background/terminated.
/// HARUS berupa top-level function (bukan method dari class).
@pragma('vm:entry-point')
Future<void> _firebaseMessagingBackgroundHandler(RemoteMessage message) async {
  debugPrint('FCM Background: ${message.notification?.title}');
  // Bisa tambahkan logic seperti simpan ke local storage
}

class NotificationService {
  NotificationService._();
  static final NotificationService instance = NotificationService._();

  final FirebaseMessaging _messaging = FirebaseMessaging.instance;
  final FlutterLocalNotificationsPlugin _localNotifications =
      FlutterLocalNotificationsPlugin();

  /// Callback saat user tap notifikasi — set ini dari widget/page utama
  Function(Map<String, dynamic> data)? onNotificationTap;

  /// FCM token saat ini
  String? _currentToken;
  String? get currentToken => _currentToken;

  // =========================================================================
  // INITIALIZATION
  // =========================================================================

  Future<void> initialize() async {
    // 1. Request permission (wajib untuk iOS & Android 13+)
    await _requestPermission();

    // 2. Setup local notifications (untuk foreground)
    await _setupLocalNotifications();

    // 3. Register background handler
    FirebaseMessaging.onBackgroundMessage(_firebaseMessagingBackgroundHandler);

    // 4. Ambil FCM token
    _currentToken = await _messaging.getToken();
    debugPrint('🔑 FCM Token: $_currentToken');

    // 5. Listen token refresh
    _messaging.onTokenRefresh.listen((newToken) {
      _currentToken = newToken;
      debugPrint('🔄 FCM Token refreshed: $newToken');
      // Kirim token baru ke backend
      // Panggil: sendTokenToServer(newToken);
    });

    // 6. Handle foreground messages
    FirebaseMessaging.onMessage.listen(_handleForegroundMessage);

    // 7. Handle notification tap saat app di background
    FirebaseMessaging.onMessageOpenedApp.listen(_handleNotificationTap);

    // 8. Cek apakah app dibuka dari notifikasi (terminated state)
    final initialMessage = await _messaging.getInitialMessage();
    if (initialMessage != null) {
      _handleNotificationTap(initialMessage);
    }
  }

  // =========================================================================
  // PERMISSION
  // =========================================================================

  Future<void> _requestPermission() async {
    final settings = await _messaging.requestPermission(
      alert: true,
      announcement: false,
      badge: true,
      carPlay: false,
      criticalAlert: false,
      provisional: false,
      sound: true,
    );

    debugPrint('📱 Notification permission: ${settings.authorizationStatus}');
  }

  // =========================================================================
  // LOCAL NOTIFICATIONS SETUP (untuk foreground)
  // =========================================================================

  Future<void> _setupLocalNotifications() async {
    // Android initialization
    const androidSettings = AndroidInitializationSettings(
      '@mipmap/ic_launcher',
    );

    // iOS initialization
    const iosSettings = DarwinInitializationSettings(
      requestAlertPermission: false,
      requestBadgePermission: false,
      requestSoundPermission: false,
    );

    const initSettings = InitializationSettings(
      android: androidSettings,
      iOS: iosSettings,
    );

    await _localNotifications.initialize(
      initSettings,
      onDidReceiveNotificationResponse: (details) {
        // Handle tap pada local notification
        if (details.payload != null) {
          final data = jsonDecode(details.payload!);
          onNotificationTap?.call(Map<String, dynamic>.from(data));
        }
      },
    );

    // Buat notification channel untuk Android
    const androidChannel = AndroidNotificationChannel(
      'kepitink_notifications', // ID harus sama dengan di AndroidManifest
      'Notifikasi Kepitink',
      description: 'Notifikasi untuk aplikasi Kepitink',
      importance: Importance.high,
      playSound: true,
    );

    await _localNotifications
        .resolvePlatformSpecificImplementation<
          AndroidFlutterLocalNotificationsPlugin
        >()
        ?.createNotificationChannel(androidChannel);
  }

  // =========================================================================
  // MESSAGE HANDLERS
  // =========================================================================

  /// Tampilkan notifikasi lokal saat app di foreground.
  void _handleForegroundMessage(RemoteMessage message) {
    debugPrint('📩 Foreground message: ${message.notification?.title}');

    final notification = message.notification;
    if (notification == null) return;

    _localNotifications.show(
      notification.hashCode,
      notification.title,
      notification.body,
      NotificationDetails(
        android: AndroidNotificationDetails(
          'kepitink_notifications',
          'Notifikasi Kepitink',
          channelDescription: 'Notifikasi untuk aplikasi Kepitink',
          importance: Importance.high,
          priority: Priority.high,
          icon: '@mipmap/ic_launcher',
        ),
        iOS: const DarwinNotificationDetails(
          presentAlert: true,
          presentBadge: true,
          presentSound: true,
        ),
      ),
      payload: jsonEncode(message.data),
    );
  }

  /// Handle ketika user tap notifikasi.
  void _handleNotificationTap(RemoteMessage message) {
    debugPrint('👆 Notification tapped: ${message.data}');
    onNotificationTap?.call(message.data);
  }

  // =========================================================================
  // TOKEN MANAGEMENT — KIRIM KE BACKEND LARAVEL
  // =========================================================================

  /// Kirim FCM token ke backend Laravel setelah login.
  /// Panggil ini setelah user berhasil login dan punya JWT token.
  Future<bool> sendTokenToServer({
    required String jwtToken,
    required String baseUrl,
    String? deviceName,
  }) async {
    final fcmToken = _currentToken;
    if (fcmToken == null) {
      debugPrint('⚠️ FCM token belum tersedia');
      return false;
    }

    try {
      final response = await http.post(
        Uri.parse('$baseUrl/api/fcm-token'),
        headers: {
          'Authorization': 'Bearer $jwtToken',
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: jsonEncode({
          'token': fcmToken,
          'device_name': deviceName ?? _getDeviceName(),
        }),
      );

      if (response.statusCode == 200) {
        debugPrint('FCM token berhasil dikirim ke server');
        return true;
      } else {
        debugPrint('Gagal kirim FCM token: ${response.body}');
        return false;
      }
    } catch (e) {
      debugPrint('Error kirim FCM token: $e');
      return false;
    }
  }

  /// Hapus FCM token dari backend saat logout.
  Future<bool> removeTokenFromServer({
    required String jwtToken,
    required String baseUrl,
  }) async {
    final fcmToken = _currentToken;
    if (fcmToken == null) return true;

    try {
      final response = await http.delete(
        Uri.parse('$baseUrl/api/fcm-token'),
        headers: {
          'Authorization': 'Bearer $jwtToken',
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: jsonEncode({'token': fcmToken}),
      );

      if (response.statusCode == 200) {
        debugPrint('✅ FCM token berhasil dihapus dari server');
        return true;
      } else {
        debugPrint('❌ Gagal hapus FCM token: ${response.body}');
        return false;
      }
    } catch (e) {
      debugPrint('❌ Error hapus FCM token: $e');
      return false;
    }
  }

  String _getDeviceName() {
    if (Platform.isAndroid) return 'Android';
    if (Platform.isIOS) return 'iOS';
    return 'Unknown';
  }
}
```

---

## 6. Integrasi dengan Auth Flow (Login/Logout)

### 6.1 Setelah Login Berhasil

Setelah mendapatkan JWT token dari `POST /api/auth/login`, kirim FCM token ke backend:

```dart
// Di login handler / login page
Future<void> onLoginSuccess(String jwtToken) async {
  // Simpan JWT token ke storage (SharedPreferences/SecureStorage)
  await storage.write(key: 'jwt_token', value: jwtToken);

  // Kirim FCM token ke backend
  await NotificationService.instance.sendTokenToServer(
    jwtToken: jwtToken,
    baseUrl: 'https://your-api-url.com',  // Ganti dengan URL API kamu
    deviceName: 'Samsung Galaxy S24',      // Opsional
  );

  // Setup token refresh listener agar auto-update
  FirebaseMessaging.instance.onTokenRefresh.listen((newToken) async {
    final jwt = await storage.read(key: 'jwt_token');
    if (jwt != null) {
      await NotificationService.instance.sendTokenToServer(
        jwtToken: jwt,
        baseUrl: 'https://your-api-url.com',
      );
    }
  });
}
```

### 6.2 Sebelum Logout

Hapus FCM token dari backend sebelum invalidate JWT:

```dart
Future<void> onLogout() async {
  final jwtToken = await storage.read(key: 'jwt_token');

  if (jwtToken != null) {
    // 1. Hapus FCM token dari backend
    await NotificationService.instance.removeTokenFromServer(
      jwtToken: jwtToken,
      baseUrl: 'https://your-api-url.com',
    );

    // 2. Logout dari API (invalidate JWT)
    await http.post(
      Uri.parse('https://your-api-url.com/api/auth/logout'),
      headers: {'Authorization': 'Bearer $jwtToken'},
    );
  }

  // 3. Hapus local storage
  await storage.deleteAll();

  // 4. Navigate ke login page
  // Navigator.pushReplacementNamed(context, '/login');
}
```

---

## 7. API Reference — Endpoint Notifikasi

Semua endpoint membutuhkan header:

```
Authorization: Bearer <jwt_token>
Accept: application/json
Content-Type: application/json
```

### 7.1 FCM Token Management

#### `POST /api/fcm-token` — Simpan FCM Token

Kirim setelah login untuk mendaftarkan device.

**Request Body:**

```json
{
  "token": "eF1kX9...",
  "device_name": "Samsung Galaxy S24"
}
```

| Field | Type | Required | Keterangan |
|-------|------|----------|------------|
| `token` | string | ✅ | FCM device token dari `FirebaseMessaging.instance.getToken()` |
| `device_name` | string | ❌ | Nama device (opsional, untuk identifikasi) |

**Response (200):**

```json
{
  "statuscode": 200,
  "msg": "FCM token berhasil disimpan.",
  "data": {
    "id": 1,
    "id_user": 5,
    "token": "eF1kX9...",
    "device_name": "Samsung Galaxy S24",
    "created_at": "2026-06-12T10:00:00.000000Z",
    "updated_at": "2026-06-12T10:00:00.000000Z"
  }
}
```

> **Catatan:** Endpoint ini menggunakan **upsert** — jika token sudah ada untuk user tersebut, hanya `device_name` yang diupdate. Tidak akan ada duplikat.

---

#### `DELETE /api/fcm-token` — Hapus FCM Token

Kirim sebelum logout untuk berhenti menerima notifikasi di device ini.

**Request Body:**

```json
{
  "token": "eF1kX9..."
}
```

**Response (200):**

```json
{
  "statuscode": 200,
  "msg": "FCM token berhasil dihapus.",
  "data": []
}
```

---

### 7.2 Notifikasi History

#### `GET /api/notifikasi` — Daftar Notifikasi (Paginated)

**Query Parameters:**

| Param | Type | Default | Keterangan |
|-------|------|---------|------------|
| `page` | int | 1 | Halaman ke-n |
| `limit` | int | 15 | Jumlah per halaman (max: 100) |
| `filter` | string | `semua` | Filter status baca: `semua` (semua notifikasi), `dibaca` (hanya yang sudah dibaca), `belum_dibaca` (hanya yang belum dibaca). Invalid filter mengembalikan status 422. |

**Response (200):**

```json
{
  "statuscode": 200,
  "msg": "Daftar notifikasi berhasil diambil.",
  "filter": "semua",
  "data": [
    {
      "id": 10,
      "id_user": 5,
      "judul": "Permintaan Pertemanan",
      "pesan": "Budi mengirim permintaan pertemanan.",
      "tipe": "pertemanan",
      "data": {
        "pertemanan_id": "3",
        "aksi": "permintaan_masuk"
      },
      "dibaca_at": null,
      "created_at": "2026-06-12T10:30:00.000000Z",
      "updated_at": "2026-06-12T10:30:00.000000Z"
    },
    {
      "id": 9,
      "id_user": 5,
      "judul": "Hutang Baru",
      "pesan": "Andi mencatat hutang kamu sebesar Rp50.000.",
      "tipe": "hutang",
      "data": {
        "hutang_id": "7",
        "aksi": "hutang_baru"
      },
      "dibaca_at": "2026-06-12T09:00:00.000000Z",
      "created_at": "2026-06-12T08:00:00.000000Z",
      "updated_at": "2026-06-12T09:00:00.000000Z"
    }
  ],
  "pagination": {
    "current_page": 1,
    "last_page": 3,
    "per_page": 15,
    "total": 42
  }
}
```

---

#### `GET /api/notifikasi/belum-dibaca` — Jumlah Belum Dibaca

**Response (200):**

```json
{
  "statuscode": 200,
  "msg": "Jumlah notifikasi belum dibaca.",
  "data": {
    "count": 5
  }
}
```

> **Tip Flutter:** Gunakan endpoint ini untuk menampilkan badge count di ikon lonceng.

---

#### `PUT /api/notifikasi/{id}/baca` — Tandai Satu Dibaca

**Response (200):**

```json
{
  "statuscode": 200,
  "msg": "Notifikasi ditandai sudah dibaca.",
  "data": {
    "id": 10,
    "id_user": 5,
    "judul": "Permintaan Pertemanan",
    "pesan": "Budi mengirim permintaan pertemanan.",
    "tipe": "pertemanan",
    "data": { "pertemanan_id": "3", "aksi": "permintaan_masuk" },
    "dibaca_at": "2026-06-12T10:35:00.000000Z",
    "created_at": "2026-06-12T10:30:00.000000Z",
    "updated_at": "2026-06-12T10:35:00.000000Z"
  }
}
```

---

#### `PUT /api/notifikasi/baca-semua` — Tandai Semua Dibaca

**Response (200):**

```json
{
  "statuscode": 200,
  "msg": "Semua notifikasi ditandai sudah dibaca.",
  "data": []
}
```

---

## 8. Menampilkan Notifikasi di UI

### 8.1 Model Notifikasi

```dart
class NotifikasiModel {
  final int id;
  final int idUser;
  final String judul;
  final String pesan;
  final String tipe;
  final Map<String, dynamic>? data;
  final DateTime? dibacaAt;
  final DateTime createdAt;

  NotifikasiModel({
    required this.id,
    required this.idUser,
    required this.judul,
    required this.pesan,
    required this.tipe,
    this.data,
    this.dibacaAt,
    required this.createdAt,
  });

  bool get sudahDibaca => dibacaAt != null;

  factory NotifikasiModel.fromJson(Map<String, dynamic> json) {
    return NotifikasiModel(
      id: json['id'],
      idUser: json['id_user'],
      judul: json['judul'],
      pesan: json['pesan'],
      tipe: json['tipe'],
      data: json['data'] != null
          ? Map<String, dynamic>.from(json['data'])
          : null,
      dibacaAt: json['dibaca_at'] != null
          ? DateTime.parse(json['dibaca_at'])
          : null,
      createdAt: DateTime.parse(json['created_at']),
    );
  }
}
```

### 8.2 Notification API Service

```dart
import 'dart:convert';
import 'package:http/http.dart' as http;

class NotifikasiApiService {
  final String baseUrl;
  final String jwtToken;

  NotifikasiApiService({required this.baseUrl, required this.jwtToken});

  Map<String, String> get _headers => {
        'Authorization': 'Bearer $jwtToken',
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      };

  /// Ambil daftar notifikasi (paginated)
  Future<List<NotifikasiModel>> getNotifikasi({int page = 1, int limit = 15}) async {
    final response = await http.get(
      Uri.parse('$baseUrl/api/notifikasi?page=$page&limit=$limit'),
      headers: _headers,
    );

    if (response.statusCode == 200) {
      final json = jsonDecode(response.body);
      final List data = json['data'];
      return data.map((e) => NotifikasiModel.fromJson(e)).toList();
    }
    throw Exception('Gagal mengambil notifikasi');
  }

  /// Ambil jumlah notifikasi belum dibaca
  Future<int> getUnreadCount() async {
    final response = await http.get(
      Uri.parse('$baseUrl/api/notifikasi/belum-dibaca'),
      headers: _headers,
    );

    if (response.statusCode == 200) {
      final json = jsonDecode(response.body);
      return json['data']['count'] as int;
    }
    throw Exception('Gagal mengambil unread count');
  }

  /// Tandai satu notifikasi sebagai dibaca
  Future<void> markAsRead(int id) async {
    await http.put(
      Uri.parse('$baseUrl/api/notifikasi/$id/baca'),
      headers: _headers,
    );
  }

  /// Tandai semua notifikasi sebagai dibaca
  Future<void> markAllAsRead() async {
    await http.put(
      Uri.parse('$baseUrl/api/notifikasi/baca-semua'),
      headers: _headers,
    );
  }
}
```

### 8.3 Contoh Widget — Badge Notifikasi

```dart
class NotificationBadge extends StatefulWidget {
  const NotificationBadge({super.key});

  @override
  State<NotificationBadge> createState() => _NotificationBadgeState();
}

class _NotificationBadgeState extends State<NotificationBadge> {
  int _unreadCount = 0;

  @override
  void initState() {
    super.initState();
    _loadUnreadCount();

    // Auto-refresh saat ada notifikasi foreground
    FirebaseMessaging.onMessage.listen((_) => _loadUnreadCount());
  }

  Future<void> _loadUnreadCount() async {
    final api = NotifikasiApiService(
      baseUrl: 'https://your-api-url.com',
      jwtToken: 'your-jwt-token',
    );
    final count = await api.getUnreadCount();
    if (mounted) setState(() => _unreadCount = count);
  }

  @override
  Widget build(BuildContext context) {
    return Stack(
      children: [
        IconButton(
          icon: const Icon(Icons.notifications_outlined),
          onPressed: () {
            Navigator.pushNamed(context, '/notifikasi');
          },
        ),
        if (_unreadCount > 0)
          Positioned(
            right: 4,
            top: 4,
            child: Container(
              padding: const EdgeInsets.all(4),
              decoration: const BoxDecoration(
                color: Colors.red,
                shape: BoxShape.circle,
              ),
              child: Text(
                _unreadCount > 99 ? '99+' : '$_unreadCount',
                style: const TextStyle(color: Colors.white, fontSize: 10),
              ),
            ),
          ),
      ],
    );
  }
}
```

---

## 9. Handle Notification Tap (Navigation)

Setup navigasi berdasarkan tipe notifikasi saat user mengetuk notifikasi:

```dart
// Di main.dart atau home page, set callback:
NotificationService.instance.onNotificationTap = (data) {
  final tipe = data['tipe'];
  final aksi = data['aksi'];

  switch (tipe) {
    case 'pertemanan':
      if (aksi == 'permintaan_masuk') {
        // Navigate ke halaman permintaan pertemanan masuk
        Navigator.pushNamed(context, '/pertemanan/permintaan-masuk');
      } else if (aksi == 'permintaan_diterima') {
        // Navigate ke halaman daftar teman
        Navigator.pushNamed(context, '/pertemanan');
      }
      break;

    case 'hutang':
      final hutangId = data['hutang_id'];
      if (aksi == 'hutang_baru') {
        // Navigate ke halaman hutang saya
        Navigator.pushNamed(context, '/hutang/hutang-saya');
      } else if (aksi == 'hutang_update') {
        // Navigate ke detail hutang
        Navigator.pushNamed(context, '/hutang/detail', arguments: hutangId);
      }
      break;

    case 'tagihan':
      // Navigate ke halaman tagihan
      Navigator.pushNamed(context, '/tagihan');
      break;

    default:
      // Navigate ke halaman notifikasi
      Navigator.pushNamed(context, '/notifikasi');
  }
};
```

---

## 10. Background & Terminated Notification

### Bagaimana Notifikasi Bekerja di Setiap State

| App State | Notification Display | Data Handler |
|-----------|---------------------|--------------|
| **Foreground** | `flutter_local_notifications` menampilkan manual | `FirebaseMessaging.onMessage` |
| **Background** | System tray otomatis dari FCM | `FirebaseMessaging.onMessageOpenedApp` |
| **Terminated** | System tray otomatis dari FCM | `FirebaseMessaging.instance.getInitialMessage()` |

### Penting!

- **Background handler** (`_firebaseMessagingBackgroundHandler`) HARUS berupa **top-level function**, bukan method dari class
- **Jangan** melakukan operasi UI di background handler
- Background handler cocok untuk: simpan ke local DB, update badge count, dll.

```dart
// ✅ BENAR — top-level function
@pragma('vm:entry-point')
Future<void> _firebaseMessagingBackgroundHandler(RemoteMessage message) async {
  // Proses data, JANGAN akses UI
  debugPrint('Background: ${message.notification?.title}');
}

// ❌ SALAH — method dari class
class MyService {
  Future<void> handleBackground(RemoteMessage message) async { ... }
}
```

---

## 11. Tipe Notifikasi & Data Payload

Berikut semua tipe notifikasi yang dikirim oleh backend:

### 11.1 Pertemanan (`tipe: "pertemanan"`)

| Aksi | Trigger | Judul | Contoh Pesan |
|------|---------|-------|-------------|
| `permintaan_masuk` | User A kirim permintaan ke User B | "Permintaan Pertemanan" | "Budi mengirim permintaan pertemanan." |
| `permintaan_diterima` | User B terima permintaan User A | "Pertemanan Diterima" | "Andi menerima permintaan pertemanan kamu." |
| `permintaan_dibatalkan` | User A/B batalkan permintaan | "Permintaan Pertemanan" | "Budi membatalkan permintaan pertemanan." |

**Data payload:**
```json
{
  "pertemanan_id": "3",
  "aksi": "permintaan_masuk",
  "tipe": "pertemanan",
  "click_action": "FLUTTER_NOTIFICATION_CLICK"
}
```

### 11.2 Hutang (`tipe: "hutang"`)

| Aksi | Trigger | Judul | Contoh Pesan |
|------|---------|-------|-------------|
| `hutang_baru` | User A catat hutang User B | "Hutang Baru" | "Budi mencatat hutang kamu sebesar Rp50.000." |
| `hutang_update` | User A update status hutang | "Update Hutang" | "Budi mengubah status hutang Rp50.000 menjadi lunas." |

**Data payload:**
```json
{
  "hutang_id": "7",
  "aksi": "hutang_baru",
  "tipe": "hutang",
  "click_action": "FLUTTER_NOTIFICATION_CLICK"
}
```

### 11.3 Tagihan (`tipe: "tagihan"`)

| Aksi | Trigger | Judul | Contoh Pesan |
|------|---------|-------|-------------|
| `tagihan_terlambat` | Scheduler harian 00:01 | "Tagihan Terlambat" | "Tagihan "Listrik" sebesar Rp500.000 sudah melewati jatuh tempo." |
| `tagihan_pengingat` | Scheduler harian 08:00 | "Pengingat Tagihan" | "Tagihan "WiFi" sebesar Rp350.000 jatuh tempo besok." |

**Data payload:**
```json
{
  "aksi": "tagihan_terlambat",
  "jumlah_tagihan": "3",
  "tipe": "tagihan",
  "click_action": "FLUTTER_NOTIFICATION_CLICK"
}
```

> **Catatan:** Semua value di `data` payload FCM selalu bertipe **string** (ini requirement dari FCM). Parse ke int/bool di Flutter jika perlu.

---

## 12. Troubleshooting

### Token tidak muncul / null

```
⚠️ FCM token belum tersedia
```

**Solusi:**
- Pastikan Firebase sudah diinisialisasi sebelum memanggil `getToken()`
- Pastikan Google Play Services terinstall di emulator/device
- Pastikan koneksi internet aktif

### Notifikasi tidak muncul di foreground

**Solusi:**
- Pastikan `flutter_local_notifications` sudah disetup
- Pastikan notification channel sudah dibuat (`kepitink_notifications`)
- Cek `AndroidManifest.xml` ada permission `POST_NOTIFICATIONS`

### Notifikasi tidak muncul di background/terminated

**Solusi:**
- Pastikan `_firebaseMessagingBackgroundHandler` adalah **top-level function**
- Pastikan ada `@pragma('vm:entry-point')` di atas function
- Cek Firebase Console → Cloud Messaging → kirim test message

### Error 401 saat kirim token ke server

**Solusi:**
- JWT token mungkin expired — panggil refresh token dulu (`POST /api/auth/refresh`)
- Pastikan header `Authorization: Bearer <token>` benar

### Notifikasi masuk tapi tidak ada data payload

**Solusi:**
- Cek apakah backend mengirim `data` field di FCM message
- Pastikan value di `data` semuanya string (FCM requirement)

### Test notifikasi dari Firebase Console

1. Buka [Firebase Console](https://console.firebase.google.com/)
2. **Messaging** → **Create your first campaign** → **Firebase Notification messages**
3. Masukkan judul & body
4. **Send test message** → masukkan FCM token device
5. Klik **Test**

---

## Alur Lengkap (Flowchart)

```
┌─────────────────────────────────────────────────────────────────┐
│                     FLUTTER APP STARTUP                         │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  1. Firebase.initializeApp()                                    │
│  2. NotificationService.instance.initialize()                   │
│     ├── requestPermission()                                     │
│     ├── setupLocalNotifications()                               │
│     ├── getToken() → FCM Token                                  │
│     ├── onTokenRefresh listener                                 │
│     ├── onMessage listener (foreground)                         │
│     ├── onMessageOpenedApp listener (background tap)            │
│     └── getInitialMessage() (terminated tap)                    │
│                                                                 │
├─────────────────────────────────────────────────────────────────┤
│                      USER LOGIN                                 │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  1. POST /api/auth/login → JWT Token                            │
│  2. POST /api/fcm-token  → { token: fcmToken }                 │
│     (Mendaftarkan device untuk menerima notifikasi)             │
│                                                                 │
├─────────────────────────────────────────────────────────────────┤
│                    MENERIMA NOTIFIKASI                           │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  Backend trigger (contoh: kirim permintaan pertemanan)           │
│  └── FcmService::sendToUser()                                   │
│      ├── Simpan ke tabel `notifikasis` (riwayat)                │
│      └── Kirim ke FCM HTTP v1 API → Push ke device              │
│                                                                 │
│  Flutter menerima:                                              │
│  ├── Foreground → local notification + auto-refresh badge       │
│  ├── Background → system notification → tap → navigate          │
│  └── Terminated → system notification → tap → navigate          │
│                                                                 │
├─────────────────────────────────────────────────────────────────┤
│                    BACA NOTIFIKASI                               │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  GET    /api/notifikasi              → Daftar (paginated)       │
│  GET    /api/notifikasi/belum-dibaca → Badge count              │
│  PUT    /api/notifikasi/{id}/baca    → Tandai 1 dibaca          │
│  PUT    /api/notifikasi/baca-semua   → Tandai semua dibaca      │
│                                                                 │
├─────────────────────────────────────────────────────────────────┤
│                      USER LOGOUT                                │
├─────────────────────────────────────────────────────────────────┤
│                                                                 │
│  1. DELETE /api/fcm-token → { token: fcmToken }                 │
│     (Berhenti menerima notifikasi di device ini)                │
│  2. POST /api/auth/logout → Invalidate JWT                      │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```
