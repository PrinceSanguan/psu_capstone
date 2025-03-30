@extends('admin.layout.app')

@section('content')
<div class="container mt-3">
    <h1>Enrolled Students in Section: {{ $section->name }}</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
            </ul>
        </div>
    @endif

    <!-- If you want success messages -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($enrolledStudents->count() > 0)
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Student Number</th>
                    <th>Major</th>
                    <th>Sex</th>
                    <th>Course</th>
                    <th>Year</th>
                </tr>
            </thead>
            <tbody>
            @foreach($enrolledStudents as $student)
                <tr>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->student_number }}</td>
                    <td>{{ $student->major }}</td>
                    <td>{{ $student->sex }}</td>
                    <td>{{ $student->course }}</td>
                    <td>{{ $student->year }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-info">
            No students are enrolled in this section yet.
        </div>
    @endif

    <a href="{{ route('admin.assignments.index') }}" class="btn btn-secondary mt-3">
        Back to Assignments
    </a>
</div>
@endsection
