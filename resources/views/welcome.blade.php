<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ClassManagement: A Digital Solution for Academic Transparency at PSU</title>
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
    <!-- Navigation -->
    <div class="navbar bg-primary text-primary-content shadow-lg">
        <div class="navbar-start">
            <div class="dropdown">
                <label tabindex="0" class="btn btn-ghost lg:hidden">
                    <i class="fas fa-bars text-xl"></i>
                </label>
                <ul tabindex="0"
                    class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-base-100 rounded-box w-52 text-neutral">
                    <li><a href="#">Home</a></li>
                    <li><a href="#">About</a></li>
                    <li><a href="#">Features</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>
            <a class="btn btn-ghost normal-case text-xl">
                <i class="fas fa-graduation-cap mr-2"></i> PSU ClassManagement
            </a>
        </div>
        <div class="navbar-center hidden lg:flex">
            <ul class="menu menu-horizontal px-1">
                <li><a class="font-medium">Home</a></li>
                <li><a class="font-medium">About</a></li>
                <li><a class="font-medium">Features</a></li>
                <li><a class="font-medium">Contact</a></li>
            </ul>
        </div>
        <div class="navbar-end">
            <a href="{{ route('login.form') }}" class="btn btn-accent">Login</a>
        </div>
    </div>

    <!-- Hero Section -->
    <div class="hero min-h-[60vh] bg-base-100 shadow-sm">
        <div class="hero-content flex-col lg:flex-row-reverse p-4 md:p-8">
            <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&q=80"
                alt="Students using digital tools" class="max-w-full md:max-w-sm rounded-lg shadow-2xl mb-6 lg:mb-0" />
            <div>
                <h1 class="text-3xl md:text-5xl font-bold font-serif text-primary text-center lg:text-left">
                    ClassManagement</h1>
                <h2 class="text-xl md:text-2xl mt-2 font-serif text-center lg:text-left">A Digital Solution for Academic
                    Transparency</h2>
                <p class="py-4 md:py-6 text-center lg:text-left">Pangasinan State University's innovative platform
                    designed to enhance communication between students, faculty, and administration. Monitor grades,
                    track attendance, and access course materials in one unified system.</p>
                <div class="flex flex-col sm:flex-row gap-3 justify-center lg:justify-start">
                    <a href="{{ route('login.form') }}" class="btn btn-primary w-full sm:w-auto">Get Started</a>
                    <a href="#" class="btn btn-outline w-full sm:w-auto">Learn More</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-10 md:py-16 bg-base-200">
        <h2 class="text-2xl md:text-3xl font-bold text-center mb-8 md:mb-12 font-serif">Key Features</h2>
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                <div class="card bg-base-100 shadow-xl">
                    <figure class="px-6 md:px-10 pt-6 md:pt-10">
                        <div
                            class="w-16 h-16 md:w-20 md:h-20 rounded-full bg-primary/20 flex items-center justify-center">
                            <i class="fas fa-chart-line text-3xl md:text-4xl text-primary"></i>
                        </div>
                    </figure>
                    <div class="card-body items-center text-center px-4 py-5 md:px-6 md:py-6">
                        <h3 class="card-title text-lg md:text-xl">Grade Monitoring</h3>
                        <p class="text-sm md:text-base">Real-time access to academic performance with detailed
                            breakdowns by subject and assessment type.</p>
                    </div>
                </div>

                <div class="card bg-base-100 shadow-xl">
                    <figure class="px-10 pt-10">
                        <div class="w-20 h-20 rounded-full bg-primary/20 flex items-center justify-center">
                            <i class="fas fa-calendar-check text-4xl text-primary"></i>
                        </div>
                    </figure>
                    <div class="card-body items-center text-center">
                        <h3 class="card-title text-xl">Attendance Tracking</h3>
                        <p>Automated attendance recording and reporting for both students and faculty members.</p>
                    </div>
                </div>

                <div class="card bg-base-100 shadow-xl">
                    <figure class="px-10 pt-10">
                        <div class="w-20 h-20 rounded-full bg-primary/20 flex items-center justify-center">
                            <i class="fas fa-file-alt text-4xl text-primary"></i>
                        </div>
                    </figure>
                    <div class="card-body items-center text-center">
                        <h3 class="card-title text-xl">Course Resources</h3>
                        <p>Centralized repository for syllabi, lecture notes, and other educational materials.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Testimonials -->
    <div class="py-10 md:py-16 bg-base-100">
        <div class="container mx-auto px-4">
            <h2 class="text-2xl md:text-3xl font-bold text-center mb-8 md:mb-12 font-serif">What Our Community Says</h2>
            <div class="grid grid-cols-1 gap-6 max-w-lg mx-auto md:max-w-none md:grid-cols-2">
                <div class="card bg-base-200 shadow-md">
                    <div class="card-body p-4 md:p-6">
                        <div class="flex items-center mb-4">
                            <div class="avatar">
                                <div class="w-10 md:w-12 rounded-full">
                                    <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&q=80"
                                        alt="Faculty" />
                                </div>
                            </div>
                            <div class="ml-3 md:ml-4">
                                <h3 class="font-bold text-sm md:text-base">Dr. Maria Santos</h3>
                                <p class="text-xs md:text-sm">Faculty, Computer Science Department</p>
                            </div>
                        </div>
                        <p class="text-sm md:text-base">"The ClassManagement system has revolutionized how I monitor
                            student progress and provide timely feedback. It's an essential tool for modern education."
                        </p>
                        <div class="mt-2 text-warning">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>

                <div class="card bg-base-200 shadow-md">
                    <div class="card-body">
                        <div class="flex items-center mb-4">
                            <div class="avatar">
                                <div class="w-12 rounded-full">
                                    <img src="https://images.unsplash.com/photo-1531545514256-b1400bc00f31?auto=format&fit=crop&q=80"
                                        alt="Student" />
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="font-bold">Juan Dela Cruz</h3>
                                <p class="text-sm">Student, Business Administration</p>
                            </div>
                        </div>
                        <p>"I can easily track my academic progress and access course materials anytime. The system has
                            helped me stay organized and focused on my studies."</p>
                        <div class="mt-2 text-warning">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="py-8 md:py-16 bg-primary text-primary-content">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-2xl md:text-3xl font-bold mb-3 md:mb-4 font-serif">Ready to Transform Your Academic
                Experience?</h2>
            <p class="mb-6 md:mb-8 max-w-2xl mx-auto text-sm md:text-base">Join thousands of students and faculty
                members already benefiting from PSU's ClassManagement system.</p>
            <a href="{{ route('login.form') }}" class="btn btn-accent btn-md md:btn-lg">Login to Your Account</a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer p-6 md:p-10 bg-neutral text-neutral-content text-xs md:text-sm">
        <div>
            <span class="footer-title">Pangasinan State University</span>
            <a class="link link-hover">About Us</a>
            <a class="link link-hover">Academic Programs</a>
            <a class="link link-hover">Research</a>
            <a class="link link-hover">Extension Services</a>
        </div>
        <div>
            <span class="footer-title">ClassManagement</span>
            <a class="link link-hover">Features</a>
            <a class="link link-hover">FAQ</a>
            <a class="link link-hover">Support</a>
            <a class="link link-hover">Privacy Policy</a>
        </div>
        <div>
            <span class="footer-title">Contact</span>
            <a class="link link-hover">Urdaneta City, Pangasinan</a>
            <a class="link link-hover">admin@psu.edu.ph</a>
            <a class="link link-hover">+63 (075) 123-4567</a>
            <div class="grid grid-flow-col gap-4 mt-2">
                <a><i class="fab fa-facebook-f text-lg"></i></a>
                <a><i class="fab fa-twitter text-lg"></i></a>
                <a><i class="fab fa-youtube text-lg"></i></a>
            </div>
        </div>
    </footer>
    <footer class="footer footer-center p-4 bg-base-300 text-base-content text-xs md:text-sm">
        <div>
            <p>Copyright © 2025 - Pangasinan State University. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>
