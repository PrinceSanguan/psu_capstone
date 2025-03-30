<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('faculty.dashboard') }}" class="brand-link">
        <i class="fas fa-user-tie brand-image img-circle elevation-3 mt-1" style="opacity: .8"></i>
        <span class="brand-text font-weight-light">Faculty Portal</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        @php
            $facultyUser = Auth::user();
            $initials = strtoupper(substr($facultyUser->name ?? 'Faculty', 0, 2));
        @endphp

        <!-- Sidebar user panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <div class="img-circle elevation-2 bg-info" style="width: 34px; height: 34px; display: flex; align-items: center; justify-content: center;">
                    <span class="text-white">{{ $initials }}</span>
                </div>
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ $facultyUser->name ?? 'Faculty User' }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('faculty.dashboard') }}" class="nav-link {{ request()->routeIs('faculty.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Faculty Dashboard</p>
                    </a>
                </li>
                <!-- My Classes -->
                <li class="nav-item">
                    <a href="{{ route('faculty.classes.index') }}" class="nav-link {{ request()->routeIs('faculty.classes.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chalkboard"></i>
                        <p>My Classes</p>
                    </a>
                </li>
                <!-- Syllabi -->
                <li class="nav-header">SYLLABUS</li>
                <li class="nav-item">
                    <a href="{{ route('faculty.syllabus.index') }}" class="nav-link {{ request()->routeIs('faculty.syllabus.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-upload"></i>
                        <p>Upload / View Syllabi</p>
                    </a>
                </li>
                <!-- Seat Plans -->
                <li class="nav-header">SEAT PLAN</li>
                <li class="nav-item">
                    <a href="{{ route('faculty.classes.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Generate Seat Plan</p>
                    </a>
                </li>
                <!-- Assessments -->
                <li class="nav-header">ASSESSMENTS</li>
                <li class="nav-item">
                    <a href="{{ route('faculty.classes.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-file-signature"></i>
                        <p>Schedule Quiz/Activity</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('faculty.classes.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-calculator"></i>
                        <p>Input Student Scores</p>
                    </a>
                </li>
                <!-- Analytics & Reports -->
                <li class="nav-header">ANALYTICS & REPORTS</li>
                <li class="nav-item">
                    <a href="{{ route('faculty.classes.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>Student Analytics</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('faculty.classes.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-file-pdf"></i>
                        <p>Generate Reports</p>
                    </a>
                </li>
                <!-- Logout -->
                <li class="nav-item mt-4">
                    <a href="{{ route('logout') }}" class="nav-link bg-danger">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>
