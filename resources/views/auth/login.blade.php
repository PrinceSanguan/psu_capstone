<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Harvard Academic Portal: Secure Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Crimson+Text:wght@400;600;700&family=Open+Sans:wght@300;400;600&display=swap');

        .font-crimson {
            font-family: 'Crimson Text', serif;
        }

        .font-open {
            font-family: 'Open Sans', sans-serif;
        }

        .harvard-red {
            background-color: #A41034;
        }

        .harvard-red-text {
            color: #A41034;
        }

        .gradient-bg {
            background: linear-gradient(135deg, rgba(164, 16, 52, 0.1) 0%, rgba(140, 21, 21, 0.1) 100%);
        }

        .login-shadow {
            box-shadow: 0 20px 50px rgba(164, 16, 52, 0.15);
        }
    </style>
</head>

<body class="bg-slate-50 min-h-screen font-open gradient-bg">
    <!-- Navigation -->
    <div class="navbar bg-white shadow-lg border-b-2 border-red-800">
        <div class="navbar-start">
            <a href="/" class="btn btn-ghost normal-case text-sm md:text-xl font-crimson text-red-800 font-bold">
                <i class="fas fa-university mr-1 md:mr-2"></i> Harvard Academic Portal
            </a>
        </div>
        <div class="navbar-end">
            <a href="/" class="btn btn-ghost btn-sm text-gray-700 hover:text-red-800">Back to Home</a>
        </div>
    </div>

    <!-- Login Section -->
    <div class="min-h-[85vh] flex items-center justify-center p-4">
        <div class="card w-full max-w-md bg-white login-shadow border border-gray-100">
            <div class="card-body p-6 md:p-8">
                <!-- Harvard Branding -->
                <div class="text-center mb-6">
                    <div
                        class="w-24 h-24 mx-auto mb-4 bg-red-50 rounded-full flex items-center justify-center border-4 border-red-100">
                        <i class="fas fa-university text-3xl harvard-red-text"></i>
                    </div>
                    <h1 class="text-2xl md:text-3xl font-bold font-crimson harvard-red-text mb-2">
                        Harvard Portal
                    </h1>
                    <p class="text-sm md:text-base text-gray-600 font-open">
                        Access your academic dashboard
                    </p>
                    <div class="w-16 h-1 harvard-red mx-auto mt-3 rounded"></div>
                </div>

                <!-- Login Form -->
                <form action="{{ route('login.submit') }}" method="POST" class="space-y-5">
                    @csrf

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium text-gray-700 font-open">Student ID</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-id-card text-gray-400"></i>
                            </div>
                            <input type="text" name="student_number" placeholder="Enter your Harvard ID"
                                class="input input-bordered w-full pl-10 focus:border-red-800 focus:ring-1 focus:ring-red-800 bg-white"
                                required />
                        </div>
                        @error('student_number')
                            <label class="label">
                                <span class="label-text-alt text-red-600 font-open">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-medium text-gray-700 font-open">Password</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input type="password" name="password" placeholder="Enter your password"
                                class="input input-bordered w-full pl-10 focus:border-red-800 focus:ring-1 focus:ring-red-800 bg-white"
                                required />
                        </div>
                        @error('password')
                            <label class="label">
                                <span class="label-text-alt text-red-600 font-open">{{ $message }}</span>
                            </label>
                        @enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between text-sm">
                        <label class="cursor-pointer label">
                            <input type="checkbox" class="checkbox checkbox-sm mr-2" />
                            <span class="label-text text-gray-600">Remember me</span>
                        </label>
                        <a href="#" class="link link-hover harvard-red-text font-medium">Forgot password?</a>
                    </div>

                    <div class="form-control mt-6">
                        <button type="submit"
                            class="btn harvard-red text-white hover:bg-red-900 border-none font-semibold py-3">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Access Portal
                        </button>
                    </div>

                    <!-- Error Messages -->
                    @if (session('error'))
                        <div class="alert bg-red-50 border border-red-200 text-red-800 mt-4">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span class="font-open">{{ session('error') }}</span>
                        </div>
                    @endif
                </form>

                <!-- Additional Info -->
                <div class="divider mt-6 mb-4">
                    <span class="text-gray-400 text-xs font-open">SECURE LOGIN</span>
                </div>

                <div class="text-center">
                    <p class="text-xs text-gray-500 font-open mb-2">
                        For technical support, contact Harvard IT Services
                    </p>
                    <div class="flex justify-center space-x-4 text-xs text-gray-400">
                        <a href="#" class="hover:harvard-red-text">Privacy Policy</a>
                        <span>•</span>
                        <a href="#" class="hover:harvard-red-text">Terms of Use</a>
                        <span>•</span>
                        <a href="#" class="hover:harvard-red-text">Help</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Footer -->
    <footer class="bg-gray-900 text-gray-300 py-6">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0">
                    <p class="text-sm font-crimson">
                        © 2025 The President and Fellows of Harvard College
                    </p>
                    <p class="text-xs text-gray-400 mt-1">
                        All rights reserved • Cambridge, Massachusetts 02138
                    </p>
                </div>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-red-400 transition-colors">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-red-400 transition-colors">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-red-400 transition-colors">
                        <i class="fab fa-linkedin"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-red-400 transition-colors">
                        <i class="fab fa-youtube"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>
