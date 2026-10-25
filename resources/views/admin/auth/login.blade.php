<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="min-h-screen bg-slate-100 flex items-center justify-center">

    <div class="bg-white shadow-xl rounded-2xl p-8 w-full max-w-md">

        <h1 class="text-2xl font-bold text-center mb-6">
            Login Admin
        </h1>

        @if($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded-lg mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block mb-2">Email</label>
                <input
                    type="email"
                    name="email"
                    class="w-full border rounded-lg p-3"
                    required>
            </div>

          <div class="mb-6">
            <label class="block mb-2">Password</label>
            <div class="relative">
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="w-full border rounded-lg p-3 pr-10"
                    required>
                <button
                    type="button"
                    id="togglePassword"
                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>

            <button
                type="submit"
                class="w-full bg-blue-600 text-white p-3 rounded-lg">
                Login
            </button>
        </form>

    </div>

    <script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    togglePassword.addEventListener('click', function(e) {
        e.preventDefault();
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
    });
</script>

</body>
</html>