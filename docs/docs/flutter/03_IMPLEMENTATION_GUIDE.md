# 💻 Implementation Guide - Flutter

Complete implementation example for Flutter integration.

## 📦 Setup

### 1. Add Dependencies (pubspec.yaml)
```yaml
dependencies:
  flutter:
    sdk: flutter
  http: ^1.1.0
  shared_preferences: ^2.2.2
  intl: ^0.18.1
```

### 2. Project Structure
```
lib/
├── models/
│   ├── api_response.dart
│   ├── user.dart
│   ├── pemasukan.dart
│   ├── pengeluaran.dart
│   ├── tagihan.dart
│   ├── hutang.dart
│   ├── pertemanan.dart
│   └── kategori.dart
├── services/
│   ├── api_service.dart
│   ├── auth_service.dart
│   ├── pemasukan_service.dart
│   ├── pengeluaran_service.dart
│   ├── tagihan_service.dart
│   ├── hutang_service.dart
│   └── pertemanan_service.dart
└── screens/
    ├── login_screen.dart
    ├── home_screen.dart
    └── ...
```

---

## 🔧 Base API Service

```dart
// lib/services/api_service.dart
import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';

class ApiService {
  static const String baseUrl = 'http://your-domain.com/api';
  
  // Get stored token
  Future<String?> getToken() async {
    final prefs = await SharedPreferences.getInstance();
    return prefs.getString('jwt_token');
  }
  
  // Save token
  Future<void> saveToken(String token) async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.setString('jwt_token', token);
  }
  
  // Delete token (logout)
  Future<void> deleteToken() async {
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('jwt_token');
  }
  
  // Get headers with token
  Future<Map<String, String>> _getHeaders() async {
    final token = await getToken();
    return {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      if (token != null) 'Authorization': 'Bearer $token',
    };
  }
  
  // Generic GET request
  Future<http.Response> get(String endpoint, {Map<String, String>? queryParams}) async {
    final uri = Uri.parse('$baseUrl$endpoint').replace(queryParameters: queryParams);
    final headers = await _getHeaders();
    
    try {
      final response = await http.get(uri, headers: headers);
      return response;
    } catch (e) {
      throw Exception('Network error: $e');
    }
  }
  
  // Generic POST request
  Future<http.Response> post(String endpoint, {required Map<String, dynamic> body}) async {
    final uri = Uri.parse('$baseUrl$endpoint');
    final headers = await _getHeaders();
    
    try {
      final response = await http.post(
        uri,
        headers: headers,
        body: jsonEncode(body),
      );
      return response;
    } catch (e) {
      throw Exception('Network error: $e');
    }
  }
  
  // Generic PUT request
  Future<http.Response> put(String endpoint, {required Map<String, dynamic> body}) async {
    final uri = Uri.parse('$baseUrl$endpoint');
    final headers = await _getHeaders();
    
    try {
      final response = await http.put(
        uri,
        headers: headers,
        body: jsonEncode(body),
      );
      return response;
    } catch (e) {
      throw Exception('Network error: $e');
    }
  }
  
  // Generic DELETE request
  Future<http.Response> delete(String endpoint) async {
    final uri = Uri.parse('$baseUrl$endpoint');
    final headers = await _getHeaders();
    
    try {
      final response = await http.delete(uri, headers: headers);
      return response;
    } catch (e) {
      throw Exception('Network error: $e');
    }
  }
}
```

---

## 🔐 Auth Service

```dart
// lib/services/auth_service.dart
import 'dart:convert';
import 'package:http/http.dart' as http;
import '../models/api_response.dart';
import '../models/user.dart';
import 'api_service.dart';

class AuthService {
  final ApiService _apiService = ApiService();
  
  // Register
  Future<ApiResponse<Map<String, dynamic>>> register({
    required String name,
    required String email,
    required String password,
    required String passwordConfirmation,
  }) async {
    final response = await _apiService.post('/auth/register', body: {
      'name': name,
      'email': email,
      'password': password,
      'password_confirmation': passwordConfirmation,
    });
    
    final jsonResponse = jsonDecode(response.body);
    
    if (response.statusCode == 201) {
      // Save token
      await _apiService.saveToken(jsonResponse['data']['token']);
      return ApiResponse.fromJson(jsonResponse, (data) => data as Map<String, dynamic>);
    } else {
      throw Exception(jsonResponse['msg'] ?? 'Registration failed');
    }
  }
  
  // Login
  Future<ApiResponse<Map<String, dynamic>>> login({
    required String email,
    required String password,
  }) async {
    final response = await _apiService.post('/auth/login', body: {
      'email': email,
      'password': password,
    });
    
    final jsonResponse = jsonDecode(response.body);
    
    if (response.statusCode == 200) {
      // Save token
      await _apiService.saveToken(jsonResponse['data']['token']);
      return ApiResponse.fromJson(jsonResponse, (data) => data as Map<String, dynamic>);
    } else {
      throw Exception(jsonResponse['msg'] ?? 'Login failed');
    }
  }
  
  // Logout
  Future<void> logout() async {
    try {
      await _apiService.post('/auth/logout', body: {});
    } finally {
      await _apiService.deleteToken();
    }
  }
  
  // Get Profile
  Future<ApiResponse<User>> getProfile() async {
    final response = await _apiService.get('/auth/profile');
    final jsonResponse = jsonDecode(response.body);
    
    if (response.statusCode == 200) {
      return ApiResponse.fromJson(jsonResponse, (data) => User.fromJson(data));
    } else {
      throw Exception(jsonResponse['msg'] ?? 'Failed to get profile');
    }
  }
  
  // Check if logged in
  Future<bool> isLoggedIn() async {
    final token = await _apiService.getToken();
    return token != null;
  }
}
```

