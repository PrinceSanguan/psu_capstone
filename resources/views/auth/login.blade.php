<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ClassManagement: PSU Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Merriweather:wght@300;400;700&display=swap');

        .font-serif {
            font-family: 'Merriweather', serif;
        }
    </style>
</head>

<body class="bg-base-200 min-h-screen">
    <!-- Simple Navigation -->
    <div class="navbar bg-primary text-primary-content">
        <div class="navbar-start">
            <a href="/" class="btn btn-ghost normal-case text-sm md:text-xl">
                <i class="fas fa-graduation-cap mr-1 md:mr-2"></i> PSU ClassManagement
            </a>
        </div>
        <div class="navbar-end">
            <a href="/" class="btn btn-ghost btn-sm">Home</a>
        </div>
    </div>

    <!-- Login Section -->
    <div class="min-h-[80vh] flex items-center justify-center p-4">
        <div class="card w-full max-w-sm bg-base-100 shadow-lg">
            <div class="card-body p-5 md:p-6">
                <div class="text-center mb-5">
                    <img src="https://images.unsplash.com/photo-1599687351724-dfa3c4ff81b1?auto=format&fit=crop&q=80&w=150&h=150"
                        class="rounded-full w-20 h-20 mx-auto mb-3 object-cover border-4 border-primary" alt="PSU Logo">
                    <h2 class="text-2xl md:text-3xl font-bold font-serif text-primary">Student Login</h2>
                    <p class="text-xs md:text-sm text-base-content/70 mt-1">Sign in to access your account</p>
                </div>

                <form action="{{ route('login.submit') }}" method="POST">
                    @csrf
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium">Student Number</span>
                        </label>
                        <input type="text" name="student_number" placeholder="Enter your student number"
                            class="input input-bordered w-full focus:outline-primary" required />
                        @error('student_number')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="form-control mt-3">
                        <label class="label">
                            <span class="label-text font-medium">Password</span>
                        </label>
                        <input type="password" name="password" placeholder="Enter your password"
                            class="input input-bordered w-full focus:outline-primary" required />
                        @error('password')
                            <label class="label">
                                <span class="label-text-alt text-error">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="form-control mt-5">
                        <button type="submit" class="btn btn-primary">
                            Sign In <i class="fas fa-sign-in-alt ml-2"></i>
                        </button>
                    </div>

                    @if (session('error'))
                        <div class="alert alert-error mt-4 text-xs">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                    @endif
                </form>

            </div>
        </div>
    </div>

    <!-- Simple Footer -->
    <footer class="footer footer-center p-4 bg-base-300 text-base-content text-xs">
        <div>
            <p>Copyright © 2025 - Pangasinan State University</p>
        </div>
    </footer>
</body>

</html>
