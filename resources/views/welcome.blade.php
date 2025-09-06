<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Harvard Academic Portal: Excellence in Digital Education Management</title>
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

        .gradient-overlay {
            background: linear-gradient(135deg, rgba(164, 16, 52, 0.9) 0%, rgba(140, 21, 21, 0.9) 100%);
        }
    </style>
</head>

<body class="bg-slate-50 min-h-screen font-open">
    <!-- Navigation -->
    <div class="navbar bg-white shadow-lg border-b-2 border-red-800">
        <div class="navbar-start">
            <div class="dropdown">
                <label tabindex="0" class="btn btn-ghost lg:hidden">
                    <i class="fas fa-bars text-xl text-red-800"></i>
                </label>
                <ul tabindex="0"
                    class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow-xl bg-white rounded-lg w-52 border">
                    <li><a href="#" class="text-gray-700 hover:text-red-800">Home</a></li>
                    <li><a href="#" class="text-gray-700 hover:text-red-800">Academics</a></li>
                    <li><a href="#" class="text-gray-700 hover:text-red-800">Research</a></li>
                    <li><a href="#" class="text-gray-700 hover:text-red-800">Campus Life</a></li>
                </ul>
            </div>
            <a class="btn btn-ghost normal-case text-xl font-crimson text-red-800 font-bold">
                <i class="fas fa-university mr-2"></i> Harvard Academic Portal
            </a>
        </div>
        <div class="navbar-center hidden lg:flex">
            <ul class="menu menu-horizontal px-1">
                <li><a class="font-medium text-gray-700 hover:text-red-800">Home</a></li>
                <li><a class="font-medium text-gray-700 hover:text-red-800">Academics</a></li>
                <li><a class="font-medium text-gray-700 hover:text-red-800">Research</a></li>
                <li><a class="font-medium text-gray-700 hover:text-red-800">Campus Life</a></li>
            </ul>
        </div>
        <div class="navbar-end">
            <a href="{{ route('login.form') }}" class="btn harvard-red text-white hover:bg-red-900 border-none">Access
                Portal</a>
        </div>
    </div>

    <!-- Hero Section with Harvard-style banner -->
    <div class="relative min-h-[70vh] bg-cover bg-center"
        style="background-image: url('https://images.unsplash.com/photo-1564981797816-1043664bf78d?auto=format&fit=crop&q=80');">
        <div class="absolute inset-0 gradient-overlay"></div>
        <div class="relative hero min-h-[70vh]">
            <div class="hero-content text-center text-white p-4 md:p-8">
                <div class="max-w-4xl">
                    <h1 class="text-4xl md:text-6xl font-bold font-crimson mb-4">
                        Harvard Academic Portal
                    </h1>
                    <h2 class="text-xl md:text-3xl mb-6 font-crimson opacity-90">
                        Veritas Through Digital Innovation
                    </h2>
                    <p class="text-lg md:text-xl mb-8 max-w-3xl mx-auto leading-relaxed">
                        Experience Harvard's commitment to academic excellence through our state-of-the-art digital
                        platform.
                        Seamlessly manage your academic journey with tools designed for the world's most prestigious
                        institution.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('login.form') }}"
                            class="btn btn-lg bg-white text-red-800 hover:bg-gray-100 border-none font-semibold">
                            Enter Portal
                        </a>
                        <a href="#features"
                            class="btn btn-lg btn-outline text-white border-white hover:bg-white hover:text-red-800">
                            Explore Features
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Harvard Traditions Section -->
    <div class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold font-crimson harvard-red-text mb-4">Academic Excellence Since
                    1636</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Building upon nearly four centuries of educational leadership, our digital platform embodies
                    Harvard's
                    commitment to scholarly pursuit and intellectual growth.
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-20 h-20 mx-auto mb-4 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-scroll text-3xl harvard-red-text"></i>
                    </div>
                    <h3 class="text-xl font-bold font-crimson mb-2">Historic Legacy</h3>
                    <p class="text-gray-600">Nearly four centuries of educational excellence and innovation in higher
                        learning.</p>
                </div>
                <div class="text-center">
                    <div class="w-20 h-20 mx-auto mb-4 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-globe text-3xl harvard-red-text"></i>
                    </div>
                    <h3 class="text-xl font-bold font-crimson mb-2">Global Impact</h3>
                    <p class="text-gray-600">Shaping leaders and innovators who transform communities worldwide.</p>
                </div>
                <div class="text-center">
                    <div class="w-20 h-20 mx-auto mb-4 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-lightbulb text-3xl harvard-red-text"></i>
                    </div>
                    <h3 class="text-xl font-bold font-crimson mb-2">Innovation</h3>
                    <p class="text-gray-600">Pioneering research and breakthrough discoveries that advance human
                        knowledge.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div id="features" class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl md:text-4xl font-bold text-center mb-12 font-crimson harvard-red-text">
                Portal Capabilities
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="card bg-white shadow-xl border border-gray-200 hover:shadow-2xl transition-shadow">
                    <figure class="px-10 pt-10">
                        <div class="w-20 h-20 rounded-full bg-red-50 flex items-center justify-center">
                            <i class="fas fa-medal text-4xl harvard-red-text"></i>
                        </div>
                    </figure>
                    <div class="card-body items-center text-center">
                        <h3 class="card-title text-xl font-crimson">Academic Excellence</h3>
                        <p class="text-gray-600">
                            Comprehensive grade tracking and performance analytics aligned with Harvard's rigorous
                            academic standards.
                        </p>
                    </div>
                </div>

                <div class="card bg-white shadow-xl border border-gray-200 hover:shadow-2xl transition-shadow">
                    <figure class="px-10 pt-10">
                        <div class="w-20 h-20 rounded-full bg-red-50 flex items-center justify-center">
                            <i class="fas fa-users text-4xl harvard-red-text"></i>
                        </div>
                    </figure>
                    <div class="card-body items-center text-center">
                        <h3 class="card-title text-xl font-crimson">Community Engagement</h3>
                        <p class="text-gray-600">
                            Connect with fellow scholars, faculty, and the broader Harvard community through integrated
                            tools.
                        </p>
                    </div>
                </div>

                <div class="card bg-white shadow-xl border border-gray-200 hover:shadow-2xl transition-shadow">
                    <figure class="px-10 pt-10">
                        <div class="w-20 h-20 rounded-full bg-red-50 flex items-center justify-center">
                            <i class="fas fa-brain text-4xl harvard-red-text"></i>
                        </div>
                    </figure>
                    <div class="card-body items-center text-center">
                        <h3 class="card-title text-xl font-crimson">Research Resources</h3>
                        <p class="text-gray-600">
                            Access to Harvard's vast digital libraries, research databases, and scholarly publications.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Testimonials -->
    <div class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl md:text-4xl font-bold text-center mb-12 font-crimson harvard-red-text">
                Voices from Harvard
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <div class="card bg-gray-50 shadow-lg border">
                    <div class="card-body">
                        <div class="flex items-center mb-4">
                            <div class="avatar">
                                <div class="w-12 rounded-full">
                                    <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&q=80"
                                        alt="Professor" />
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="font-bold font-crimson">Professor James Mitchell</h3>
                                <p class="text-sm text-gray-600">Kennedy School of Government</p>
                            </div>
                        </div>
                        <p class="text-gray-700 italic">
                            "This portal exemplifies Harvard's dedication to innovation in education. It seamlessly
                            integrates
                            our academic traditions with cutting-edge technology."
                        </p>
                        <div class="mt-3 text-amber-500">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>

                <div class="card bg-gray-50 shadow-lg border">
                    <div class="card-body">
                        <div class="flex items-center mb-4">
                            <div class="avatar">
                                <div class="w-12 rounded-full">
                                    <img src="https://images.unsplash.com/photo-1494790108755-2616c5e83a78?auto=format&fit=crop&q=80"
                                        alt="Student" />
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="font-bold font-crimson">Emily Rodriguez</h3>
                                <p class="text-sm text-gray-600">Graduate Student, Harvard Business School</p>
                            </div>
                        </div>
                        <p class="text-gray-700 italic">
                            "The platform's intuitive design and comprehensive features have transformed how I engage
                            with my coursework and connect with the Harvard community."
                        </p>
                        <div class="mt-3 text-amber-500">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    <div class="py-16 harvard-red text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-4 font-crimson">
                Begin Your Harvard Journey
            </h2>
            <p class="text-xl mb-8 max-w-2xl mx-auto opacity-90">
                Join the legacy of excellence. Access your academic portal and become part of Harvard's continuing story
                of
                intellectual discovery and global impact.
            </p>
            <a href="{{ route('login.form') }}"
                class="btn btn-lg bg-white text-red-800 hover:bg-gray-100 border-none font-semibold">
                Access Your Portal
            </a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer p-10 bg-gray-900 text-gray-300">
        <div>
            <span class="footer-title text-white font-crimson">Harvard University</span>
            <a class="link link-hover">About Harvard</a>
            <a class="link link-hover">Schools & Divisions</a>
            <a class="link link-hover">Research Initiatives</a>
            <a class="link link-hover">Global Programs</a>
        </div>
        <div>
            <span class="footer-title text-white font-crimson">Academic Portal</span>
            <a class="link link-hover">Portal Features</a>
            <a class="link link-hover">Technical Support</a>
            <a class="link link-hover">User Guidelines</a>
            <a class="link link-hover">Privacy & Security</a>
        </div>
        <div>
            <span class="footer-title text-white font-crimson">Contact Harvard</span>
            <a class="link link-hover">Cambridge, Massachusetts 02138</a>
            <a class="link link-hover">info@harvard.edu</a>
            <a class="link link-hover">+1 (617) 495-1000</a>
            <div class="grid grid-flow-col gap-4 mt-2">
                <a><i class="fab fa-facebook-f text-lg hover:text-red-400"></i></a>
                <a><i class="fab fa-twitter text-lg hover:text-red-400"></i></a>
                <a><i class="fab fa-linkedin text-lg hover:text-red-400"></i></a>
                <a><i class="fab fa-youtube text-lg hover:text-red-400"></i></a>
            </div>
        </div>
    </footer>
    <footer class="footer footer-center p-4 bg-black text-gray-400">
        <div>
            <p class="font-crimson">© 2025 The President and Fellows of Harvard College. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>
