# 📱 Flutter API Documentation - Kelola Uang

Complete REST API documentation with Dart models for Flutter integration.

## 📚 Documentation Files

1. **[01_API_ENDPOINTS.md](01_API_ENDPOINTS.md)** - Complete API Endpoints Reference
2. **[02_DART_MODELS.md](02_DART_MODELS.md)** - All Dart Models (Copy-Paste Ready)
3. **[03_IMPLEMENTATION_GUIDE.md](03_IMPLEMENTATION_GUIDE.md)** - Complete Implementation Guide with Examples

## ⚡ Quick Start

### Base URL

```
http://your-domain.com/api
```

### Authentication

All endpoints (except register/login) require JWT token:

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

## 🎯 API Features

### Authentication

- ✅ Register, Login, Logout
- ✅ Get Profile, Refresh Token
- ✅ JWT Token based

### Pemasukan (Income)

- ✅ CRUD Operations
- ✅ Filter by Period (bulan_ini, minggu_ini, custom, semua)
- ✅ Pagination Support

### Pengeluaran (Expense)

- ✅ CRUD Operations
- ✅ Kategori Integration
- ✅ Multiple Payment Methods
- ✅ Filter by Period + Pagination

### Tagihan (Bills)

- ✅ CRUD Operations
- ✅ Auto Create Expense when status = lunas
- ✅ Recurring Bills (sekali_bayar, bulanan, tahunan)
- ✅ Filter by Period + Pagination

### Hutang (Debts)

- ✅ CRUD Operations
- ✅ Friend Integration (Pertemanan)
- ✅ Hutang Saya (Debts I Owe)
- ✅ Filter by Period + Pagination

### Pertemanan (Friends)

- ✅ Send/Accept/Reject Friend Requests
- ✅ List Friends (Accepted)
- ✅ List Pending Requests (In/Out)
- ✅ Search User by Email

### Kategori (Categories)

- ✅ Kategori Pengeluaran (Expense Categories)
- ✅ Kategori Tagihan (Bill Categories)
- ✅ CRUD Operations

### Dashboard

- ✅ Total Pemasukan, Pengeluaran, Saldo
- ✅ Tagihan Menunggu
- ✅ Batas Harian Info

### Batas Harian (Daily Limit)

- ✅ Set/Update/Delete Daily Spending Limit

## 📊 Filter Periode (All Transactions)

All transaction endpoints support period filtering:

**Query Parameters:**

- `periode`: `semua` | `bulan_ini` | `minggu_ini` | `custom` (default: `bulan_ini`)
- `bulan_custom`: `YYYY-MM` format (e.g., `2024-06`) - for `custom` period
- `limit`: Items per page (default: 10, max: 100)

**Examples:**

```
GET /api/pemasukan                                    // Default: bulan_ini
GET /api/pemasukan?periode=minggu_ini                 // This week
GET /api/pemasukan?periode=semua                      // All data
GET /api/pemasukan?periode=custom&bulan_custom=2024-01  // January 2024
```

## 🚀 Getting Started

### Step 1: Setup

1. Add dependencies to `pubspec.yaml`:

```yaml
dependencies:
  http: ^1.1.0
  shared_preferences: ^2.2.2
  intl: ^0.18.1
```

### Step 2: Copy Models

1. Copy all models from [02_DART_MODELS.md](02_DART_MODELS.md)
2. Place them in `lib/models/` directory

### Step 3: Implement Services

1. Follow the implementation guide in [03_IMPLEMENTATION_GUIDE.md](03_IMPLEMENTATION_GUIDE.md)
2. Create `api_service.dart` for base HTTP client
3. Create service classes for each feature

### Step 4: Implement UI

1. Use the service classes in your screens
2. Handle loading states and errors properly
3. Check examples in implementation guide

## 📖 Documentation Structure

### [01_API_ENDPOINTS.md](01_API_ENDPOINTS.md)

Complete reference of all API endpoints including:

- Request methods and URLs
- Request body examples
- Response examples
- Query parameters
- HTTP status codes

### [02_DART_MODELS.md](02_DART_MODELS.md)

Ready-to-use Dart models including:

- Base response models (ApiResponse, PaginationResponse)
- All entity models (User, Pemasukan, Pengeluaran, etc.)
- Complete `fromJson()` and `toJson()` methods
- Null-safety support
- Enums for status fields

### [03_IMPLEMENTATION_GUIDE.md](03_IMPLEMENTATION_GUIDE.md)

Step-by-step implementation guide including:

- Project structure
- Base API service implementation
- Authentication service
- CRUD service examples
- Screen implementation examples
- Error handling best practices
- Tips and best practices

## 💡 Key Features

### 🔐 Security

- JWT authentication
- Token stored in SharedPreferences
- Auto-include token in all requests

### 🎨 Flutter Ready

- All models with null-safety
- Complete fromJson/toJson
- Type-safe implementations

### 📱 Production Ready

- Error handling
- Pagination support
- Filter by period
- Loading states

### 🚀 Developer Friendly

- Copy-paste ready code
- Complete examples
- Clear documentation
- Best practices included

## 🔄 Common Workflows

### Login Flow

```
1. User enters email & password
2. Call AuthService.login()
3. Token saved automatically
4. Navigate to home screen
```

### Create Transaction Flow

```
1. User fills form
2. Call service.create(model)
3. Handle success/error
4. Refresh list
```

### List with Filter Flow

```
1. User selects filter (periode)
2. Call service.getList(periode: ...)
3. Display data in ListView
4. Handle pagination if needed
```

## ⚠️ Important Notes

1. **Base URL**: Change `http://your-domain.com/api` to your actual API URL
2. **Token Storage**: JWT token stored in SharedPreferences (consider more secure storage for production)
3. **Date Format**: Always use `YYYY-MM-DD` format for dates
4. **Decimal Numbers**: Send money amounts as numbers, not strings
5. **Error Handling**: Always wrap API calls in try-catch blocks
6. **Loading States**: Show loading indicators during API calls
7. **Validation**: Validate input before sending to API

## 🐛 Troubleshooting

### "Network error" or timeout

- Check internet connection
- Verify base URL is correct
- Check if API server is running

### "Unauthorized" (401)

- Token might be expired
- Implement token refresh logic
- Ask user to login again

### "Validation Error" (422)

- Check request body format
- Verify all required fields are sent
- Check field types (number vs string)

### Data not showing in list

- Check if filter period is correct
- Try with `periode=semua` to see all data
- Check pagination response

## 📞 Support

For API issues or questions:

1. Check this documentation first
2. Review the implementation guide
3. Check example code
4. Contact backend developer

## 🎉 Success Tips

1. ✅ Start with authentication first
2. ✅ Test each endpoint individually
3. ✅ Use Postman/Insomnia to test API before coding
4. ✅ Implement error handling from the start
5. ✅ Use state management (Provider/Riverpod/Bloc)
6. ✅ Add loading states for better UX
7. ✅ Implement proper validation
8. ✅ Test with real data
9. ✅ Handle edge cases
10. ✅ Write clean, maintainable code

---

Happy coding! 🚀 If you follow this documentation, you'll have a fully functional Flutter app integrated with the Kelola Uang API!
