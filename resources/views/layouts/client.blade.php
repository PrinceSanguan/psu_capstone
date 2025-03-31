<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Student Portal">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - Student Portal</title>

    <!-- Custom fonts for this template-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/startbootstrap-sb-admin-2/4.1.4/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.25/css/dataTables.bootstrap4.min.css" rel="stylesheet">

    <!-- Additional styles -->
    @yield('styles')
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('client.dashboard') }}">
                <div class="sidebar-brand-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Student Portal</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item {{ request()->routeIs('client.dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('client.dashboard') }}">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Academics
            </div>

            <!-- Nav Item - Classes -->
            <li class="nav-item {{ request()->routeIs('client.classes.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('client.classes.index') }}">
                    <i class="fas fa-fw fa-book"></i>
                    <span>My Classes</span>
                </a>
            </li>

            <!-- Nav Item - Grades -->
            <li class="nav-item {{ request()->routeIs('client.grades.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('client.grades.index') }}">
                    <i class="fas fa-fw fa-chart-line"></i>
                    <span>My Grades</span>
                </a>
            </li>

            <!-- Nav Item - Schedules -->
            <li class="nav-item {{ request()->routeIs('client.schedules.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('client.schedules.index') }}">
                    <i class="fas fa-fw fa-calendar-alt"></i>
                    <span>Assessment Schedules</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Communication
            </div>

            <!-- Nav Item - Messages -->
            <li class="nav-item {{ request()->routeIs('client.messages.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('client.messages.index') }}">
                    <i class="fas fa-fw fa-envelope"></i>
                    <span>Messages</span>
                    @php
                        $unreadCount = DB::table('messages')
                            ->where('recipient_id', Auth::id())
                            ->where('read', false)
                            ->count();
                    @endphp
                    @if($unreadCount > 0)
                        <span class="badge badge-danger badge-counter">{{ $unreadCount }}</span>
                    @endif
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                @php
                                    $upcomingAssessments = DB::table('assessments')
                                        ->join('section_subject', 'assessments.subject_id', '=', 'section_subject.subject_id')
                                        ->join('section_student', function ($join) {
                                            $join->on('section_subject.section_id', '=', 'section_student.section_id')
                                                ->on('section_subject.school_year', '=', 'section_student.school_year')
                                                ->on('section_subject.semester', '=', 'section_student.semester');
                                        })
                                        ->where('section_student.student_id', Auth::id())
                                        ->whereNotNull('assessments.schedule_date')
                                        ->whereDate('assessments.schedule_date', '>=', now()->toDateString())
                                        ->whereDate('assessments.schedule_date', '<=', now()->addDays(3)->toDateString())
                                        ->count();
                                @endphp
                                @if($upcomingAssessments > 0)
                                    <span class="badge badge-danger badge-counter">{{ $upcomingAssessments }}</span>
                                @endif
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header">
                                    Upcoming Assessments
                                </h6>
                                @php
                                    $recentAssessments = DB::table('assessments')
                                        ->join('section_subject', 'assessments.subject_id', '=', 'section_subject.subject_id')
                                        ->join('section_student', function ($join) {
                                            $join->on('section_subject.section_id', '=', 'section_student.section_id')
                                                ->on('section_subject.school_year', '=', 'section_student.school_year')
                                                ->on('section_subject.semester', '=', 'section_subject.semester');
                                        })
                                        ->where('section_student.student_id', Auth::id())
                                        ->whereNotNull('assessments.schedule_date')
                                        ->whereDate('assessments.schedule_date', '>=', now()->toDateString())
                                        ->whereDate('assessments.schedule_date', '<=', now()->addDays(3)->toDateString())
                                        ->join('subjects', 'assessments.subject_id', '=', 'subjects.id')
                                        ->select(
                                            'assessments.*',
                                            'subjects.name as subject_name',
                                            'subjects.code as subject_code'
                                        )
                                        ->orderBy('assessments.schedule_date')
                                        ->limit(3)
                                        ->get();
                                @endphp
                                @forelse($recentAssessments as $assessment)
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('client.schedules.index') }}">
                                        <div class="mr-3">
                                            <div class="icon-circle bg-primary">
                                                <i class="fas fa-clipboard-list text-white"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="small text-gray-500">{{ date('M d, Y', strtotime($assessment->schedule_date)) }}</div>
                                            <span class="font-weight-bold">{{ $assessment->subject_code }}: {{ $assessment->title }}</span>
                                        </div>
                                    </a>
                                @empty
                                    <a class="dropdown-item d-flex align-items-center" href="#">
                                        <div class="mr-3">
                                            <div class="icon-circle bg-success">
                                                <i class="fas fa-check text-white"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <span>No upcoming assessments in the next 3 days</span>
                                        </div>
                                    </a>
                                @endforelse
                                <a class="dropdown-item text-center small text-gray-500" href="{{ route('client.schedules.index') }}">Show All Assessments</a>
                            </div>
                        </li>

                        <!-- Nav Item - Messages -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-envelope fa-fw"></i>
                                <!-- Counter - Messages -->
                                @php
                                    $unreadMessages = DB::table('messages')
                                        ->where('recipient_id', Auth::id())
                                        ->where('read', false)
                                        ->count();
                                @endphp
                                @if($unreadMessages > 0)
                                    <span class="badge badge-danger badge-counter">{{ $unreadMessages }}</span>
                                @endif
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="messagesDropdown">
                                <h6 class="dropdown-header">
                                    Message Center
                                </h6>
                                @php
                                    $recentMessages = DB::table('messages')
                                        ->where('recipient_id', Auth::id())
                                        ->join('users', 'messages.sender_id', '=', 'users.id')
                                        ->select(
                                            'messages.*',
                                            'users.name as sender_name'
                                        )
                                        ->orderBy('messages.created_at', 'desc')
                                        ->limit(3)
                                        ->get();
                                @endphp
                                @forelse($recentMessages as $message)
                                    <a class="dropdown-item d-flex align-items-center" href="{{ route('client.messages.index') }}">
                                        <div class="dropdown-list-image mr-3">
                                            <div class="icon-circle bg-primary">
                                                <span class="text-white font-weight-bold">{{ strtoupper(substr($message->sender_name, 0, 1)) }}</span>
                                            </div>
                                        </div>
                                        <div class="{{ $message->read ? 'font-weight-normal' : 'font-weight-bold' }}">
                                            <div class="text-truncate">{{ \Illuminate\Support\Str::limit($message->message, 30) }}</div>
                                            <div class="small text-gray-500">{{ $message->sender_name }} · {{ date('M d, Y', strtotime($message->created_at)) }}</div>
                                        </div>
                                    </a>
                                @empty
                                    <a class="dropdown-item d-flex align-items-center" href="#">
                                        <div class="mr-3">
                                            <div class="icon-circle bg-success">
                                                <i class="fas fa-check text-white"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <span>No messages to display</span>
                                        </div>
                                    </a>
                                @endforelse
                                <a class="dropdown-item text-center small text-gray-500" href="{{ route('client.messages.index') }}">Read More Messages</a>
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
                                <div class="img-profile rounded-circle d-flex align-items-center justify-content-center bg-primary">
                                    <span class="text-white font-weight-bold">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                </div>
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                @yield('content')
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Class Management System {{ date('Y') }}</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="{{ route('logout') }}">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/startbootstrap-sb-admin-2/4.1.4/js/sb-admin-2.min.js"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.25/js/dataTables.bootstrap4.min.js"></script>

    <!-- CSRF Token for AJAX -->
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <!-- Additional scripts -->
    @yield('scripts')

</body>

</html>
