<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="w-full max-w-sm mx-4 sm:mx-0">
        <div class="bg-white shadow-md rounded-lg px-8 pt-6 pb-8">
            <h1 class="text-2xl font-bold text-center mb-2">Verifikasi Login</h1>
            <p class="text-center text-gray-500 mb-6">Kami telah mengirimkan kode OTP ke email Anda.</p>
            <form method="POST" action="{{ route('otp.verify') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="otp">Kode OTP (6 Digit)</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 text-center tracking-[1em] focus:outline-none focus:shadow-outline @error('otp') border-red-500 @enderror" 
                           id="otp" type="text" name="otp" required autofocus inputmode="numeric" maxlength="6">
                </div>
                <button class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded w-full" type="submit">
                    Verifikasi
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Pesan sukses dari session
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
                timer: 3000,
                showConfirmButton: false
            });
        @endif

        // Pesan error umum dari session
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('error') }}',
            });
        @endif

        // Menampilkan error validasi form (hanya pesan pertama)
        @if ($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'Gagal Verifikasi',
                text: '{{ $errors->first() }}',
            });
        @endif
    </script>
</body>
</html>