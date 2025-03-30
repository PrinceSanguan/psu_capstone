@extends('admin.layout.app')

@section('content')
<div class="container mt-3">
    <h1>Classes Assigned to {{ $faculty->name }}</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Section Name</th>
                <th>Subject Name</th>
                <th>Subject Code</th>
                <th>School Year</th>
                <th>Semester</th>
                <th>Actions</th> <!-- new column -->
            </tr>
        </thead>
        <tbody>
        @forelse($classes as $class)
            <tr>
                <td>{{ $class->section_name }}</td>
                <td>{{ $class->subject_name }}</td>
                <td>{{ $class->subject_code ?? 'N/A' }}</td>
                <td>{{ $class->school_year }}</td>
                <td>{{ $class->semester }}</td>
                <td>
                    <!-- LINK to new route that shows the enrolled students -->
                    <a href="{{ route('admin.assignments.showEnrolledStudents', $class->section_id) }}"
                       class="btn btn-sm btn-warning">
                        View Students
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">
                    No classes found for this faculty.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <!-- Link back to assignments index -->
    <a href="{{ route('admin.assignments.index') }}" class="btn btn-secondary mt-3">
        Back to Assignments
    </a>
</div>
@endsection
