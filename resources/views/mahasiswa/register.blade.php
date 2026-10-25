<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Mahasiswa</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            DEFAULT: '#1a6e9e',
                            dark: '#0f4d80',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">

        <h1 class="text-2xl font-bold text-center mb-6">
           Register
        </h1>

        {{-- ALERT ERROR --}}
        @if(session('error'))
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        {{-- ALERT SUCCESS --}}
        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="/register" method="POST">

            @csrf

            {{-- NIM --}}
            <div class="mb-4">
                <label class="block mb-2 font-semibold">
                    NIM
                </label>

                <input
                    type="text"
                    name="nim"
                    class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-brand"
                    placeholder="Masukkan NIM"
                    required
                >
            </div>

            {{-- NAMA --}}
            <div class="mb-4">
                <label class="block mb-2 font-semibold">
                    Nama Lengkap
                </label>

                <input
                    type="text"
                    name="nama"
                    class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-brand"
                    placeholder="Masukkan Nama"
                    required
                >
            </div>

            {{-- EMAIL --}}
            <div class="mb-4">
                <label class="block mb-2 font-semibold">
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-brand"
                    placeholder="Masukkan Email"
                    required
                >
            </div>

            {{-- PASSWORD --}}
            <div class="mb-6">
                <label class="block mb-2 font-semibold">
                    Password
                </label>

                <div class="relative">
                    <input
                        id="passwordInput"
                        type="password"
                        name="password"
                        class="w-full border rounded-lg px-4 py-2 pr-10 focus:outline-none focus:ring-2 focus:ring-brand"
                        placeholder="Masukkan Password"
                        required
                    >
                    <button
                        type="button"
                        id="togglePassword"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-brand transition"
                    >
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            {{-- BUTTON --}}
            <button
                type="submit"
                class="w-full bg-brand hover:bg-brand-dark text-white font-semibold py-3 rounded-lg transition mb-4"
            >
                Register
            </button>

            {{-- LOGIN LINK --}}
            <div class="text-center text-sm text-gray-600">
                Sudah punya akun? 
                <a href="/login" class="text-brand hover:text-brand-dark font-semibold underline transition">
                    Login
                </a>
            </div>

        </form>

    </div>

    <script>
        const passwordInput = document.getElementById('passwordInput');
        const togglePassword = document.getElementById('togglePassword');

        togglePassword.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                togglePassword.innerHTML = '<i class="fas fa-eye-slash"></i>';
            } else {
                passwordInput.type = 'password';
                togglePassword.innerHTML = '<i class="fas fa-eye"></i>';
            }
        });
    </script>

</body>
</html>