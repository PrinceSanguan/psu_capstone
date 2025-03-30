@extends('admin.layout.app')

@section('content')
<div class="container mt-3">
    <!-- Display the subject code at the top -->
    <h1 class="mb-1">
        Edit Grading System for Subject ID: {{ $gradingSystem->subject_id }}
    </h1>
    <h5 class="text-muted mb-3">
        Subject Code: <strong>{{ $subject->code ?? 'N/A' }}</strong>
    </h5>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
            @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
            @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.updateGradingSystem', $gradingSystem->subject_id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="quiz_percentage">Quizzes (%)</label>
            <input type="number"
                   name="quiz_percentage"
                   id="quiz_percentage"
                   class="form-control"
                   value="{{ old('quiz_percentage', $gradingSystem->quiz_percentage) }}"
                   min="0" max="100" step="0.01"
                   required>
        </div>

        <div class="form-group">
            <label for="unit_test_percentage">Unit Tests (%)</label>
            <input type="number"
                   name="unit_test_percentage"
                   id="unit_test_percentage"
                   class="form-control"
                   value="{{ old('unit_test_percentage', $gradingSystem->unit_test_percentage) }}"
                   min="0" max="100" step="0.01"
                   required>
        </div>

        <div class="form-group">
            <label for="activity_percentage">Activities (%)</label>
            <input type="number"
                   name="activity_percentage"
                   id="activity_percentage"
                   class="form-control"
                   value="{{ old('activity_percentage', $gradingSystem->activity_percentage) }}"
                   min="0" max="100" step="0.01"
                   required>
        </div>

        <div class="form-group">
            <label for="exam_percentage">Exams (%)</label>
            <input type="number"
                   name="exam_percentage"
                   id="exam_percentage"
                   class="form-control"
                   value="{{ old('exam_percentage', $gradingSystem->exam_percentage) }}"
                   min="0" max="100" step="0.01"
                   required>
        </div>

        <button type="submit" class="btn btn-primary">Update Grading</button>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
            Cancel
        </a>
    </form>
</div>
@endsection
