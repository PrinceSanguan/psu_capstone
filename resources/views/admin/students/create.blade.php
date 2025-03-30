@extends('admin.layout.app')

@section('content')
<div class="container mt-3">
    <h1>Add New Student</h1>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.storeStudent') }}" method="POST">
        @csrf

        <!-- name -->
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text"
                   name="name"
                   id="name"
                   class="form-control"
                   value="{{ old('name') }}"
                   required>
        </div>

        <!-- student_number -->
        <div class="form-group">
            <label for="student_number">Student Number</label>
            <input type="text"
                   name="student_number"
                   id="student_number"
                   class="form-control"
                   value="{{ old('student_number') }}"
                   required>
        </div>

        <!-- major (optional) -->
        <div class="form-group">
            <label for="major">Major</label>
            <input type="text"
                   name="major"
                   id="major"
                   class="form-control"
                   value="{{ old('major') }}">
        </div>

        <!-- sex: M or F -->
        <div class="form-group">
            <label for="sex">Sex</label>
            <select name="sex" id="sex" class="form-control" required>
                <option value="">-- Select --</option>
                <option value="M" {{ old('sex')=='M' ? 'selected' : '' }}>Male</option>
                <option value="F" {{ old('sex')=='F' ? 'selected' : '' }}>Female</option>
            </select>
        </div>

        <!-- course -->
        <div class="form-group">
            <label for="course">Course</label>
            <input type="text"
                   name="course"
                   id="course"
                   class="form-control"
                   value="{{ old('course') }}"
                   required>
        </div>

        <!-- year -->
        <div class="form-group">
            <label for="year">Year</label>
            <input type="text"
                   name="year"
                   id="year"
                   class="form-control"
                   value="{{ old('year') }}"
                   required>
        </div>

        <!-- password -->
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password"
                   name="password"
                   id="password"
                   class="form-control"
                   required>
        </div>

        <button type="submit" class="btn btn-primary">
            Add Student
        </button>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
            Cancel
        </a>
    </form>
</div>
@endsection
