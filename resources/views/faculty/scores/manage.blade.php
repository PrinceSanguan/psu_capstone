@extends('faculty.layout.app')

@section('content')
<div class="container-fluid">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Manage Scores: {{ $assessment->title }}</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('faculty.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('faculty.classes.index') }}">My Classes</a></li>
                        <li class="breadcrumb-item active">Manage Scores</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <section class="content">
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
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

            <div class="row">
                <div class="col-md-4">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Assessment Information</h3>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-unbordered mb-3">
                                <li class="list-group-item"><b>Subject:</b> <a class="float-right">{{ $subject->name }}</a></li>
                                <li class="list-group-item"><b>Assessment Title:</b> <a class="float-right">{{ $assessment->title }}</a></li>
                                <li class="list-group-item"><b>Type:</b> <a class="float-right">{{ ucfirst(str_replace('_', ' ', $assessment->type)) }}</a></li>
                                <li class="list-group-item"><b>Term:</b> <a class="float-right">{{ ucfirst($assessment->term) }}</a></li>
                                <li class="list-group-item"><b>Maximum Score:</b> <a class="float-right">{{ $assessment->max_score }}</a></li>
                                @if($assessment->schedule_date)
                                <li class="list-group-item"><b>Schedule:</b> <a class="float-right">{{ date('F d, Y', strtotime($assessment->schedule_date)) }}
                                    @if($assessment->schedule_time)
                                        {{ date('h:i A', strtotime($assessment->schedule_time)) }}
                                    @endif
                                </a></li>
                                @endif
                                <li class="list-group-item"><b>Students:</b> <a class="float-right">{{ $students->count() }}</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Scoring Guide</h3>
                        </div>
                        <div class="card-body">
                            <ul>
                                <li>Enter scores between 0 and {{ $assessment->max_score }}.</li>
                                <li>Leave the field empty for students who did not take the assessment.</li>
                                <li>Click "Save Scores" after entering all scores.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Student Scores</h3>
                            <div class="card-tools">
                                <span class="badge badge-light">{{ $students->count() }} Students</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('faculty.scores.save', ['assessmentId' => $assessment->id]) }}" method="POST">
                                @csrf
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th style="width: 50px;">#</th>
                                                <th>Student Number</th>
                                                <th>Student Name</th>
                                                <th style="width: 120px;">Score <small>(Max: {{ $assessment->max_score }})</small></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($students as $index => $student)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $student->student_number }}</td>
                                                    <td>{{ $student->name }}</td>
                                                    <td>
                                                        <input type="number"
                                                            class="form-control"
                                                            name="scores[{{ $student->id }}]"
                                                            value="{{ $scores[$student->id] ?? '' }}"
                                                            min="0"
                                                            max="{{ $assessment->max_score }}"
                                                            step="0.5">
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center">No students found for this assessment.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-success btn-lg">
                                        <i class="fas fa-save"></i> Save Scores
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Statistics</h3>
                        </div>
                        <div class="card-body">
                            @php
                                $scoreValues = $scores->filter(function($value) { return $value !== null; });
                                $count = $scoreValues->count();
                                $avg = $count > 0 ? $scoreValues->sum() / $count : 0;
                                $highest = $count > 0 ? $scoreValues->max() : 0;
                                $lowest = $count > 0 ? $scoreValues->min() : 0;
                                $submitCount = $count;
                                $notSubmitCount = $students->count() - $count;
                            @endphp
                            <div class="row">
                                <div class="col-md-3 col-sm-6">
                                    <div class="info-box bg-info">
                                        <span class="info-box-icon"><i class="fas fa-calculator"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Average</span>
                                            <span class="info-box-number">{{ number_format($avg, 1) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="info-box bg-success">
                                        <span class="info-box-icon"><i class="fas fa-arrow-up"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Highest</span>
                                            <span class="info-box-number">{{ $highest }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="info-box bg-warning">
                                        <span class="info-box-icon"><i class="fas fa-arrow-down"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Lowest</span>
                                            <span class="info-box-number">{{ $lowest }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="info-box bg-danger">
                                        <span class="info-box-icon"><i class="fas fa-times"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Missing</span>
                                            <span class="info-box-number">{{ $notSubmitCount }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Highlight rows where score is below half of max score
        const maxScore = {{ $assessment->max_score }};
        const halfScore = maxScore / 2;

        document.querySelectorAll('input[name^="scores"]').forEach(function(input) {
            const scoreValue = parseFloat(input.value);
            if (!isNaN(scoreValue) && scoreValue < halfScore) {
                input.closest('tr').classList.add('table-warning');
            }

            // Add event listener to highlight/unhighlight on input change
            input.addEventListener('change', function() {
                const newScore = parseFloat(this.value);
                const row = this.closest('tr');

                if (!isNaN(newScore) && newScore < halfScore) {
                    row.classList.add('table-warning');
                } else {
                    row.classList.remove('table-warning');
                }
            });
        });
    });
</script>
@endsection
