
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Scan Struk</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="bg-white p-8 rounded-xl shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Test API Scan Struk</h2>
        
        <form action="{{ route('scan.receipt') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf <div>
                <label class="block text-sm font-medium text-gray-700">Pilih Gambar Struk</label>
                <input type="file" name="receipt" required
                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori (Pilih Minimal Satu)</label>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="categories[]" value="Makanan" class="rounded text-blue-600">
                        <span class="ml-2 text-sm text-gray-600">Makanan</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="categories[]" value="Minuman" class="rounded text-blue-600">
                        <span class="ml-2 text-sm text-gray-600">Minuman</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="categories[]" value="Lainnya" class="rounded text-blue-600">
                        <span class="ml-2 text-sm text-gray-600">Lainnya</span>
                    </label>
                </div>
            </div>

            <button type="submit" 
                class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition duration-200 font-semibold">
                Upload & Scan
            </button>
        </form>

        @if(session('success'))
            <div class="mt-4 p-4 bg-green-100 text-green-700 rounded-md">
                {{ session('success') }}
            </div>
        @endif
    </div>
</body>
</html>