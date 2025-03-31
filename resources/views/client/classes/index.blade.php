@extends('layouts.client')

@section('title', 'My Classes')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">My Classes</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Group classes by school year and semester -->
    @php
        $groupedEnrollments = $enrollments->groupBy(function($item) {
            return $item->school_year . ' - ' . $item->semester . ' Semester';
        });
    @endphp

    @if($groupedEnrollments->isEmpty())
        <div class="card shadow mb-4">
            <div class="card-body">
                <p class="text-center">You are not enrolled in any classes.</p>
            </div>
        </div>
    @else
        @foreach($groupedEnrollments as $termName => $termEnrollments)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ $termName }}</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Subject Code</th>
                                    <th>Subject Name</th>
                                    <th>Section</th>
                                    <th>Faculty</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($termEnrollments as $enrollment)
                                    <tr>
                                        <td>{{ $enrollment->subject_code }}</td>
                                        <td>{{ $enrollment->subject_name }}</td>
                                        <td>{{ $enrollment->section_name }}</td>
                                        <td>{{ $enrollment->faculty_name }}</td>
                                        <td>
                                            <a href="{{ route('client.classes.details', [
                                                'sectionId' => $enrollment->section_id,
                                                'subjectId' => $enrollment->subject_id,
                                                'schoolYear' => $enrollment->school_year,
                                                'semester' => $enrollment->semester
                                            ]) }}" class="btn btn-primary btn-sm">
                                                <i class="fas fa-eye"></i> View Details
                                            </a>
                                            <a href="#" class="btn btn-info btn-sm chat-btn"
                                               data-faculty-id="{{ $enrollment->faculty_id }}"
                                               data-faculty-name="{{ $enrollment->faculty_name }}">
                                                <i class="fas fa-comment"></i> Chat with Teacher
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>

<!-- Chat Modal -->
<div class="modal fade" id="chatModal" tabindex="-1" role="dialog" aria-labelledby="chatModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="chatModalLabel">Chat with <span id="facultyName"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Do you want to open a chat with this teacher?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <a href="#" id="openChatBtn" class="btn btn-primary">Open Chat</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#dataTable').DataTable();

        // Handle chat button click
        $('.chat-btn').on('click', function(e) {
            e.preventDefault();

            const facultyId = $(this).data('faculty-id');
            const facultyName = $(this).data('faculty-name');

            $('#facultyName').text(facultyName);
            $('#openChatBtn').attr('href', '{{ route("client.messages.index") }}?faculty=' + facultyId);

            $('#chatModal').modal('show');
        });
    });
</script>
@endsection
