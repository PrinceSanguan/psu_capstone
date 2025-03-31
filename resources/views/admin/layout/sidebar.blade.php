<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard') }}" class="brand-link">
        <i class="fas fa-graduation-cap brand-image img-circle elevation-3 mt-1" style="opacity: .8"></i>
        <span class="brand-text font-weight-light">PSU Admin Panel</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        @php
            // If you're using something like Auth::user() for admin
            $adminUser = Auth::user();
            // Example: take first two letters of name as initials
            $initials = strtoupper(substr($adminUser->name ?? 'Admin', 0, 2));
        @endphp

        <!-- Sidebar user panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <div class="img-circle elevation-2 bg-info"
                     style="width: 34px; height: 34px; display: flex; align-items: center; justify-content: center;">
                    <span class="text-white">{{ $initials }}</span>
                </div>
            </div>
            <div class="info">
                <a href="#" class="d-block">
                    {{ $adminUser->name ?? 'Administrator' }}
                </a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul
                class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview"
                role="menu"
                data-accordion="false"
            >
                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}"
                       class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- Example: Manage Faculty -->
                <li class="nav-item">
                    <a href="{{ route('admin.faculty.index') }}"
                       class="nav-link {{ request()->routeIs('admin.faculty.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chalkboard-teacher"></i>
                        <p>Manage Faculty</p>
                    </a>
                </li>

                <!-- Example: Assignments -->
                <li class="nav-item">
                    <a href="{{ route('admin.assignments.index') }}"
                       class="nav-link {{ request()->routeIs('admin.assignments.*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tasks"></i>
                        <p>Faculty Assignments</p>
                    </a>
                </li>

                <!-- Add any other Admin menus here... -->

                <!-- Logout -->
                <li class="nav-item mt-4">
                    <a href="{{ route('logout') }}" class="nav-link bg-danger">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
