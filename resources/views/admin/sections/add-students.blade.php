@extends('admin.layout.app')

@section('content')
<div class="container mt-3">
    <h1>Assign Students to Section: {{ $section->name }}</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.sections.storeStudents', $section->id) }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="school_year">School Year</label>
            <input type="text"
                   name="school_year"
                   id="school_year"
                   class="form-control"
                   value="{{ old('school_year', '2025-2026') }}"
                   required>
        </div>

        <div class="form-group">
            <label for="semester">Semester</label>
            <select name="semester" id="semester" class="form-control" required>
                <option value="First"  {{ old('semester')=='First'  ? 'selected' : '' }}>First</option>
                <option value="Second" {{ old('semester')=='Second' ? 'selected' : '' }}>Second</option>
                <option value="Summer" {{ old('semester')=='Summer' ? 'selected' : '' }}>Summer</option>
            </select>
        </div>

        <div class="form-group">
            <label>Select Students:</label>
            <div style="max-height: 400px; overflow-y: auto; border:1px solid #ccc; padding:10px;">
                @foreach($allStudents as $student)
                    <div class="form-check">
                        <input class="form-check-input"
                               type="checkbox"
                               name="students[]"
                               value="{{ $student->id }}"
                               id="student_{{ $student->id }}"
                               {{ in_array($student->id, $assignedStudents) ? 'checked' : '' }}>
                        <label class="form-check-label" for="student_{{ $student->id }}">
                            {{ $student->name }} ({{ $student->student_number }})
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Update Students</button>
        <a href="{{ route('admin.assignments.index') }}" class="btn btn-default">Cancel</a>
    </form>
</div>
@endsection
