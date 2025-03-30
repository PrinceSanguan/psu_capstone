@extends('admin.layout.app')

@section('content')
<div class="container mt-3">
    <h1>Faculty Assignments</h1>

    {{-- Validation errors & success messages --}}
    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Assignment creation form -->
    <div class="card card-primary mb-3">
        <div class="card-header">
            <h3 class="card-title">Assign Faculty to a Section & Subject</h3>
        </div>
        <form action="{{ route('admin.assignments.store') }}" method="POST">
            @csrf
            <div class="card-body">

                <!-- Faculty -->
                <div class="form-group">
                    <label for="faculty_id">Select Faculty</label>
                    <select name="faculty_id" id="faculty_id" class="form-control" required>
                        <option value="">-- Choose Faculty --</option>
                        @foreach($faculty as $f)
                            <option value="{{ $f->id }}">{{ $f->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Section Name -->
                <div class="form-group">
                    <label for="section_name">Section Name</label>
                    <input type="text" name="section_name" id="section_name" class="form-control" required>
                </div>

                <!-- Subject Name -->
                <div class="form-group">
                    <label for="subject_name">Subject Name</label>
                    <input type="text" name="subject_name" id="subject_name" class="form-control" required>
                </div>

                <!-- School Year -->
                <div class="form-group">
                    <label for="school_year">School Year</label>
                    <input type="text" name="school_year" id="school_year" class="form-control" placeholder="e.g. 2025-2026" required>
                </div>

                <!-- Semester -->
                <div class="form-group">
                    <label for="semester">Semester</label>
                    <select name="semester" id="semester" class="form-control" required>
                        <option value="">-- Choose Semester --</option>
                        <option value="First">First</option>
                        <option value="Second">Second</option>
                        <option value="Summer">Summer</option>
                    </select>
                </div>
            </div>
            <div class="card-footer">
                <button class="btn btn-primary" type="submit">Assign Faculty</button>
            </div>
        </form>
    </div>

    <!-- List of existing assignments -->
    <div class="card card-secondary">
        <div class="card-header">
            <h3 class="card-title">Current Assignments</h3>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered mb-0">
                <thead>
                <tr>
                    <th>Faculty</th>
                    <th>Section</th>
                    <th>Subject</th>
                    <th>School Year</th>
                    <th>Semester</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($assignments as $assign)
                    <tr>
                        <td>{{ $assign->faculty_name }}</td>
                        <td>{{ $assign->section_name }}</td>
                        <td>{{ $assign->subject_name }}</td>
                        <td>{{ $assign->school_year }}</td>
                        <td>{{ $assign->semester }}</td>
                        <td>
                            <!-- Link to view faculty classes -->
                            <a href="{{ route('admin.assignments.facultyClasses', $assign->faculty_id) }}"
                               class="btn btn-sm btn-info">View Classes</a>

                            <!-- Link to add students to the created section -->
                            <a href="{{ route('admin.sections.showStudents', $assign->section_id) }}"
                               class="btn btn-sm btn-warning">
                                Add Students
                            </a>

                            <!-- Delete assignment -->
                            <form action="{{ route('admin.assignments.delete', $assign->id) }}"
                                  method="POST" style="display:inline-block">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure?')">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center">No assignments found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
