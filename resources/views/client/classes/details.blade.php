@extends('layouts.client')

@section('title', 'Class Details')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $subject->code }} - {{ $subject->name }}</h1>
        <a href="{{ route('client.classes.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Back to Classes
        </a>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Class Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Subject:</strong> {{ $subject->code }} - {{ $subject->name }}
                    </div>
                    <div class="mb-3">
                        <strong>Section:</strong> {{ $section->name }}
                    </div>
                    <div class="mb-3">
                        <strong>Teacher:</strong> {{ $faculty->name }}
                    </div>
                    <div class="mb-3">
                        <strong>School Year:</strong> {{ $schoolYear }}
                    </div>
                    <div class="mb-3">
                        <strong>Semester:</strong> {{ $semester }}
                    </div>
                    <div class="mb-3">
                        <strong>Description:</strong> {{ $subject->description ?? 'No description available' }}
                    </div>

                    @if($syllabus)
                        <div class="mb-3">
                            <strong>Syllabus:</strong>
                            <a href="{{ route('client.syllabus.download', $syllabus->id) }}" class="btn btn-sm btn-info ml-2">
                                <i class="fas fa-download"></i> Download
                            </a>
                        </div>
                    @endif

                    <div class="mt-4">
                        <a href="#" class="btn btn-primary btn-sm chat-btn"
                           data-faculty-id="{{ $faculty->id }}"
                           data-faculty-name="{{ $faculty->name }}">
                            <i class="fas fa-comment"></i> Chat with Teacher
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Your Grades</h6>
                </div>
                <div class="card-body">
                    <!-- Grade summary -->
                    <div class="row mb-4">
                        <div class="col-md-4 text-center">
                            <div class="card border-left-primary h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Midterm Grade</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($midtermGrade, 2) }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="card border-left-success h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Final Grade</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($finalGrade, 2) }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="card border-left-info h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                Overall Grade</div>
                                            <div class="h5 mb-0 font-weight-bold
                                                @if($overallGrade >= 75) text-success @else text-danger @endif">
                                                {{ number_format($overallGrade, 2) }}
                                                @if($overallGrade >= 75) (Passing) @else (Failing) @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Grading System -->
                    <div class="mb-4">
                        <h6 class="font-weight-bold">Grading System:</h6>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Quizzes</div>
                                <div class="mb-0">{{ $gradingSystem->quiz_percentage }}%</div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Unit Tests</div>
                                <div class="mb-0">{{ $gradingSystem->unit_test_percentage }}%</div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Activities</div>
                                <div class="mb-0">{{ $gradingSystem->activity_percentage }}%</div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-xs font-weight-bold text-uppercase mb-1">Exams</div>
                                <div class="mb-0">{{ $gradingSystem->exam_percentage }}%</div>
                            </div>
                        </div>
                    </div>

                    <!-- Detailed Assessment Scores -->
                    <h6 class="font-weight-bold">Assessment Scores:</h6>
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="midterm-tab" data-toggle="tab" href="#midterm" role="tab"
                               aria-controls="midterm" aria-selected="true">Midterm</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="final-tab" data-toggle="tab" href="#final" role="tab"
                               aria-controls="final" aria-selected="false">Final</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <!-- Midterm Assessments -->
                        <div class="tab-pane fade show active" id="midterm" role="tabpanel" aria-labelledby="midterm-tab">
                            <div class="table-responsive mt-3">
                                <table class="table table-bordered" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Type</th>
                                            <th>Score</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $hasMidtermScores = false; @endphp
                                        @foreach($assessments->where('term', 'midterm') as $assessment)
                                            @php $hasMidtermScores = true; @endphp
                                            <tr>
                                                <td>{{ $assessment->title }}</td>
                                                <td>{{ ucfirst(str_replace('_', ' ', $assessment->type)) }}</td>
                                                <td>
                                                    @if(isset($scores[$assessment->id]))
                                                        {{ $scores[$assessment->id] }} / {{ $assessment->max_score }}
                                                        ({{ round(($scores[$assessment->id] / $assessment->max_score) * 100, 2) }}%)
                                                    @else
                                                        Not yet graded
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($assessment->schedule_date)
                                                        {{ date('M d, Y', strtotime($assessment->schedule_date)) }}
                                                    @else
                                                        Not scheduled
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        @if(!$hasMidtermScores)
                                            <tr>
                                                <td colspan="4" class="text-center">No midterm assessments available.</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Final Assessments -->
                        <div class="tab-pane fade" id="final" role="tabpanel" aria-labelledby="final-tab">
                            <div class="table-responsive mt-3">
                                <table class="table table-bordered" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Type</th>
                                            <th>Score</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $hasFinalScores = false; @endphp
                                        @foreach($assessments->where('term', 'final') as $assessment)
                                            @php $hasFinalScores = true; @endphp
                                            <tr>
                                                <td>{{ $assessment->title }}</td>
                                                <td>{{ ucfirst(str_replace('_', ' ', $assessment->type)) }}</td>
                                                <td>
                                                    @if(isset($scores[$assessment->id]))
                                                        {{ $scores[$assessment->id] }} / {{ $assessment->max_score }}
                                                        ({{ round(($scores[$assessment->id] / $assessment->max_score) * 100, 2) }}%)
                                                    @else
                                                        Not yet graded
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($assessment->schedule_date)
                                                        {{ date('M d, Y', strtotime($assessment->schedule_date)) }}
                                                    @else
                                                        Not scheduled
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        @if(!$hasFinalScores)
                                            <tr>
                                                <td colspan="4" class="text-center">No final assessments available.</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