---

## 💰 Pemasukan Service

```dart
// lib/services/pemasukan_service.dart
import 'dart:convert';
import '../models/api_response.dart';
import '../models/pemasukan.dart';
import 'api_service.dart';

class PemasukanService {
  final ApiService _apiService = ApiService();
  
  // Get list with filter
  Future<PaginationResponse<Pemasukan>> getList({
    String periode = 'bulan_ini',
    String? bulanCustom,
    int limit = 10,
    int page = 1,
  }) async {
    final queryParams = {
      'periode': periode,
      if (bulanCustom != null) 'bulan_custom': bulanCustom,
      'limit': limit.toString(),
      'page': page.toString(),
    };
    
    final response = await _apiService.get('/pemasukan', queryParams: queryParams);
    final jsonResponse = jsonDecode(response.body);
    
    if (response.statusCode == 200) {
      return PaginationResponse.fromJson(
        jsonResponse,
        (json) => Pemasukan.fromJson(json),
      );
    } else {
      throw Exception(jsonResponse['msg'] ?? 'Failed to get data');
    }
  }
  
  // Get by ID
  Future<ApiResponse<Pemasukan>> getById(int id) async {
    final response = await _apiService.get('/pemasukan/$id');
    final jsonResponse = jsonDecode(response.body);
    
    if (response.statusCode == 200) {
      return ApiResponse.fromJson(
        jsonResponse,
        (data) => Pemasukan.fromJson(data),
      );
    } else {
      throw Exception(jsonResponse['msg'] ?? 'Failed to get data');
    }
  }
  
  // Create
  Future<ApiResponse<Pemasukan>> create(Pemasukan pemasukan) async {
    final response = await _apiService.post(
      '/pemasukan',
      body: pemasukan.toJson(),
    );
    final jsonResponse = jsonDecode(response.body);
    
    if (response.statusCode == 201) {
      return ApiResponse.fromJson(
        jsonResponse,
        (data) => Pemasukan.fromJson(data),
      );
    } else {
      throw Exception(jsonResponse['msg'] ?? 'Failed to create');
    }
  }
  
  // Update
  Future<ApiResponse<Pemasukan>> update(int id, Pemasukan pemasukan) async {
    final response = await _apiService.put(
      '/pemasukan/$id',
      body: pemasukan.toJson(),
    );
    final jsonResponse = jsonDecode(response.body);
    
    if (response.statusCode == 200) {
      return ApiResponse.fromJson(
        jsonResponse,
        (data) => Pemasukan.fromJson(data),
      );
    } else {
      throw Exception(jsonResponse['msg'] ?? 'Failed to update');
    }
  }
  
  // Delete
  Future<void> delete(int id) async {
    final response = await _apiService.delete('/pemasukan/$id');
    final jsonResponse = jsonDecode(response.body);
    
    if (response.statusCode != 200) {
      throw Exception(jsonResponse['msg'] ?? 'Failed to delete');
    }
  }
}
```

---

## 🎯 Usage Example - Login Screen

```dart
// lib/screens/login_screen.dart
import 'package:flutter/material.dart';
import '../services/auth_service.dart';

class LoginScreen extends StatefulWidget {
  @override
  _LoginScreenState createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final _formKey = GlobalKey<FormState>();
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();
  final _authService = AuthService();
  bool _isLoading = false;
  
  Future<void> _handleLogin() async {
    if (!_formKey.currentState!.validate()) return;
    
    setState(() => _isLoading = true);
    
    try {
      final response = await _authService.login(
        email: _emailController.text,
        password: _passwordController.text,
      );
      
      // Navigate to home
      Navigator.pushReplacementNamed(context, '/home');
      
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text(response.msg)),
      );
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Login failed: $e')),
      );
    } finally {
      setState(() => _isLoading = false);
    }
  }
  
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Login')),
      body: Padding(
        padding: EdgeInsets.all(16),
        child: Form(
          key: _formKey,
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              TextFormField(
                controller: _emailController,
                decoration: InputDecoration(labelText: 'Email'),
                keyboardType: TextInputType.emailAddress,
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Email is required';
                  }
                  return null;
                },
              ),
              SizedBox(height: 16),
              TextFormField(
                controller: _passwordController,
                decoration: InputDecoration(labelText: 'Password'),
                obscureText: true,
                validator: (value) {
                  if (value == null || value.isEmpty) {
                    return 'Password is required';
                  }
                  return null;
                },
              ),
              SizedBox(height: 24),
              _isLoading
                  ? CircularProgressIndicator()
                  : ElevatedButton(
                      onPressed: _handleLogin,
                      child: Text('Login'),
                    ),
            ],
          ),
        ),
      ),
    );
  }
  
  @override
  void dispose() {
    _emailController.dispose();
    _passwordController.dispose();
    super.dispose();
  }
}
```

