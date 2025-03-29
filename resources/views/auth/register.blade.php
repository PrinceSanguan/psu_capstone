<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet" type="text/css" />
</head>

<body class="bg-gray-100 h-screen flex items-center justify-center">
    <div class="card w-96 bg-white shadow-xl p-8 rounded-2xl">
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">Register</h1>
        <form>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                <input type="text" class="input input-bordered w-full" placeholder="Enter your name" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <input type="email" class="input input-bordered w-full" placeholder="Enter your email" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input type="password" class="input input-bordered w-full" placeholder="Enter your password" required>
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Retype Password</label>
                <input type="password" class="input input-bordered w-full" placeholder="Retype your password" required>
            </div>
            <button type="submit" class="btn btn-primary w-full">Register</button>
        </form>
    </div>
</body>

</html>
