<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Mahasiswa</title>

    <script src="https://cdn.tailwindcss.com"></script>
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
            Register Mahasiswa
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

                <input
                    type="password"
                    name="password"
                    class="w-full border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-brand"
                    placeholder="Masukkan Password"
                    required
                >
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

</body>
</html>