---

## 📊 Usage Example - Pemasukan List

```dart
// lib/screens/pemasukan_list_screen.dart
import 'package:flutter/material.dart';
import 'package:intl/intl.dart';
import '../models/pemasukan.dart';
import '../services/pemasukan_service.dart';

class PemasukanListScreen extends StatefulWidget {
  @override
  _PemasukanListScreenState createState() => _PemasukanListScreenState();
}

class _PemasukanListScreenState extends State<PemasukanListScreen> {
  final _pemasukanService = PemasukanService();
  List<Pemasukan> _list = [];
  bool _isLoading = false;
  String _periode = 'bulan_ini';
  
  @override
  void initState() {
    super.initState();
    _loadData();
  }
  
  Future<void> _loadData() async {
    setState(() => _isLoading = true);
    
    try {
      final response = await _pemasukanService.getList(
        periode: _periode,
        limit: 20,
      );
      
      setState(() {
        _list = response.data;
      });
    } catch (e) {
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Failed to load data: $e')),
      );
    } finally {
      setState(() => _isLoading = false);
    }
  }
  
  String _formatCurrency(double amount) {
    final formatter = NumberFormat.currency(
      locale: 'id_ID',
      symbol: 'Rp ',
      decimalDigits: 0,
    );
    return formatter.format(amount);
  }
  
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text('Pemasukan'),
        actions: [
          PopupMenuButton<String>(
            onSelected: (value) {
              setState(() => _periode = value);
              _loadData();
            },
            itemBuilder: (context) => [
              PopupMenuItem(value: 'bulan_ini', child: Text('Bulan Ini')),
              PopupMenuItem(value: 'minggu_ini', child: Text('Minggu Ini')),
              PopupMenuItem(value: 'semua', child: Text('Semua')),
            ],
          ),
        ],
      ),
      body: _isLoading
          ? Center(child: CircularProgressIndicator())
          : _list.isEmpty
              ? Center(child: Text('No data'))
              : ListView.builder(
                  itemCount: _list.length,
                  itemBuilder: (context, index) {
                    final item = _list[index];
                    return ListTile(
                      title: Text(item.jenis),
                      subtitle: Text(
                        DateFormat('dd MMM yyyy').format(item.tanggal),
                      ),
                      trailing: Text(
                        _formatCurrency(item.total),
                        style: TextStyle(
                          fontWeight: FontWeight.bold,
                          color: Colors.green,
                        ),
                      ),
                      onTap: () {
                        // Navigate to detail
                      },
                    );
                  },
                ),
      floatingActionButton: FloatingActionButton(
        onPressed: () {
          // Navigate to create form
        },
        child: Icon(Icons.add),
      ),
    );
  }
}
```

---

## 🔄 Error Handling Best Practices

```dart
class ApiException implements Exception {
  final String message;
  final int? statusCode;
  final Map<String, dynamic>? errors;
  
  ApiException(this.message, {this.statusCode, this.errors});
  
  @override
  String toString() => message;
}

// In service:
Future<ApiResponse<T>> _handleResponse<T>(
  http.Response response,
  T Function(dynamic) fromJson,
) async {
  final jsonResponse = jsonDecode(response.body);
  
  if (response.statusCode >= 200 && response.statusCode < 300) {
    return ApiResponse.fromJson(jsonResponse, fromJson);
  } else {
    throw ApiException(
      jsonResponse['msg'] ?? 'Request failed',
      statusCode: response.statusCode,
      errors: jsonResponse['data'],
    );
  }
}
```

---

## 💡 Tips & Best Practices

1. **State Management**: Consider using Provider, Riverpod, or Bloc for better state management
2. **Error Handling**: Always wrap API calls in try-catch blocks
3. **Loading States**: Show loading indicators during API calls
4. **Token Refresh**: Implement automatic token refresh logic
5. **Offline Support**: Consider adding local database (SQLite/Hive) for offline capability
6. **Retry Logic**: Add retry mechanism for failed requests
7. **Cache**: Implement caching strategy for better performance
8. **Validation**: Validate data before sending to API
9. **Security**: Never hardcode API keys or sensitive data
10. **Testing**: Write unit tests for services

---

## 📱 Complete Working Example

Check the complete working example in the repository:
- Full CRUD implementation
- State management with Provider
- Error handling
- Loading states
- Form validation
- Navigation

This guide should get you started with integrating the Kelola Uang API into your Flutter app! 🚀
