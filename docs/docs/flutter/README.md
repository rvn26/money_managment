# 📱 Flutter API Documentation - Kelola Uang

Dokumentasi lengkap REST API dan Dart Models untuk integrasi Flutter.

## 📚 Dokumentasi

1. **[API_ENDPOINTS.md](API_ENDPOINTS.md)** - Semua endpoint API dengan contoh request/response
2. **[DART_MODELS.md](DART_MODELS.md)** - Semua model Dart untuk parsing JSON
3. **[API_SERVICE.md](API_SERVICE.md)** - Service class dan helper functions
4. **[EXAMPLES.md](EXAMPLES.md)** - Contoh implementasi lengkap

## ⚡ Quick Start

### Base URL
```
http://your-domain.com/api
```

### Authentication
Semua endpoint (kecuali register/login) memerlukan JWT token:
```
Authorization: Bearer {your_jwt_token}
```

### Standard Response Format
```json
{
  "statuscode": 200,
  "msg": "Success message",
  "data": { ... }
}
```

## 🎯 Main Features

- ✅ Authentication (Register, Login, Logout)
- ✅ Pemasukan (Income) Management
- ✅ Pengeluaran (Expense) Management
